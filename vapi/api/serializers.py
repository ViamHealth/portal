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
        model = Healthfiles
        fields = ('id', 'user','tags','name' ,'description','mime_type','stored_url','created_at','updated_at','updated_by')

class HealthfileTagSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = HealthfileTags
        fields = ('id','tag' ,'healthfile')	