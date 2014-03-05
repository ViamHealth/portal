from api.goals.models import *
from api.goals.serializers import *
from django.contrib.auth.models import User, AnonymousUser
from rest_framework import viewsets, permissions
from rest_framework.decorators import api_view, authentication_classes, permission_classes
from rest_framework.response import Response
from django.http import  HttpResponse
from api.views_helper import *
from api.users.serializers import *
from django.db.models import Q


def list_users(user):
	qqueryset = UserGroupSet.objects.filter(Q(group=user)|Q(user=user)).filter(status='ACTIVE')
	sync_ts = None
	f = '%Y-%m-%d %H:%M:%S'

	users = []
	users = list(set(users))
	#users = [p.user for p in qqueryset]
	for p in qqueryset:
	    if p.group.id != user.id:
	        if sync_ts is not None:
	            ut = p.group.updated_at.replace(tzinfo=None)
	            but = p.group.bmi_profile.updated_at.replace(tzinfo=None)
	            st = datetime.datetime.strptime(sync_ts, f)
	            if st < ut or st < but:
	                p.group.is_deleted = p.is_deleted
	                users = [p.group] + users
	        else:
	            if p.is_deleted == False:
	                p.group.is_deleted = p.is_deleted
	                users = [p.group] + users
	    if p.user.id != user.id:
	        if sync_ts is not None:
	            ut = p.user.updated_at.replace(tzinfo=None)
	            but = p.user.bmi_profile.updated_at.replace(tzinfo=None)
	            st = datetime.datetime.strptime(sync_ts, f)
	            if st < ut or st < but:
	                p.user.is_deleted = p.is_deleted
	                users = [p.user] + users
	        else:
	            if p.is_deleted == False:
	                p.user.is_deleted = p.is_deleted
	                users = [p.user] + users

	self_user = user


	if sync_ts is not None:
	    ut = self_user.profile.updated_at.replace(tzinfo=None)
	    but = self_user.bmi_profile.updated_at.replace(tzinfo=None)
	    st = datetime.datetime.strptime(sync_ts, f)
	    if st < ut or st < but:
	        self_user.is_deleted = False
	        users = [self_user] + users
	else:
	    self_user.is_deleted = False
	    users = [self_user] + users



	#sync_ts = request.QUERY_PARAMS.get('last_sync', None)
	if sync_ts is None:
	    serializer = UserListSerializer(users, fields=('id',  'username', 'email', 'first_name', 'last_name', 'profile', 'bmi_profile'), many=True)
	else:
	    serializer = UserListSerializer(users,fields=('id',  'username', 'email', 'first_name', 'last_name', 'profile', 'bmi_profile','is_deleted'), many=True)    
	return serializer.data


@api_view(['GET',])
@permission_classes((permissions.IsAdminUser,))
def watchdog_data(request):
    glucose = None
    weight = None
    blood_pressure = None
    cholesterol = None
    family = None
    result = {}

    fuser = request.GET.get('user',None)
    if fuser is None:
    	result["detail"] = "Please provide a user id"
        return JSONResponse(result, status=404)
    try:
    	user = User.objects.get(pk=fuser)
    except User.DoesNotExist:
        result["detail"] = "Please provide a valid user id"
        return JSONResponse(result, status=404)

    try:
    	family = list_users(user)
    except UserGroupSet.DoesNotExist:
    	pass
    try:
        glucose = UserGlucoseGoal.objects.get(user=user)
    except UserGlucoseGoal.DoesNotExist:
        pass
    try:
        weight = UserWeightGoal.objects.get(user=user)
    except UserWeightGoal.DoesNotExist:
        pass
    try:
        blood_pressure = UserBloodPressureGoal.objects.get(user=user)
    except UserBloodPressureGoal.DoesNotExist:
        pass
    try:
        cholesterol = UserCholesterolGoal.objects.get(user=user)
    except UserCholesterolGoal.DoesNotExist:
        pass
    

    if request.method == 'GET':
    	if family is not None:
    		result['family'] = family
        if weight is not None:
            serializer = UserWeightGoalSerializer(weight)
            result['weight-goals'] = serializer.data
            serializer = None
        if glucose is not None:
            serializer = UserGlucoseGoalSerializer(glucose)
            result['glucose-goals'] = serializer.data
            serializer = None
        if cholesterol is not None:
            serializer = UserCholesterolGoalSerializer(cholesterol)
            result['cholesterol-goals'] = serializer.data
            serializer = None
        if blood_pressure is not None:
            serializer = UserBloodPressureGoalSerializer(blood_pressure)
            result['blood-pressure-goals'] = serializer.data
            serializer = None

        return JSONResponse(result)
    else:
        return HttpResponse(status=404)
