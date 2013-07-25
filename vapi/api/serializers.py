from django.contrib.auth.models import User
from api.models import *
from rest_framework import serializers
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
    class Meta:
        model = UserProfile
        fields = ( 'location', 'gender')

class UserSerializer(serializers.HyperlinkedModelSerializer):
    profile = UserProfileSerializer(required=False)
    class Meta:
        model = User
        fields = ('id', 'url', 'username', 'email', 'first_name', 'last_name', 'profile')

class ReminderSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = Reminder
        exclude = ('created_at','updated_at','updated_by',)
        #fields = ('id', 'user','details','start_datetime' ,'repeat_mode','repeat_day','repeat_hour','repeat_min','repeat_weekday','repeat_day_interval','status','created_at','updated_at','updated_by')

class HealthfileSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = Healthfile
        fields = ('id', 'user','tags','name' ,'description','mime_type','stored_url','created_at','updated_at')

class HealthfileTagSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = HealthfileTag
        fields = ('id','tag' ,'healthfile')

class UserWeightReadingSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = UserWeightReading
        #fields = ('id','user_weight_goal', 'weight','weight_measure' ,'reading_date','created_at','updated_at','updated_by')
        fields = ('id','user_weight_goal', 'weight','weight_measure' ,'reading_date','created_at','updated_at')

class UserWeightReadingListSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = UserWeightReading
        fields = ('weight','weight_measure' ,'reading_date')

class UserWeightGoalSerializer(serializers.HyperlinkedModelSerializer):
    readings = UserWeightReadingListSerializer(required=False)

    class Meta:
        model = UserWeightGoal
        exclude = ('created_at','updated_at','updated_by',)
        fields = ('id', 'url','user','readings','weight','weight_measure' ,'target_date','interval_num','interval_unit','created_at','updated_at','updated_by')

class GoalSerializer(serializers.HyperlinkedModelSerializer):
    weight = UserWeightGoalSerializer

    class Meta():
        fields = ('weight')


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
