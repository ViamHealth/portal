from django.contrib.auth.models import User, Group
from api.models import *
from rest_framework import serializers

class UserSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = User
        fields = ('url', 'username', 'email', 'groups')

class GroupSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = Group
        fields = ('url', 'name')

class HealthfileSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = Healthfile
        fields = ('id', 'user','tags','name' ,'description','mime_type','stored_url','created_at','updated_at','updated_by')

class HealthfileTagSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = HealthfileTag
        fields = ('id','tag' ,'healthfile')

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
