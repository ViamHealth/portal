# Create your views here.
#TODOs
#Optimize has_permission
#Change the table for usermap
#implement delete for user - break connection
#implement delete for others - status inactive
from django.contrib.auth.models import User, AnonymousUser
from rest_framework import viewsets
from api.models import *
from api.serializers import *
from rest_framework.authtoken.models import Token
from django.core.signals import request_started
from rest_framework.views import APIView
from rest_framework.generics import ListAPIView
#from rest_framework.authtoken.serializers import AuthTokenSerializer
from rest_framework import permissions, renderers, parsers, status
from rest_framework.decorators import api_view, link, action
from rest_framework.mixins import DestroyModelMixin
from rest_framework.reverse import reverse
from rest_framework.response import Response
import pprint
from django.db.models import Q
from django.http import Http404
from itertools import chain
import time
from datetime import datetime, timedelta
from django.shortcuts import get_object_or_404
from rest_framework.parsers import MultiPartParser
import mimetypes

#Temporary create code for all users once.
for user in User.objects.all():
    enddate = datetime.today() - timedelta(days=7)
    #pprint.pprint('deleting since the datetime')
    #pprint.pprint(enddate)
    Token.objects.filter(created__lt=enddate).delete()
    #TODO: custom algo for creating token string
    Token.objects.get_or_create(user=user)

class FamilyPermission(permissions.BasePermission):
    def check_family_permission(self,user_id, family_user_id):
        """
        User id : current authenticated user
        Family User id : current profile
        """
        if user_id == family_user_id:
            return True
            #Q(connection_status='ACTIVE'),
        #TODO: improve this code
        has_permission = False

        qqueryset = UserGroupSet.objects.filter(user_id__in=[user_id,family_user_id],group_id__in=[user_id,family_user_id])
        for p in qqueryset:
            has_permission = True

        return has_permission

    #TODO:optimize function
    def has_permission(self, request, view):
        has_permission = False
        family_user_id = request.QUERY_PARAMS.get('user_id', None)
        if family_user_id is None:
            family_user_id = view.kwargs.get('pk')
        
        if family_user_id is None:
            #List page and
            #Current user is the family user
            return True
        family_user_id = int(family_user_id)
        user_id = int(request.user.id)
        return self.check_family_permission(user_id, family_user_id)

    """
    Not needed right now. Permission layer controlled by get_queryset
    """
    def has_object_permission(self, request, view, obj=None):
        if request is None:
            request = self.request

        has_permission = False
        if obj is not None:
           #Object level authentication. check if current object's user is mapped with current login user
           family_user_id =  obj.user.id
        
        family_user_id = int(family_user_id)
        user_id = int(request.user.id)
        return self.check_family_permission(user_id, family_user_id)
        
"""
function to get object , with ACTIVE status

"""

def global_get_object(view):
    queryset = view.get_queryset()
    filter = {}
    filter[view.lookup_field] = view.kwargs[view.lookup_field]
    model = get_object_or_404(queryset, **filter)
    #Redundant as queryset will have auto check
    #view.check_object_permissions(view.request, model)
    return model


def global_get_object_old(view, model):
    pk = view.kwargs.get('pk')
    if pk is not None:
        try:
            queryset = model.objects.get(pk=pk, status='ACTIVE')
            view.check_object_permissions(view.request, queryset)
            return queryset
        except model.DoesNotExist:
            #return Response(status=status.HTTP_404_NOT_FOUND)
            raise Http404

"""
function to get queryset , with ACTIVE status and current user / family user

"""
def global_get_queryset(view, model):
    queryset = model.objects.filter(status='ACTIVE')
    if view.request.method not in permissions.SAFE_METHODS:
        return queryset
    user = view.request.QUERY_PARAMS.get('user_id', None)
    if user is not None:
        queryset = queryset.filter(user=user)
    else:
        queryset = queryset.filter(user=view.request.user)
    return queryset    

