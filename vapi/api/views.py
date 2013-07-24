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
#from rest_framework.authtoken.serializers import AuthTokenSerializer
from rest_framework import generics, permissions, renderers, parsers, status
from rest_framework.decorators import api_view, link, action
from rest_framework.mixins import DestroyModelMixin
from rest_framework.reverse import reverse
from rest_framework.response import Response
import pprint
from django.db.models import Q
from django.http import Http404
from itertools import chain

#Temporary create code for all users once.
for user in User.objects.all():
    Token.objects.get_or_create(user=user)

class FamilyPermission(permissions.BasePermission):
    #TODO:optimize function
    def has_permission(self, request, view):
        has_permission = False
        family_user_id = request.QUERY_PARAMS.get('user_id', None)
        if family_user_id is None:
            #List page and
            #Current user is the family user
            return True
        
        family_user_id = int(family_user_id)
        user_id = int(request.user.id)
        if user_id == family_user_id:
            #Current user is the family user
            return True
            #Q(connection_status='ACTIVE'),
        qqueryset = UsersMap.objects.filter(
             Q(initiatior_user_id=user_id) , Q(connected_user_id=family_user_id) | 
             Q(connected_user_id=family_user_id) , Q(initiatior_user= user_id)
        )
        users = [p.initiatior_user for p in qqueryset]
        for p in qqueryset:
            users.append(p.connected_user)
        for u in users:
            if u.id == family_user_id:
                has_permission = True
        return has_permission

    def has_object_permission(self, request, view, obj=None):
        if request is None:
            request = self.request

        has_permission = False
        if obj is not None:
           #Object level authentication. check if current object's user is mapped with current login user
           family_user_id =  obj.user.id
        
        family_user_id = int(family_user_id)
        user_id = int(request.user.id)
        if user_id == family_user_id:
            #Current user is the family user
            return True
            #Q(connection_status='ACTIVE'),
        qqueryset = UsersMap.objects.filter(
             Q(initiatior_user_id=user_id) , Q(connected_user_id=family_user_id) | 
             Q(connected_user_id=family_user_id) , Q(initiatior_user= user_id)
        )
        users = [p.initiatior_user for p in qqueryset]
        for p in qqueryset:
            users.append(p.connected_user)
        for u in users:
            if u.id == family_user_id:
                has_permission = True
        return has_permission

def global_get_object(view, model):
    pk = view.kwargs.get('pk')
    if pk is not None:
        try:
            queryset = model.objects.get(pk=pk)
            view.check_object_permissions(view.request, queryset)
            return queryset
        except model.DoesNotExist:
            #return Response(status=status.HTTP_404_NOT_FOUND)
            raise Http404

def global_get_queryset(view, model):
    queryset = model.objects.all()
    if view.request.method not in permissions.SAFE_METHODS:
        return queryset
    user = view.request.QUERY_PARAMS.get('user_id', None)
    if user is not None:
        queryset = queryset.filter(user=user)
    else:
        queryset = queryset.filter(user=view.request.user)
    return queryset    

#TODO: Move to mixins for less  code
class UserView(viewsets.ViewSet):
    def check_permission(self, request , pk):
        #temp for dev
        #if(isinstance(request.user, AnonymousUser)):
        #    return True

        has_permission = False
        pk = int(pk)
        user_id = int(request.user.id)
        if user_id == pk:
            return True
        qqueryset = UsersMap.objects.filter(
            Q(connection_status='ACTIVE'),
            Q(initiatior_user_id=user_id) | Q(connected_user_id=user_id)
        )
        users = [p.initiatior_user for p in qqueryset]
        for p in qqueryset:
            users.append(p.connected_user)
        for u in users:
            if u.id == pk:
                has_permission = True
        return has_permission

    def get_object(self, pk):
        try:
            return User.objects.get(pk=pk)
        except User.DoesNotExist:
            return Response(status=status.HTTP_404_BAD_REQUEST)

    def list(self, request, format=None):
        qqueryset = UsersMap.objects.filter(
            #Q(connection_status='ACTIVE'),
            Q(initiatior_user_id=request.user.id) | Q(connected_user_id=request.user.id)
        )
        #if len(qqueryset) == 0:
        users = [p.initiatior_user for p in qqueryset]
        for p in qqueryset:
            users.append(p.connected_user)
        users.append(request.user)
        users = list(set(users))
        
        serializer = UserSerializer(users, many=True)
        return Response(serializer.data)

    def create(self, request, format=None):
        serializer = UserSerializer(data=request.DATA)
        if serializer.is_valid():
            serializer.save()
            if not isinstance(request.user, AnonymousUser):
                umap = UsersMap(initiatior_user_id=request.user.id, connected_user_id=serializer.data.get('id'),connection_status='ACTIVE');
                umap.save()
            return Response(serializer.data, status=status.HTTP_201_CREATED)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    @link()
    def current_user(self, request):
        serializer = UserSerializer(request.user)
        return Response(serializer.data)

    def retrieve(self, request, pk=None):
        if self.check_permission(request, pk) == False:
            return Response(status=status.HTTP_404_BAD_REQUEST)
        user = self.get_object(pk)
        serializer = UserSerializer(user)
        return Response(serializer.data)

    def update(self, request, pk=None):
        if self.check_permission(request, pk) == False:
            return Response(status=status.HTTP_404_BAD_REQUEST)
        user = self.get_object(pk)
        serializer = UserSerializer(user, data=request.DATA)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    def partial_update(self, request, pk=None):
        pass

    """
    def destroy(self, request, pk=None):
        snippet = self.get_object(pk)
        snippet.delete()
        return Response(status=status.HTTP_204_NO_CONTENT)
    """

    @action(methods=['PUT'])
    def update_profile(self, request, pk=None):
        if self.check_permission(request, pk) == False:
            return Response(status=status.HTTP_404_BAD_REQUEST)
        profile = self.get_object(pk).get_profile()

        serializer = UserProfileSerializer(profile, data=request.DATA)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

