from django.contrib.auth.models import User, AnonymousUser
from rest_framework import viewsets
from api.models import *
from api.users.models import *
from api.serializers import *
from rest_framework.authtoken.models import Token

#from rest_framework.authtoken.serializers import AuthTokenSerializer
from rest_framework import permissions, renderers, parsers, status
from rest_framework.response import Response

from django.contrib.auth.hashers import *
from django.http import Http404, HttpResponse



def sync_queryset_filter(view,queryset):
    sync_ts = view.request.QUERY_PARAMS.get('last_sync', None)
    if sync_ts is None:
        queryset.filter(is_deleted=False)
    else:
        queryset.filter(updated_at__gte=sync_ts)
    return queryset


class JSONResponse(HttpResponse):
    """
    An HttpResponse that renders its content into JSON.
    """
    def __init__(self, data, **kwargs):
        content = renderers.JSONRenderer().render(data)
        kwargs['content_type'] = 'application/json'
        super(JSONResponse, self).__init__(content, **kwargs)



class FamilyPermission(permissions.BasePermission):
    def check_family_permission(self,user_id, family_user_id):
        """
        User id : current authenticated user
        Family User id : current profile
        """
        if user_id == family_user_id:
            return True

        has_permission = False
        qqueryset = UserGroupSet.objects.filter(user_id__in=[user_id,family_user_id],group_id__in=[user_id,family_user_id],status='ACTIVE')
        for p in qqueryset:
            if p.group.id != p.user.id:
                has_permission = True

        return has_permission

    #TODO:optimize function
    def has_permission(self, request, view):
        has_permission = False
        family_user_id = request.QUERY_PARAMS.get('user', None)
        
        if family_user_id is None:
            #List page and
            #Current user is the family user
            return True
        family_user_id = int(family_user_id)
        user_id = int(request.user.id)
        return self.check_family_permission(user_id, family_user_id)

    def has_object_permission(self, request, view, obj):
        family_user_id =  int(obj.user.id)
        user_id = int(request.user.id)
        return self.check_family_permission(user_id, family_user_id)

"""
For static models like food_items
"""
class ViamModelViewSetNoUser(viewsets.ModelViewSet):

    permission_classes = (permissions.IsAuthenticated,)

    def restruct_serializer(self):
        sync_ts = self.request.QUERY_PARAMS.get('last_sync', None)
        if sync_ts is not None:
            self.serializer_class.is_deleted = serializers.BooleanField(read_only=True,required=False)
            self.serializer_class.Meta.fields += ('is_deleted',)

    def sync_queryset_filter(self,queryset):
        self.restruct_serializer()
        sync_ts = self.request.QUERY_PARAMS.get('last_sync', None)
        if sync_ts is None:
            queryset = queryset.filter(is_deleted=False)
        else:
            queryset = queryset.filter(updated_at__gte=sync_ts)
        return queryset

    def get_queryset(self):
        queryset = super(ViamModelViewSetNoUser, self).get_queryset()
        return self.sync_queryset_filter(queryset)

    def get_object(self):
        o = super(ViamModelViewSetNoUser, self).get_object()
        if o.is_deleted :
            raise Http404
        else:
            return o
        
    def list(self, request, format=None):
        return super(ViamModelViewSetNoUser, self).list(request,format)

    def create(self, request, format=None):
        return super(ViamModelViewSetNoUser, self).create(request,format)

    def retrieve(self, request, pk=None, format=None):
        return super(ViamModelViewSetNoUser, self).retrieve(request,pk,format)

    def update(self, request, pk=None, format=None):
        return super(ViamModelViewSetNoUser, self).update(request,pk,format)        

    def destroy(self,request, pk=None, format=None):
        o = self.get_object()
        o.soft_delete()
        return Response(status=status.HTTP_204_NO_CONTENT)


"""
For  models that are linked with a user
"""
class ViamModelViewSet(ViamModelViewSetNoUser):
    
    permission_classes = (permissions.IsAuthenticated,FamilyPermission,)
    #filter_fields = ('user',)

    #Custom helper functions
    def get_user_object(self):
        fuser = self.request.QUERY_PARAMS.get('user', None)
        if fuser is not None:
            return User.objects.get(pk=fuser)
        else:
            return self.request.user

    #Over riding viewset functions
    def get_queryset(self):
        queryset = super(ViamModelViewSet, self).get_queryset()
        if self.request.method not in permissions.SAFE_METHODS or self.action == 'retrieve':
            return queryset
        user = self.get_user_object()
        q = queryset.filter(user=user)
        return q

    def get_object(self):
        o = super(ViamModelViewSet, self).get_object()
        #TODO: remove below check. Super should take care of this
        self.check_object_permissions(self.request, o)
        return o
    
    def pre_save(self, obj):
        if hasattr(obj, 'user') == False:
            obj.user = self.get_user_object()
        if obj.user is None:
            obj.user = self.get_user_object()
        obj.updated_by = self.request.user

    def destroy(self, request, pk=None, format=None):
        #super(ViamModelViewSet, self).destroy(pk)
        o = self.get_object()
        o.soft_delete()
        #TODO: Find a way to automatically figure out, whether the model has updated_by field or not. and push this above
        o.updated_by = self.request.user
        o.save()
        return Response(status=status.HTTP_204_NO_CONTENT)


"""
Only for goal readings as reading_date is psuedo primary key for them
"""
class GoalReadingsViewSet(ViamModelViewSet):
    def get_object(self, reading_date):
        try:
            o = self.model.objects.get(reading_date=reading_date,user=self.get_user_object(),is_deleted=False)
            self.check_object_permissions(self.request, o)
            return o
        except self.model.DoesNotExist:
            raise Http404

    def create(self, request, format=None):
        serializer = self.get_serializer(data=request.DATA,)
        if serializer.is_valid():
            serializer.object.user = self.get_user_object()
            serializer.object.updated_by = self.request.user
            serializer.save()
            return Response(serializer.data, status=status.HTTP_201_CREATED)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    def retrieve(self, request, reading_date):
        m = self.get_object(reading_date)
        serializer = self.get_serializer(m)
        return Response(serializer.data)

    def update(self, request, reading_date):
        m = self.get_object(reading_date)
        serializer = self.get_serializer(m, data=request.DATA)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)