"""
function to get object's User

"""
def global_get_user_object(request):
        fuser = request.QUERY_PARAMS.get('user_id', None)
        if fuser is not None:
            fuserObj = User.objects.get(pk=fuser)
            return fuserObj
        else:
            return request.user
        return

"""
function used for creating and updating]
"""
def global_create_update(request, view, pk=None, data=None):
    serializerObj = view.get_serializer_class()
    if data is None:
        data = request.DATA
    if pk is not None:
        mObj = view.get_object()
        serializer = serializerObj(mObj, data=data, context={'request': request})
    else:
        serializer = serializerObj(data=data, context={'request': request})
    if serializer.is_valid():
        serializer.object.user = global_get_user_object(request)
        serializer.object.updated_by = request.user
        serializer.save()
        return Response(serializer.data, status=status.HTTP_201_CREATED)
    return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)



@api_view(['GET',])
def api_root(request, format=None):
    return Response({
        'users': reverse('user-list', request=request, format=format),
        'logged-in user': reverse('user-me', request=request, format=format),
        #'user profile crud': reverse('profile-detail', request=request, format=format),
        'signup': reverse('user-signup', request=request, format=format),
        'reminders': reverse('reminder-list', request=request, format=format),
        
        
    })

class SignupView(viewsets.ViewSet):
    model = User
    permission_classes=(permissions.AllowAny,)

    def get_serializer_class(self):
        return UserSerializer
    
    @action(methods=['POST',])
    def user_signup(self, request, format=None):
        return global_create_update(request, self, None, None)

#TODO: Move to mixins for less  code
class UserView(viewsets.ViewSet):
    """
    CRUD for authenticated user or its family member
    * Requires token authentication.
    * CRUD of fields created_at & updated_at are handled by API only.
    * ============
    * GET /users/ - List of users accessible to current logged in user
    * GET /users/me/ - get current logged in user
    * GET /users/<pk>/ - get user with id <pk>
    * POST /users/ - Create new family user for current logged in user
    * PUT /users/<pk> - get user with id <pk>
    * PUT /users/<pk> - Update user with id <pk>
    * PUT /users/<pk>/profile/ - update profile of user with id <pk>
    * DELETE /users/<pk> - Delete users with id <pk>
    * ============
    """
    permission_classes = (permissions.IsAuthenticated,)

    def has_permission_user_view(self, request):
        family_user_id = self.kwargs.get('pk')
        if family_user_id is None:
            return True
        family_user_id = int(family_user_id)
        user_id = int(request.user.id)
        if user_id == family_user_id:
            return True
        has_permission = False
        
        qqueryset = UserGroupSet.objects.filter(user_id__in=[user_id,family_user_id],group_id__in=[user_id,family_user_id])
        for p in qqueryset:
            if(p.user_id != p.group_id):
                has_permission = True
        return has_permission

    def get_object(self, pk):
        try:
            if self.has_permission_user_view(self.request):
                return User.objects.get(pk=pk)
            else:
                return False                
        except User.DoesNotExist:
            return False

    def list(self, request, format=None):
        qqueryset = UserGroupSet.objects.filter(group=request.user,status='ACTIVE')
        users = [p.user for p in qqueryset]
        users = list(set(users))
        
        serializer = UserSerializer(users, many=True, context={'request': request})
        return Response(serializer.data)


    def create(self, request, format=None):
        serializer = UserSerializer(data=request.DATA)
        serializer.object.status = 'ACTIVE'
        if serializer.is_valid():
            serializer.save()
            if not isinstance(request.user, AnonymousUser):
                umap = UserGroupSet(group=request.user.id, user=serializer.data.get('id'),connection_status='ACTIVE');
                umap.save()
            else:
                umap = UserGroupSet(group=serializer.data.get('id'), user=serializer.data.get('id'),connection_status='ACTIVE');
                umap.save()
            return Response(serializer.data, status=status.HTTP_201_CREATED)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    @link()
    def current_user(self, request):
        serializer = UserSerializer(request.user, context={'request': request})
        return Response(serializer.data)

    def retrieve(self, request, pk=None):
        user = self.get_object(pk)
        if user is False:
            return Response( status=status.HTTP_404_NOT_FOUND)
        serializer = UserSerializer(user)
        return Response(serializer.data)

    def update(self, request, pk=None):
        user = self.get_object(pk)
        if user is False:
            return Response(status=status.HTTP_404_NOT_FOUND)
        serializer = UserSerializer(user, data=request.DATA)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    def partial_update(self, request, pk=None):
        pass

    def destroy(self, request, pk=None):
        user = self.get_object(pk)
        if user is False:
            return Response( status=status.HTTP_404_NOT_FOUND)
        user.update(status='DELETED')
        UserGroupSet.objects.filter(group=request.user.id).update(status='DELETED')
        UserGroupSet.objects.filter(user=request.user.id).update(status='DELETED')
        return Response(status=status.HTTP_204_NO_CONTENT)

    @action(methods=['PUT'])
    def update_profile(self, request, pk=None):
        profile = self.get_object(pk).get_profile()
        serializer = UserProfileSerializer(profile, data=request.DATA)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)



