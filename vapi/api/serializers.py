from django.contrib.auth.models import User
from api.models import *
from rest_framework import serializers
from django.core.validators import validate_email
from django.core.exceptions import ValidationError

"""
from django.contrib.auth import authenticate


class AuthTokenSerializer(serializers.Serializer):
    username = serializers.CharField()
    password = serializers.CharField()

    def validate(self, attrs):
        username = attrs.get('username')
        password = attrs.get('password')

        if username and password:
            user = authenticate(username=username, password=password)

            if user:
                if not user.is_active:
                    raise serializers.ValidationError('User account is disabled.')
                attrs['user'] = user
                return attrs
            else:
                raise serializers.ValidationError('Unable to login with provided credentials.')
        else:
            raise serializers.ValidationError('Must include "username" and "password"')
"""

class UserProfileSerializer(serializers.ModelSerializer):
    profile_picture_url = serializers.Field(source='profile_picture_url')
    class Meta:
        model = UserProfile
        fields = ( 'location', 'gender', 'date_of_birth', 'profile_picture_url')

class UserPasswordSerializer(serializers.ModelSerializer):
    class Meta:
        model = User
        fields = ( 'password',)
    

class UserProfilePicSerializer(serializers.ModelSerializer):
    class Meta:
        model = UserProfile
        fields = ( 'profile_picture',)

class UserSerializer(serializers.HyperlinkedModelSerializer):
    profile = UserProfileSerializer(required=False)
    class Meta:
        model = User
        fields = ('id', 'url', 'username', 'email', 'first_name', 'last_name', 'profile')

class UserEditSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = User
        fields = ('id', 'url', 'first_name', 'last_name',)

class UserCreateSerializer(serializers.HyperlinkedModelSerializer):
    
    def validate_username(self, attrs, source):
        value = attrs[source]
        try:
            validate_email( value )
            return attrs
        except ValidationError:
            raise serializers.ValidationError("Enter a valid e-mail address.")
    class Meta:
        model = User
        fields = ('id', 'url', 'first_name', 'last_name','username', )


class UserSignupSerializer(serializers.HyperlinkedModelSerializer):
    
    def validate_username(self, attrs, source):
        value = attrs[source]
        try:
            validate_email( value )
            return attrs
        except ValidationError:
            raise serializers.ValidationError("Enter a valid e-mail address.")
    class Meta:
        model = User
        fields = ('username', 'password')

class ReminderSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    class Meta:
        model = Reminder
        fields = ('id','url', 'user','details','start_timestamp' ,'repeat_mode','repeat_day','repeat_hour','repeat_min','repeat_weekday','repeat_day_interval')

class UserBmiProfileSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    class Meta:
        model = UserBmiProfile
        fields = ('id','url', 'user', 'height' ,'height_measure', 'weight' , 'weight_measure')


#class HealthfileListSerializer(serializers.HyperlinkedModelSerializer):
#    class Meta:
#        model = Healthfile
#        fields = ('id', 'url','user','tags','name' ,'description','mime_type','file','status','created_at','updated_at')

class HealthfileSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    download_url = serializers.Field(source='download_url')

    class Meta:
        model = Healthfile
        fields = ('id', 'url','user','tags','name' ,'description','mime_type','download_url')

class HealthfileEditSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    class Meta:
        model = Healthfile
        fields = ('id','url','description',)

class HealthfileUploadSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    class Meta:
        model = Healthfile
        fields = ('file','description')

class HealthfileTagSerializer(serializers.HyperlinkedModelSerializer):
    healthfile = serializers.Field(source='healthfile.id')
    class Meta:
        model = HealthfileTag
        fields = ('id','url','healthfile','tag')

class UserWeightReadingSerializer(serializers.HyperlinkedModelSerializer):
    user_weight_goal = serializers.Field(source='user_weight_goal.id')
    class Meta:
        model = UserWeightReading
        fields = ('id','url','user_weight_goal','weight','weight_measure' ,'reading_date')

class UserWeightGoalSerializer(serializers.HyperlinkedModelSerializer):
    readings = UserWeightReadingSerializer(required=False)
    user = serializers.Field(source='user.id')
    class Meta:
        model = UserWeightGoal
        fields = ('id', 'url','user','readings','weight','weight_measure' ,'target_date','interval_num','interval_unit',)

class UserBloodPressureReadingSerializer(serializers.HyperlinkedModelSerializer):
    user_blood_pressure_goal = serializers.Field(source='user_blood_pressure_goal.id')
    class Meta:
        model = UserBloodPressureReading
        fields = ('id','url','user_blood_pressure_goal','systolic_pressure','diastolic_pressure', 'pulse_rate' ,'reading_date')

class UserBloodPressureGoalSerializer(serializers.HyperlinkedModelSerializer):
    readings = UserBloodPressureReadingSerializer(required=False)
    user = serializers.Field(source='user.id')
    class Meta:
        model = UserBloodPressureGoal
        fields = ('id', 'url','user','readings','systolic_pressure','diastolic_pressure', 'pulse_rate' ,'target_date','interval_num','interval_unit',)

class UserCholesterolReadingSerializer(serializers.HyperlinkedModelSerializer):
    user_cholesterol_goal = serializers.Field(source='user_cholesterol_goal.id')
    class Meta:
        model = UserCholesterolReading
        fields = ('id','url','user_cholesterol_goal','hdl','ldl', 'triglycerides', 'total_cholesterol' ,'reading_date')

class UserCholesterolGoalSerializer(serializers.HyperlinkedModelSerializer):
    readings = UserCholesterolReadingSerializer(required=False)
    user = serializers.Field(source='user.id')
    class Meta:
        model = UserCholesterolGoal
        fields = ('id', 'url','user','readings','hdl','ldl', 'triglycerides', 'total_cholesterol' ,'target_date','interval_num','interval_unit',)

"""

class GoalSerializer(serializers.HyperlinkedModelSerializer):
    weight = UserWeightGoalSerializer

    class Meta():
        fields = ('weight')
"""

#class AuthTokenSerializer(serializers.Serializer):
#    username = serializers.CharField()
#    password = serializers.CharField()

#    def validate(self, attrs):
#        username = attrs.get('username')
#        password = attrs.get('password')
#
#        if username and password:
#            user = authenticate(username=username, password=password)
#
#            if user:
#                if not user.is_active:
#                    raise serializers.ValidationError('User account is disabled.')
#                attrs['user'] = user
#                return attrs
#            else:
#                raise serializers.ValidationError('Unable to login with provided credentials.')
#        else:
#            raise serializers.ValidationError('Must include "username" and "password"')
