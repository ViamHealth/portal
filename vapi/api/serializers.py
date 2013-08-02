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

class ReminderListSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = Reminder
        exclude = ('updated_by',)
        fields = ('id','url', 'user','details','start_datetime' ,'repeat_mode','repeat_day','repeat_hour','repeat_min','repeat_weekday','repeat_day_interval','created_at','updated_at')

class ReminderSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = Reminder
        exclude = ('created_at','updated_at','updated_by',)

#class HealthfileListSerializer(serializers.HyperlinkedModelSerializer):
#    class Meta:
#        model = Healthfile
#        fields = ('id', 'url','user','tags','name' ,'description','mime_type','stored_url','status','created_at','updated_at')

class HealthfileSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    class Meta:
        model = Healthfile
        #exclude = ('created_at','updated_at','updated_by',)
        exclude = ('updated_by',)
        #optional_fields = ('user')
        fields = ('id', 'url','user','tags','name' ,'description','mime_type','stored_url','status','created_at','updated_at')

class HealthfileTagListSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = HealthfileTag
        fields = ('id','url','tag' ,'healthfile','created_at','updated_at')

class HealthfileTagSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = HealthfileTag
        exclude = ('created_at','updated_at','updated_by',)




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
    class Meta:
        model = UserWeightGoal
        exclude = ('created_at','updated_at','updated_by','status')
        #fields = ('id', 'url','user','readings','weight','weight_measure' ,'target_date','interval_num','interval_unit','created_at','updated_at','updated_by')

class UserWeightGoalListSerializer(serializers.HyperlinkedModelSerializer):
    readings = UserWeightReadingListSerializer(required=False)

    class Meta:
        model = UserWeightGoal
        fields = ('id', 'url','user','readings','weight','weight_measure' ,'target_date','interval_num','interval_unit','created_at','updated_at')

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
