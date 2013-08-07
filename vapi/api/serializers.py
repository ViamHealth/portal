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
    profile_picture_url = serializers.Field(source='profile_picture_url')
    class Meta:
        model = UserProfile
        fields = ( 'location', 'gender', 'date_of_birth', 'profile_picture_url')

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
    class Meta:
        model = User
        fields = ('id', 'url', 'first_name', 'last_name','email','username', )

class ReminderSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = Reminder
        fields = ('id','url', 'user','details','start_timestamp' ,'repeat_mode','repeat_day','repeat_hour','repeat_min','repeat_weekday','repeat_day_interval')


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
        fields = ('description',)

class HealthfileUploadSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    class Meta:
        model = Healthfile
        fields = ('file',)

class HealthfileTagListSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = HealthfileTag
        fields = ('id','url','tag' ,'healthfile','created_at','updated_at')

class HealthfileTagSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = HealthfileTag
        exclude = ('created_at','updated_at','updated_by',)


"""

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
