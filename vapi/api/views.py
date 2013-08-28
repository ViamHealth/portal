# Create your views here.
#TODOs
#implement delete for others - status inactive
from api.views_helper import *
from django.contrib.auth.models import User, AnonymousUser
from rest_framework import viewsets
from api.models import *
from api.serializers import *
from rest_framework.authtoken.models import Token
from django.core.signals import request_started
from rest_framework.views import APIView
from rest_framework.generics import ListAPIView
#from rest_framework.authtoken.serializers import AuthTokenSerializer
from rest_framework import permissions, renderers, parsers, status, exceptions
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
#from django.core import exceptions
from django.contrib.auth.hashers import *


    
    

#Temporary create code for all users once.
for user in User.objects.all():
    enddate = datetime.today() - timedelta(days=7)
    #pprint.pprint('deleting since the datetime')
    #pprint.pprint(enddate)
    Token.objects.filter(created__lt=enddate).delete()
    #TODO: custom algo for creating token string
    Token.objects.get_or_create(user=user)



@api_view(['GET',])
def api_root(request, format=None):
    return Response({})
    return Response({
        'users': reverse('user-list', request=request, format=format),
        'logged-in user': reverse('user-me', request=request, format=format),
        #'user profile crud': reverse('profile-detail', request=request, format=format),
        'signup': reverse('user-signup', request=request, format=format),
        'reminders': reverse('reminder-list', request=request, format=format),
        #'bmi-profile': reverse('userbmiprofile-list', request=request, format=format),
        'healthfiles': reverse('healthfile-list', request=request, format=format),
        'weight-goals': reverse('userweightgoal-list', request=request, format=format),
        'weight-readings': reverse('userweightreading-list', request=request, format=format),
        'blood-pressure-goals': reverse('userbloodpressuregoal-list', request=request, format=format),
        'blood-pressure-readings': reverse('userbloodpressurereading-list', request=request, format=format),
        'cholesterol-goals': reverse('usercholesterolgoal-list', request=request, format=format),
        'cholesterol-readings': reverse('usercholesterolreading-list', request=request, format=format),
        #'food-detail': reverse('food-details-detail',request=request, format=format),
        
    })

class SignupView(viewsets.ViewSet):
    model = User
    permission_classes=(permissions.AllowAny,)

    @action(methods=['POST',])
    def user_signup(self, request, format=None):
        serializer = UserSignupSerializer(data=request.DATA, context={'request': request})
        if serializer.is_valid():
            serializer.object.email = serializer.object.username
            serializer.object.password = make_password(serializer.object.password)
            serializer.save()
            user = User.objects.get(pk=serializer.object.id)
            pserializer = UserSerializer(user, context={'request': request})
            UserBmiProfile.objects.get_or_create(user=user,defaults={'updated_by': user})
            return Response(pserializer.data, status=status.HTTP_201_CREATED)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