class HealthfileViewSet(viewsets.ModelViewSet):

    """
    Manage all healthfiles for a user ( authenticated or family member)
    * Requires token authentication.

    """

    #filter_fields = ('user')
    model = Healthfile
    permission_classes = (permissions.IsAuthenticated,FamilyPermission,)

    def get_queryset(self):
       return global_get_queryset(self, Healthfile)

    def pre_save(self, obj):
        file = self.request.FILES.get('file',None)
        if file is not None:
            obj.file = self.request.FILES['file']
            obj.uploading_file = True
        obj.user = global_get_user_object(self.request)
        obj.updated_by = self.request.user

    #parser_classes = (MultiPartParser,)
    
    def get_serializer_class(self):
        if self.request.method in permissions.SAFE_METHODS:
            return HealthfileSerializer
        else:
            file = self.request.FILES.get('file',None)
            if file is not None:
                return HealthfileUploadSerializer
            else:
                return HealthfileEditSerializer
    
    def destroy(self, request, pk=None):
        m = self.get_object(pk)
        m.update(status='DELETED')
        return Response(status=status.HTTP_204_NO_CONTENT)


class ReminderViewSet(viewsets.ModelViewSet):
    """
    Manage all healthfiles for a user ( authenticated or family member)
    * Requires token authentication.
    * CRUD of fields created_at & updated_at are handled by API only.
    * User field is not to be passed to the API via POST params. It will be ignored if passed.
    * For family user, pass user_id in URL . ie append ?user_id=$user_id
    * For current logged in user, API automatically picks up  the user
    * Allowed methods - GET , POST , PUT , DELETE

    """

    filter_fields = ('user')
    model = Reminder
    permission_classes = (permissions.IsAuthenticated,FamilyPermission,)

    def get_serializer_class(self):
        if self.request.method in permissions.SAFE_METHODS:
            return ReminderListSerializer
        return ReminderSerializer

    """
    def get_object(self):
        return global_get_object(self)
    """
    
    def get_queryset(self):
        return global_get_queryset(self, Reminder)

    def create(self, request, format=None):
        return global_create_update(request, self, None, None)

    def update(self, request, pk=None):
        return global_create_update(request, self, pk, None)

    def destroy(self, request, pk=None):
        m = self.get_object(pk)
        m.update(status='DELETED')
        return Response(status=status.HTTP_204_NO_CONTENT)
