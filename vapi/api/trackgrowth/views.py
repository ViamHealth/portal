from api.views_helper import *
from .models import *
from .serializers import *
from datetime import datetime, timedelta
from rest_framework import viewsets, permissions
from dateutil.relativedelta import relativedelta

class TrackGrowthDataViewSet(ViamModelViewSetNoUser):
    model = TrackGrowthData
    serializer_class = TrackGrowthDataSerializer

class UserTrackGrowthDataViewSet(ViamModelViewSet):
    model = UserTrackGrowthData
    serializer_class = UserTrackGrowthDataSerializer

    def get_object(self, reading_date):
        try:
            o = self.model.objects.get(reading_date=reading_date,user=self.get_user_object(),is_deleted=False)
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
            if days_diff < 0:
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
