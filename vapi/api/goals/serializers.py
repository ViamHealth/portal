from django.contrib.auth.models import User
from .models import *
from api.users.models import *
from rest_framework import serializers
from django.core.exceptions import ValidationError
from api.serializers_helper import *

def goal_readings_validator(obj, attrs, source):
    if obj.object is not None:
        if obj.object.reading_date != attrs[source]:
            raise serializers.ValidationError('reading_date cannot be changed')
        else:
            return attrs
    else:
        fuser = obj.context['request'].QUERY_PARAMS.get('user', None)
        if fuser is None:
            user = obj.context['request'].user
        else:
            user = User.objects.get(pk=fuser)
        try:
            obj.Meta.model.objects.get(reading_date=attrs['reading_date'],user=user)
            raise serializers.ValidationError('reading for this date already exists')
        except obj.Meta.model.DoesNotExist:
            return attrs
class UserWeightReadingCreateSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    class Meta:
        model = UserWeightReading
        fields = ('id','user','weight' ,'reading_date','comment')

class UserWeightReadingSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = UserWeightReading
        fields = ('weight' ,'reading_date','comment')

    def validate_reading_date(self, attrs, source):
        return goal_readings_validator(self, attrs, source)

class UserWeightGoalSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    healthy_range = serializers.SerializerMethodField('get_healthy_range')
    readings = serializers.SerializerMethodField('get_readings')
    def get_readings(self, obj=None):
        if obj is not None:
            user = obj.user
            readings = UserWeightReading.objects.filter(user=user,reading_date__gte=obj.created_at)
            serializer = UserWeightReadingSerializer(readings, many=True)
            return serializer.data
    #readings = UserWeightReadingSerializer(many=True)
    class Meta:
        model = UserWeightGoal
        fields = ('id', 'user','readings','weight','healthy_range' ,'target_date','interval_num','interval_unit',)

    def get_healthy_range(self, obj=None):
        if obj is not None:
            u = UserBmiProfile.objects.get_or_create(user=obj.user,defaults={'updated_by': obj.user})[0]
            
            if u.height is None:
                    return None
            else:
                    height = u.height
            max_bmi = 24.9
            min_bmi = 18.6
            min_weight = min_bmi * ( float(height) / 100 ) * ( float(height) / 100 )
            max_weight = max_bmi * ( float(height) / 100 ) * ( float(height) / 100 )
            a = {}
            a['weight'] = {}
            a['weight']['max'] = max_weight
            #a['weight']['max_measure'] = 'METRIC'
            a['weight']['min'] = min_weight
            #a['weight']['min_measure'] = 'METRIC'
            return a

    

###########

class UserBloodPressureReadingCreateSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    class Meta:
        model = UserBloodPressureReading
        fields = ('id','user','systolic_pressure','diastolic_pressure', 'pulse_rate' ,'reading_date','comment')

class UserBloodPressureReadingSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = UserBloodPressureReading
        fields = ('systolic_pressure','diastolic_pressure', 'pulse_rate' ,'reading_date','comment')
    def validate_reading_date(self, attrs, source):
        return goal_readings_validator(self, attrs, source)

class UserBloodPressureGoalSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    healthy_range = serializers.SerializerMethodField('get_healthy_range')
    readings = serializers.SerializerMethodField('get_readings')
    def get_readings(self, obj=None):
        if obj is not None:
            user = obj.user
            readings = UserBloodPressureReading.objects.filter(user=user,reading_date__gte=obj.created_at)
            serializer = UserBloodPressureReadingSerializer(readings, many=True)
            return serializer.data

    class Meta:
        model = UserBloodPressureGoal
        fields = ('id', 'user','readings','systolic_pressure','diastolic_pressure', 'pulse_rate' ,'healthy_range','target_date','interval_num','interval_unit',)

    def validate(self, attrs):
        return goals_date_validate(self,attrs);

    def get_healthy_range(self, obj=None):
        max_systolic_pressure  = 120;
        min_systolic_pressure  = 90;
        max_diastolic_pressure  = 80;
        min_diastolic_pressure  = 60;
        a = {}
        a['systolic_pressure'] ={}
        a['systolic_pressure']['max'] = max_systolic_pressure;
        a['systolic_pressure']['min'] = min_systolic_pressure;
        a['diastolic_pressure'] ={}
        a['diastolic_pressure']['max'] = max_diastolic_pressure;
        a['diastolic_pressure']['min'] = min_diastolic_pressure;
        return a;

