from django.contrib.auth.models import User
from api.models import *
from rest_framework import serializers



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
        fields = ('id', 'user','details','start_datetime' ,'repeat_mode','repeat_day','repeat_hour','repeat_min','repeat_weekday','repeat_day_interval','status','created_at','updated_at')

class HealthfileSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = Healthfile
        fields = ('id', 'user','tags','name' ,'description','mime_type','stored_url','created_at','updated_at')

class HealthfileTagSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = HealthfileTag
        fields = ('id','tag' ,'healthfile')

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