#TODO: Move to mixins for less  code
class UserView(viewsets.ViewSet):
    """
    CRUD for authenticated user or its family member
    * Requires token authentication.
    * CRUD of fields created_at & updated_at are handled by API only.
    * Updation of username and email not allowed
    * ============
    * GET /users/ - List of users accessible to current logged in user
    * GET /users/me/ - get current logged in user
    * GET /users/<pk>/ - get user with id <pk>
    * POST /users/ - Create new family user for current logged in user
    * PUT /users/<pk>/ - Update user with id <pk>
    * PUT /users/<pk>/profile/ - update profile of user with id <pk>
    * PUT /users/<pk>/profile-picture/ - upload profile picture of user with id <pk> . Require multpart/form-data
    * GET /users/<pk>/bmi-profile/ - gets bmi profile of user
    * PUT /users/<pk>/bmi-profile/ - updates bmi profile of user
    * POST /users/<pk>/change-password/ - change password of user with id <pk>
    * DELETE /users/<pk>/ - Delete users with id <pk>
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
        qqueryset = UserGroupSet.objects.filter(user_id__in=[user_id,family_user_id],group_id__in=[user_id,family_user_id],status='ACTIVE')
        for p in qqueryset:
            if(p.user_id != p.group_id):
                has_permission = True
        if has_permission:
            return True
        else:
            raise exceptions.PermissionDenied

    def get_object(self, pk):
        try:
            self.has_permission_user_view(self.request)
            user = User.objects.get(pk=pk,is_active=True)
            return user
        except User.DoesNotExist:
            raise Http404

    def list(self, request, format=None):
        qqueryset = UserGroupSet.objects.filter(group=request.user,status='ACTIVE')
        users = [p.user for p in qqueryset]
        users = list(set(users))
        serializer = UserSerializer(users, many=True, context={'request': request})
        return Response(serializer.data)

    def create(self, request, format=None):
        serializer = UserCreateSerializer(data=request.DATA, context={'request': request})
        if serializer.is_valid():
            serializer.save()
            umap = UserGroupSet(group=request.user, user=User.objects.get(pk=serializer.data.get('id')),status='ACTIVE',updated_by=request.user);
            umap.save()
            #TODO:check for adding updated_by
            user=User.objects.get(pk=serializer.data.get('id'))
            uprofile = UserProfile(user=user)
            uprofile.save()
            UserBmiProfile.objects.get_or_create(user=user,defaults={'updated_by': user})
            pserializer = UserSerializer(user, data=serializer.object, context={'request': request})
            return Response(pserializer.data, status=status.HTTP_201_CREATED)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    def retrieve(self, request, pk=None):
        user = self.get_object(pk)
        serializer = UserSerializer(user, context={'request': request})
        return Response(serializer.data)

    def update(self, request, pk=None):
        user = self.get_object(pk)
        serializer = UserEditSerializer(user, data=request.DATA, context={'request': request})
        if serializer.is_valid():
            serializer.save()
            pserializer = UserSerializer(user, data=serializer.object, context={'request': request})
            return Response(pserializer.data)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    @link()
    def current_user(self, request):
        serializer = UserSerializer(request.user, context={'request': request})
        return Response(serializer.data)

    def destroy(self, request, pk=None):
        UserGroupSet.objects.filter(group=pk,user=request.user.id,status='ACTIVE').update(status='DELETED',updated_by=request.user,updated_at=datetime.now())
        UserGroupSet.objects.filter(user=pk,group=request.user.id,status='ACTIVE').update(status='DELETED',updated_by=request.user,updated_at=datetime.now())
        return Response(status=status.HTTP_204_NO_CONTENT)

    @action(methods=['POST'])
    def change_password(self, request, pk):
        user = self.get_object(pk)
        serializer = UserPasswordSerializer(user, data=request.DATA)
        if serializer.is_valid():
            serializer.object.password = make_password(serializer.object.password)
            serializer.save()
            return Response(status=status.HTTP_200_OK)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    @action(methods=['PUT'])
    def update_profile(self, request, pk=None):
        user = self.get_object(pk)
        profile = user.get_profile()
        serializer = UserProfileSerializer(profile, data=request.DATA)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    @link()
    def retrieve_bmi_profile(self, request, pk):
        user = self.get_object(pk)
        bmi_profile = UserBmiProfile.objects.get_or_create(user=user,defaults={'updated_by': user})[0]
        serializer = UserBmiProfileSerializer(bmi_profile, context={'request': request})
        return Response(serializer.data)

    @action(methods=['PUT'])
    def update_bmi_profile(self, request, pk=None):
        user = self.get_object(pk)
        bmi_profile = UserBmiProfile.objects.get_or_create(user=user,defaults={'updated_by': user})[0]
        serializer = UserBmiProfileSerializer(bmi_profile, data=request.DATA, context={'request': request})
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    @action(methods=['PUT'])
    def update_profile_pic(self, request, pk=None):
        user = self.get_object(pk)
        data = request.DATA
        profile = user.get_profile()
        data.profile_picture = self.request.FILES['profile_picture']
        serializer = UserProfilePicSerializer(profile, data=data)
        if serializer.is_valid():
            serializer.object.profile_picture = self.request.FILES['profile_picture']
            serializer.save()
            pserializer = UserProfileSerializer(profile, data=serializer.object)
            return Response(pserializer.data)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)


class ReminderViewSet(ViamModelViewSet):
    """
    Manage all healthfiles for a user ( authenticated or family member)
    * Requires token authentication.
    * CRUD of fields created_at & updated_at are handled by API only.
    * User field is not to be passed to the API via POST params. It will be ignored if passed.
    * For family user, pass user in URL . ie append ?user=$user_id
    * For current logged in user, API automatically picks up  the user
    * Allowed methods - GET , POST , PUT , DELETE

    """

    #filter_fields = ('user')
    model = Reminder
    serializer_class = ReminderSerializer


class HealthfileViewSet(ViamModelViewSet):

    """
    Manage all healthfiles for a user ( authenticated or family member)
    * Requires token authentication.
    * PUT /healthfiles/<pk>/ - upload file . Require multipart/form-data
    * PUT /healthfiles/<pk>/ - updates description of halthfile with id <pk> . Without multpart/form-data
    * DELETE /users/<pk> - Delete healthfile with id <pk>

    """

    #filter_fields = ('user')
    model = Healthfile

    def pre_save(self, obj):
        file = self.request.FILES.get('file',None)
        if file is not None:
            obj.file = self.request.FILES['file']
            obj.uploading_file = True
        obj.user = self.get_user_object()
        obj.updated_by = self.request.user
    
    def get_serializer_class(self):
        if self.request.method in permissions.SAFE_METHODS:
            return HealthfileSerializer
        else:
            file = self.request.FILES.get('file',None)
            if file is not None:
                return HealthfileUploadSerializer
            else:
                return HealthfileEditSerializer

    def update(self, request, pk=None):
        tags_sent = False
        m = self.get_object(pk)
        serializer = self.get_serializer(m, data=request.DATA)
        if serializer.is_valid():
            serializer.save()
            #TODO: improve tags creation/deletion
            data = request.DATA.copy()
            tags = []
            tag_objs_up = []
            tag_objs_create = []
            for k,v in data.iteritems():
                if k[:5] == 'tags[':
                    tags.append(v)
                    tags_sent = True

            if tags_sent:
                id_arr = []
                for v in tags:
                    try:
                        t = HealthfileTag.objects.get(healthfile=m,tag=v)
                    except HealthfileTag.DoesNotExist:
                        t = HealthfileTag(tag=v,healthfile=m)
                    tdata = {}
                    tdata['tag'] = str(t.tag)
                    tdata['healthfile'] = m.id
                    if t.id is not None:
                        tdata['id'] = t.id
                        tag_objs_up.append(tdata)
                        id_arr.append(t.id)
                    else:
                        tag_objs_create.append(tdata)

                qt = HealthfileTag.objects.filter(id__in=id_arr)

                HealthfileTag.objects.filter(healthfile=m).exclude(id__in=id_arr).delete()

                tagserializer = HealthfileTagAddSerializer(data=tag_objs_create , many=True)
                if tagserializer.is_valid():
                    tagserializer.save()
                else:
                    return Response(tagserializer.errors, status=status.HTTP_400_BAD_REQUEST)
            
            return Response(serializer.data)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)
"""
class HealthfileTagViewSet(viewsets.ModelViewSet):
    filter_fields = ('healthfile',)
    model = HealthfileTag
    serializer_class = HealthfileTagSerializer
    