class ReminderViewSet(viewsets.ModelViewSet):
    serializer_class = ReminderSerializer
    filter_fields = ('user')
    model = Reminder
    permission_classes = (permissions.IsAuthenticated,FamilyPermission,)

    def get_object(self):
        return global_get_object(self,Reminder)

    def get_queryset(self):
        return global_get_queryset(self, Reminder)

class GoalViewSet(APIView):
    """
    View to list all goals for a authenticated user or its family member.

    * Requires token authentication.
    """
    def get(self, request, format=None):
        """
        Return a list all goals for a authenticated user or its family member.
        """ 
        try:       
            querysetW = UserWeightGoal.objects.get(status='ACTIVE')
            serializerW = UserWeightGoalSerializer(querysetW, many=False)
            serializer = []
            serializer.append(serializerW.data)
            response = Response(serializer, status=status.HTTP_200_OK)
            return response
        except :
            result = {"count": 0, "next": None, "previous":None , "results": []}
            response = Response(result, status=status.HTTP_200_OK)
            return response


class UserWeightGoalViewSet(viewsets.ModelViewSet):
    serializer_class = UserWeightGoalSerializer
    filter_fields = ('user')
    model = UserWeightGoal
    permission_classes = (permissions.IsAuthenticated,FamilyPermission,)

    def get_object(self):
        return global_get_object(self,UserWeightGoal)

    def get_queryset(self):
        queryset = global_get_queryset(self, UserWeightGoal)
        return queryset.filter(status='ACTIVE')

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

"""
class RemindersView(viewsets.ViewSet):

    def get_object(self, pk):
        try:
            return Reminder.objects.get(pk=pk)
        except Reminder.DoesNotExist:
            return Response(status=status.HTTP_404_BAD_REQUEST)

    def list(self, request, format=None):
        qqueryset = UsersMap.objects.filter(
            Q(connection_status='ACTIVE'),
            Q(initiatior_user_id=request.user.id) | Q(connected_user_id=request.user.id)
        )
        users = [p.initiatior_user for p in qqueryset]
        for p in qqueryset:
            users.append(p.connected_user)
        queryset = Reminder.objects.filter(user__in=users)
        serializer = ReminderSerializer(queryset, many=True)
        return Response(serializer.data)

    def create(self, request, format=None):
        reminder = request.DATA
        if not isinstance(request.user, AnonymousUser):
            reminder.updated_by = request.user
        else:
            return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)
        pp = pprint.PrettyPrinter(indent=4)
        pp.pprint(reminder)
        serializer = ReminderSerializer(data=reminder)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data, status=status.HTTP_201_CREATED)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)
"""

"""
class UserDetail(APIView):

    def get_object(self, pk):
        try:
            return User.objects.get(pk=pk)
        except User.DoesNotExist:
            return Response(status=status.HTTP_404_BAD_REQUEST)

    def get(self, request, pk, format=None):
        if self.check_permission(request, pk) == False:
            return Response(status=status.HTTP_404_BAD_REQUEST)
        user = self.get_object(pk)
        serializer = UserSerializer(user)
        return Response(serializer.data)

    def put(self, request, pk, format=None):
        if self.check_permission(request, pk) == False:
            return Response(status=status.HTTP_404_BAD_REQUEST)
        user = self.get_object(pk)
        serializer = UserSerializer(user, data=request.DATA)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    def delete(self, request, pk, format=None):
        snippet = self.get_object(pk)
        snippet.delete()
        return Response(status=status.HTTP_204_NO_CONTENT)

    @link()
    def profile(self, request):
        if self.check_permission(request, pk) == False:
            return Response(status=status.HTTP_404_BAD_REQUEST)
        profile = self.get_object(pk).get_profile()
        serializer = UserProfileSerializer(profile)
        return Response(serializer.data)
"""
