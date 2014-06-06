from api.views_helper import *
from .models import *
from .serializers import *
from datetime import datetime, timedelta
from rest_framework import viewsets, permissions
from dateutil.relativedelta import relativedelta
from api.users.models import UserProfile
from rest_framework.views import APIView
from rest_framework.response import Response
from rest_framework import authentication, permissions, status
from django.http import Http404

class TrackGrowthDataAdvancedViewSet(APIView):
    permission_classes = (permissions.IsAuthenticated,)

    def get(self, request, format=None):
        gender = self.request.QUERY_PARAMS.get('gender', None)
        age = self.request.QUERY_PARAMS.get('age', None)

        if gender is None or age is None:
            result = {}
            result['non_field_error'] = 'Provide a  gender and age'
            return Response(result,status=status.HTTP_400_BAD_REQUEST)
        
        result = {}
        tg1 = TrackGrowthAdvancedData.objects.filter(gender=gender,age__gte=age,is_deleted=False).order_by('age')
        if tg1:
            tg = tg1[0]
            if tg:
                result['gender'] = tg.gender
                result['age'] = tg.age
                result['height_3n'] = tg.height_3n
                result['weight_3n'] = tg.weight_3n
                result['height_2n'] = tg.height_2n
                result['weight_2n'] = tg.weight_2n
                result['height_1n'] = tg.height_1n
                result['weight_1n'] = tg.weight_1n
                result['height_0'] = tg.height_0
                result['weight_0'] = tg.weight_0
                result['height_1'] = tg.height_1
                result['weight_1'] = tg.weight_1
                result['height_2'] = tg.height_2
                result['weight_2'] = tg.weight_2
                result['height_3'] = tg.height_3
                result['weight_3'] = tg.weight_3

                return JSONResponse(result)
            else:
                raise Http404
        else:
            #except TrackGrowthAdvancedData.DoesNotExist:
            raise Http404






class TrackGrowthDataViewSet(ViamModelViewSetNoUser):
    model = TrackGrowthData
    serializer_class = TrackGrowthDataSerializer

class UserTrackGrowthDataViewSet(ViamModelViewSet):
    model = UserTrackGrowthData
    serializer_class = UserTrackGrowthDataSerializer

    def get_object(self, pk):
        try:
            o = self.model.objects.get(entry_date=pk,user=self.get_user_object(),is_deleted=False)
            #self.check_object_permissions(self.request, o)
            return o
        except self.model.DoesNotExist:
            raise Http404
    
    def list(self, request):
    	user_id = request.QUERY_PARAMS.get('user', None)
        if user_id is None:
            user_id = request.user.id
        u = UserProfile.objects.get(user=user_id)
        gender = u.gender
        date_of_birth = u.date_of_birth
        if date_of_birth is None or gender is None:
            return {}
        
        entry = []
        user_entry = []
        tgd = TrackGrowthData.objects.filter(is_deleted=False,gender=gender).order_by('age')
        for tg in tgd:
            a = {}
            #if tg.age == 0:
            #	a['date'] = date_of_birth
            #else:
            #	a['date'] = date_of_birth + relativedelta( days = tg.age ) 
            a['id'] = tg.id
            a['age'] = tg.age
            a['height'] = tg.height
            a['weight'] = tg.weight
            a['label'] = tg.label
            a['age'] = tg.age
            entry.append(a)


        utd = UserTrackGrowthData.objects.filter(is_deleted=False,user=user_id).order_by('entry_date')
        for ut in utd:
            days_diff = abs((ut.entry_date - date_of_birth).days)
            if days_diff < 0 or days_diff > 1825 :
            	continue
            
            #date = date_of_birth+ timedelta(days=days_diff)
            
            a = {}
            a['id'] = ut.id
            #a['date'] = ut.entry_date
            a['age'] = days_diff
            a['entry_date'] = ut.entry_date
            if ut.height is not None:
            	a['height'] = ut.height
            if ut.weight is not None:
            	a['weight'] = ut.weight
            a['user'] = ut.user.id
            user_entry.append(a)

        result = {}
        result['track_growth'] = []
        result['user_track_growth'] = []
        for e in entry:
        	result['track_growth'].append(e)
        
       	for e in user_entry:
        	result['user_track_growth'].append(e)
        	
        return JSONResponse(result)


    def create(self, request, format=None):
    	pass

    def retrieve(self, request, pk):
        m = self.get_object(pk)
        serializer = self.get_serializer(m)
        return Response(serializer.data)

    def update(self, request, pk):
    	try:
    		user_id = request.DATA.get('user')
    		try:
    			user = User.objects.get(pk=user_id)
    		except User.DoesNotExist:
    			return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

        	m = self.model.objects.get(entry_date=pk,user=user,is_deleted=False)

        	serializer = self.get_serializer(m, data=request.DATA)
	        if serializer.is_valid():
	            serializer.save()
	            return Response(serializer.data)
	        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)
        except self.model.DoesNotExist:
        	serializer = self.get_serializer(data=request.DATA,)
	        if serializer.is_valid():
	            serializer.object.user = user
	            serializer.object.updated_by = self.request.user
	            serializer.save()
	            return Response(serializer.data, status=status.HTTP_201_CREATED)
	        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)
        