"""
class UserWeightGoalViewSet(ViamModelViewSet):
    """
    Manage all healthfiles for a user ( authenticated or family member)
    * Requires token authentication.
    * CRUD of fields created_at & updated_at are handled by API only.
    * User field is not to be passed to the API via POST params. It will be ignored if passed.
    * For family user, pass user in URL . ie append ?user=$user_id
    * For current logged in user, API automatically picks up  the user
    * Allowed methods - GET , POST , PUT , DELETE

    """

    #filter_fields = ('user')
    model = UserWeightGoal
    serializer_class = UserWeightGoalSerializer

    @action(methods=['POST'])
    def set_reading(self, request, pk):
        try:
            reading = UserWeightReading.objects.get(reading_date=request.DATA['reading_date'])
            reading.weight = int(request.DATA['weight'])
            reading.weight_measure = request.DATA['weight_measure']
            reading.updated_by = request.user
            serializer = UserWeightReadingSerializer(reading)
            return Response(serializer.data)    
        except UserWeightReading.DoesNotExist:
            try:
                wgoal = UserWeightGoal.objects.get(id=pk)
                reading = UserWeightReading(user_weight_goal=wgoal,weight=int(request.DATA['weight']),weight_measure=request.DATA['weight_measure'],reading_date=request.DATA['reading_date'],updated_by=request.user)
                reading.save()
                serializer = UserWeightReadingSerializer(reading)
                return Response(serializer.data,context={'request': request})    
            except:
                return Response(status=status.HTTP_400_BAD_REQUEST)
            
        except:
            return Response(status=status.HTTP_400_BAD_REQUEST)

class UserWeightReadingView(viewsets.ModelViewSet):
    """
    Manage all healthfiles for a user ( authenticated or family member)
    * Requires token authentication.
    * Get readings for a weight goal - http://127.0.0.1:8080/healthfiletags/?user_weight_goal=1
    """
    filter_fields = ('user_weight_goal',)
    model = UserWeightReading
    serializer_class = UserWeightReadingSerializer

    #def get_queryset(self):
    #    if self.request.GET.get('user_weight_goal',None) is None:
    #        raise exceptions.MethodNotAllowed('get', detail=None)
    #    else:
    #        queryset = super(UserWeightReadingView, self).get_queryset()
    """
    def list_new(self, request, format=None):
        if request.GET.get('user_weight_goal',None) is None:
            raise exceptions.MethodNotAllowed('get', detail=None)
        else:
            return super(UserWeightReadingView, self).list(request, format)
            #pprint.pprint(request.GET['user_weight_goal'])
            #queryset = super(UserWeightReadingView, self).get_queryset()
            #queryset = super(UserWeightReadingView, self).filter_queryset(queryset)
            #pprint.pprint(queryset)
            #serializer = UserWeightReadingSerializer(queryset, many=True, context={'request': request})
            #return Response(serializer.data)
    """
class UserBloodPressureGoalViewSet(ViamModelViewSet):
    """
    Manage all healthfiles for a user ( authenticated or family member)
    * Requires token authentication.
    * CRUD of fields created_at & updated_at are handled by API only.
    * User field is not to be passed to the API via POST params. It will be ignored if passed.
    * For family user, pass user in URL . ie append ?user=$user_id
    * For current logged in user, API automatically picks up  the user
    * Allowed methods - GET , POST , PUT , DELETE
    * custom actions :- 
    * POST set_reading - set a new reading / update old reading. Updation is based on reading_date params
    """

    #filter_fields = ('user')
    model = UserBloodPressureGoal
    serializer_class = UserBloodPressureGoalSerializer

    @action(methods=['POST'])
    def set_reading(self, request, pk):
        try:
            reading = UserBloodPressureReading.objects.get(reading_date=request.DATA['reading_date'])
            reading.systolic_pressure = int(request.DATA['systolic_pressure'])
            reading.diastolic_pressure = request.DATA['diastolic_pressure']
            reading.pulse_rate = request.DATA['pulse_rate']
            reading.updated_by = request.user
            serializer = UserBloodPressureReadingSerializer(reading)
            return Response(serializer.data)    
        except UserBloodPressureReading.DoesNotExist:
            try:
                wgoal = UserBloodPressureGoal.objects.get(id=pk)
                reading = UserBloodPressureReading(user_blood_pressure_goal=wgoal,systolic_pressure=int(request.DATA['systolic_pressure']),diastolic_pressure=request.DATA['diastolic_pressure'],pulse_rate=request.DATA['pulse_rate'],reading_date=request.DATA['reading_date'],updated_by=request.user)
                reading.save()
                serializer = UserBloodPressureReadingSerializer(reading)
                return Response(serializer.data)    
            except:
                return Response(status=status.HTTP_400_BAD_REQUEST)
            
        except:
            return Response(status=status.HTTP_400_BAD_REQUEST)

class UserBloodPressureReadingView(viewsets.ModelViewSet):
    """
    Manage all healthfiles for a user ( authenticated or family member)
    * Requires token authentication.
    * Get readings for a weight goal - http://127.0.0.1:8080/healthfiletags/?user_weight_goal=1
    """
    filter_fields = ('user_blood_pressure_goal',)
    model = UserBloodPressureReading
    serializer_class = UserBloodPressureReadingSerializer


class UserCholesterolGoalViewSet(ViamModelViewSet):
    """
    Manage all healthfiles for a user ( authenticated or family member)
    * Requires token authentication.
    * CRUD of fields created_at & updated_at are handled by API only.
    * User field is not to be passed to the API via POST params. It will be ignored if passed.
    * For family user, pass user in URL . ie append ?user=$user_id
    * For current logged in user, API automatically picks up  the user
    * Allowed methods - GET , POST , PUT , DELETE
    * custom actions :- 
    * POST set_reading - set a new reading / update old reading. Updation is based on reading_date params
    """

    #filter_fields = ('user')
    model = UserCholesterolGoal
    serializer_class = UserCholesterolGoalSerializer

    @action(methods=['POST'])
    def set_reading(self, request, pk):
        try:
            reading = UserCholesterolReading.objects.get(reading_date=request.DATA['reading_date'])
            reading.ldl = int(request.DATA['ldl'])
            reading.hdl = request.DATA['hdl']
            reading.triglycerides = request.DATA['triglycerides']
            reading.total_cholesterol = request.DATA['total_cholesterol']
            reading.updated_by = request.user
            serializer = UserCholesterolReadingSerializer(reading)
            return Response(serializer.data)    
        except UserCholesterolReading.DoesNotExist:
            try:
                wgoal = UserCholesterolGoal.objects.get(id=pk)
                reading = UserCholesterolReading(user_cholesterol_goal=wgoal,ldl=int(request.DATA['ldl']),hdl=request.DATA['hdl'],triglycerides=request.DATA['triglycerides'],total_cholesterol=request.DATA['total_cholesterol'],reading_date=request.DATA['reading_date'],updated_by=request.user)
                reading.save()
                serializer = UserCholesterolReadingSerializer(reading)
                return Response(serializer.data)    
            except:
                return Response(status=status.HTTP_400_BAD_REQUEST)
            
        except:
            return Response(status=status.HTTP_400_BAD_REQUEST)

class UserCholesterolReadingView(viewsets.ModelViewSet):
    """
    Manage all healthfiles for a user ( authenticated or family member)
    * Requires token authentication.
    * Get readings for a weight goal - http://127.0.0.1:8080/healthfiletags/?user_weight_goal=1
    """
    filter_fields = ('user_cholesterol_goal',)
    model = UserCholesterolReading
    serializer_class = UserCholesterolReadingSerializer


class FoodItemViewSet(viewsets.ModelViewSet):
    model = FoodItem
    serializer_class = FoodItemSerializer
    permission_classes = (permissions.IsAuthenticated,)

    #Over riding viewset functions
    def get_queryset(self):
        queryset = self.model.objects.filter(status='ACTIVE')
        return queryset
        
    def get_object(self, pk=None):
        try:
            return self.model.objects.get(pk=pk,status='ACTIVE')
        except self.model.DoesNotExist:
            raise Http404

    def pre_save(self, obj):
        obj.updated_by = self.request.user

    def retrieve(self, request, pk=None):
        m = self.get_object(pk)
        serializer = self.get_serializer(m)
        return Response(serializer.data)

    @link()
    def search(self, request, search_string=None):
        if search_string is not None:
            queryset = self.model.objects.filter(name__icontains=search_string)
            serializer = self.get_serializer(queryset, many=True)
            resp = {'count':len(serializer.data) , 'results':serializer.data, 'previous':None, 'next':None}
            return Response(resp, status=status.HTTP_200_OK)

class DietTrackerViewSet(ViamModelViewSet):
    model = DietTracker
    serializer_class = DietTrackerSerializer
    filter_fields = ('meal_type','user',)


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

