from api.views_helper import *
from api.email_helper import *

from django.contrib.auth.models import User

from .models import *
from .serializers import *


from rest_framework import permissions, status
from rest_framework.response import Response
from django.http import  HttpResponse


class WeightReadingViewSet(GoalReadingsViewSet):
    model = UserWeightReading    
    def get_serializer_class(self):
        if self.request.method != 'post':
            return UserWeightReadingSerializer
        else:
            return UserWeightReadingCreateSerializer

class UserWeightGoalViewSet(ViamModelViewSetClean):
    model = UserWeightGoal
    serializer_class = UserWeightGoalSerializer

    def create(self, request, format=None):
        try:
            UserWeightGoal.objects.get(user=self.get_user_object())
            return Response(status=status.HTTP_400_BAD_REQUEST)
        except UserWeightGoal.DoesNotExist:
            return super(UserWeightGoalViewSet, self).create(request,format)


class BloodPressureReadingViewSet(GoalReadingsViewSet):
    model = UserBloodPressureReading
    def get_serializer_class(self):
        if self.request.method != 'post':
            return UserBloodPressureReadingSerializer
        else:
            return UserBloodPressureReadingCreateSerializer

class UserBloodPressureGoalViewSet(ViamModelViewSetClean):
    model = UserBloodPressureGoal
    serializer_class = UserBloodPressureGoalSerializer

    def create(self, request, format=None):
        try:
            UserBloodPressureGoal.objects.get(user=self.get_user_object())
            return Response(status=status.HTTP_400_BAD_REQUEST)
        except UserBloodPressureGoal.DoesNotExist:
            return super(UserBloodPressureGoalViewSet, self).create(request,format)

class CholesterolReadingViewSet(GoalReadingsViewSet):
    model = UserCholesterolReading
    def get_serializer_class(self):
        if self.request.method != 'post':
            return UserCholesterolReadingSerializer
        else:
            return UserCholesterolReadingCreateSerializer

class UserCholesterolGoalViewSet(ViamModelViewSetClean):
    
    #filter_fields = ('user')
    model = UserCholesterolGoal
    serializer_class = UserCholesterolGoalSerializer

    def create(self, request, format=None):
        try:
            UserCholesterolGoal.objects.get(user=self.get_user_object())
            return Response(status=status.HTTP_400_BAD_REQUEST)
        except UserCholesterolGoal.DoesNotExist:
            return super(UserCholesterolGoalViewSet, self).create(request,format)

class GlucoseReadingViewSet(GoalReadingsViewSet):
    model = UserGlucoseReading
    def get_serializer_class(self):
        if self.request.method != 'post':
            return UserGlucoseReadingSerializer
        else:
            return UserGlucoseReadingCreateSerializer

class UserGlucoseGoalViewSet(ViamModelViewSetClean):

    #filter_fields = ('user')
    model = UserGlucoseGoal
    serializer_class = UserGlucoseGoalSerializer

    def create(self, request, format=None):
        try:
            UserGlucoseGoal.objects.get(user=self.get_user_object())
            return Response(status=status.HTTP_400_BAD_REQUEST)
        except UserGlucoseGoal.DoesNotExist:
            return super(UserGlucoseGoalViewSet, self).create(request,format)



def all_goals(request):
    glucose = None
    weight = None
    blood_pressure = None
    cholesterol = None
    result = {}

    has_permission = False

    fuser = request.GET.get('user',None)
    if fuser is not None:
        user_id = int(request.user.id)
        qqueryset = UserGroupSet.objects.filter(user_id__in=[user_id,int(fuser)],group_id__in=[user_id,int(fuser)],status='ACTIVE')
        for p in qqueryset:
            if(p.user_id != p.group_id):
                has_permission = True
        user = User.objects.get(pk=fuser)
    else:
        user = request.user
        has_permission = True

    if has_permission == False:
        result["detail"] = "You do not have permission to perform this action."
        return JSONResponse(result, status=403)

        
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