###########################

class UserCholesterolReadingCreateSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    class Meta:
        model = UserCholesterolReading
        fields = ('id','user','hdl','ldl', 'triglycerides' ,'reading_date','comment')


class UserCholesterolReadingSerializer(serializers.HyperlinkedModelSerializer):
    total_cholesterol = serializers.Field(source='total_cholesterol')
    class Meta:
        model = UserCholesterolReading
        fields = ('hdl','ldl', 'triglycerides', 'total_cholesterol' ,'reading_date','comment')
    def validate_reading_date(self, attrs, source):
        return goal_readings_validator(self, attrs, source)

class UserCholesterolGoalSerializer(serializers.HyperlinkedModelSerializer):
    total_cholesterol = serializers.Field(source='total_cholesterol')
    user = serializers.Field(source='user.id')
    healthy_range = serializers.SerializerMethodField('get_healthy_range')
    readings = serializers.SerializerMethodField('get_readings')

    def get_readings(self, obj=None):
        if obj is not None:
            user = obj.user
            readings = UserCholesterolReading.objects.filter(user=user,reading_date__gte=obj.created_at)
            serializer = UserCholesterolReadingSerializer(readings, many=True)
            return serializer.data

    class Meta:
        model = UserCholesterolGoal
        fields = ('id', 'user','readings','hdl','ldl', 'triglycerides', 'total_cholesterol' ,'healthy_range','target_date','interval_num','interval_unit',)

    def validate(self, attrs):
        return goals_date_validate(self,attrs);

    def get_healthy_range(self, obj=None):
        max_total_cholesterol  = 200;
        min_total_cholesterol  = 0;
        max_hdl  = 50;
        min_hdl  = 0;
        max_ldl  = 100;
        min_ldl  = 0;
        a = {}
        a['total_cholesterol'] ={}
        a['total_cholesterol']['max'] = max_total_cholesterol;
        a['total_cholesterol']['min'] = min_total_cholesterol;
        a['hdl'] ={}
        a['hdl']['max'] = max_hdl;
        a['hdl']['min'] = min_hdl;
        a['ldl'] ={}
        a['ldl']['max'] = max_ldl;
        a['ldl']['min'] = min_ldl;
        return a;

###################

class UserGlucoseReadingCreateSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    class Meta:
        model = UserGlucoseReading
        fields = ('id','user','fasting','random' ,'reading_date','comment')


class UserGlucoseReadingSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = UserGlucoseReading
        fields = ('fasting','random' ,'reading_date','comment')
    def validate_reading_date(self, attrs, source):
        return goal_readings_validator(self, attrs, source)

class UserGlucoseGoalSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    healthy_range = serializers.SerializerMethodField('get_healthy_range')
    readings = serializers.SerializerMethodField('get_readings')

    def get_readings(self, obj=None):
        if obj is not None:
            user = obj.user
            readings = UserGlucoseReading.objects.filter(user=user,reading_date__gte=obj.created_at)
            serializer = UserGlucoseReadingSerializer(readings, many=True)
            return serializer.data
        
    class Meta:
        model = UserGlucoseGoal
        fields = ('id', 'user','readings','random','fasting','healthy_range','target_date','interval_num','interval_unit',)

    def validate(self, attrs):
        return goals_date_validate(self,attrs);

    def get_healthy_range(self, obj=None):
        max_random  = 140;
        min_random  = 100;
        max_fasting  = 100;
        min_fasting  = 70;
        
        a = {}
        a['random'] ={}
        a['random']['max'] = max_random;
        a['random']['min'] = min_random;
        a['fasting'] ={}
        a['fasting']['max'] = max_fasting;
        a['fasting']['min'] = min_fasting;
        return a;