"""
class GoalViewSet(ListAPIView):
    def get(self, request, format=None):
        resp = {'count':0 , 'results':[], 'previous':None, 'next':None}
        
        serializer = []
        try:       
            querysetW = UserWeightGoal.objects.get(status='ACTIVE')
            serializerW = UserWeightGoalListSerializer(querysetW, many=False)
            serializer.append(serializerW.data)
            resp['count'] = resp['count'] + 1
        except:
            print 'No weight goal'
        #Temporary adding reminders to list. to test list of different serializers
        try:
            querysetR = Reminder.objects.get(status='ACTIVE')
            serializerR = ReminderSerializer(querysetR, many=False)
            serializer.append(serializerR.data)
            resp['count'] = resp['count'] + 1
        except:
            print 'no reminder'

        resp['results'] = serializer;
        response = Response(resp, status=status.HTTP_200_OK)
        return response



class UserWeightGoalViewSet(viewsets.ModelViewSet):
    #serializer_class = UserWeightGoalSerializer
    filter_fields = ('user')
    model = UserWeightGoal
    permission_classes = (permissions.IsAuthenticated,FamilyPermission,)

    
    def get_serializer_class(self):
        if self.request.method in permissions.SAFE_METHODS:
            return UserWeightGoalListSerializer
        return UserWeightGoalSerializer
    

    def get_object(self):
        return global_get_object(self)

    def get_queryset(self):
        return global_get_queryset(self, UserWeightGoal)

    def pre_save(self, obj):
        obj.updated_by = self.request.user

    @action(methods=['POST'])
    def set_reading(self, request, pk=None):
        if pk is not None:
            try:
                reading = UserWeightReading.objects.get(reading_date=request.DATA['reading_date'])
            except UserWeightReading.DoesNotExist:
                wgoal = UserWeightGoal.objects.get(id=pk)
                reading = UserWeightReading(user_weight_goal=wgoal,updated_by = request.user)
            data = request.DATA.copy()
            data['user_weight_goal'] = '/goals/weight/'+pk+'/'
            serializer = UserWeightReadingSerializer(data=data)
            if serializer.is_valid(): 
                setattr(reading, 'weight', serializer.data['weight'])
                setattr(reading, 'weight_measure', serializer.data['weight_measure'])
                setattr(reading, 'reading_date', serializer.data['reading_date'])
                reading.save()
                return Response({'status': 'reading set'})
            else:
                return Response(serializer.errors,status=status.HTTP_400_BAD_REQUEST)
"""
"""
class HealthfileViewSet(viewsets.ModelViewSet):
    serializer_class = HealthfileSerializer
    filter_fields = ('user')
    model = Healthfile
    permission_classes = (permissions.IsAuthenticated,FamilyPermission,)

    def get_object(self):
        pk = self.kwargs.get('pk')
        if pk is not None:
            try:
                hf = Healthfile.objects.get(pk=pk)
                self.check_object_permissions(self.request, hf)
                return hf
                #FamilyPermission.has_object_permission(FamilyPermission,obj=reminder)
            except Healthfile.DoesNotExist:
                raise Http404

    def get_queryset(self):
        queryset = Healthfile.objects.all()
        if self.request.method not in permissions.SAFE_METHODS:
            return queryset
        user = self.request.QUERY_PARAMS.get('user_id', None)
        if user is not None:
            queryset = queryset.filter(user=user)
        else:
            queryset = queryset.filter(user=self.request.user)
        return queryset
"""

class HealthfileTagViewSet(viewsets.ModelViewSet):
    serializer_class = HealthfileTagSerializer
    filter_fields = ('user')
    model = HealthfileTag
    permission_classes = (permissions.IsAuthenticated,FamilyPermission,)

    def get_queryset(self):
        queryset = HealthfileTag.objects.all()
        user = self.request.QUERY_PARAMS.get('user_id', None)
        if user is not None:
            queryset = queryset.filter(user=user)
        else:
            queryset = queryset.filter(user=self.request.user)
        return queryset

#class ObtainAuthToken(APIView):
#    throttle_classes = ()
#    permission_classes = ()
#    parser_classes = (parsers.FormParser, parsers.MultiPartParser, parsers.JSONParser,)
#    renderer_classes = (renderers.JSONRenderer,) 
#    model = Token

#    def post(self, request):
#        serializer = AuthTokenSerializer(data=request.DATA)
#        if serializer.is_valid():
#            token, created = Token.objects.get_or_create(user=serializer.object['user'])
#            return Response({'token': token.key, 'user_id': serializer.object['user'].id})
#        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

