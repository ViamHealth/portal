    
from .models import *
from rest_framework import serializers
from api.serializers_helper import *
import calendar
from django.core.validators import validate_email
from django.core.exceptions import ValidationError

class HealthfileTagAddSerializer(serializers.HyperlinkedModelSerializer):
    healthfile = serializers.PrimaryKeyRelatedField(many=False)
    class Meta:
        model = HealthfileTag
        fields = ('tag','id','healthfile')

class HealthfileTagListingField(serializers.RelatedField):
    def to_native(self, value):
        return '%s' % (value.tag)

class HealthfileSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    download_url = serializers.Field(source='download_url')
    updated_by = serializers.Field(source='user.id')
    updated_at = serializers.SerializerMethodField('get_updated_at')
    updated_by_name = serializers.Field(source='user.first_name')
    #tags = HealthfileTagListingField(many=True)
    class Meta:
        model = Healthfile
        fields = ('id', 'user','name' ,'description','mime_type','download_url','updated_by','updated_at','updated_by_name',)
    def get_updated_at(self,obj=None):
        return calendar.timegm(obj.updated_at.timetuple())


class HealthfileEditSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    #tags = HealthfileTagListingField(many=True,read_only=True)
    class Meta:
        model = Healthfile
        fields = ('id','description',)

class HealthfileUploadSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    download_url = serializers.Field(source='download_url')
    class Meta:
        model = Healthfile
        fields = ('id','file',)

class FileShareSerializer(serializers.Serializer):
    email = serializers.CharField(required=True)

    def validate_email(self, attrs, source):
        value = attrs[source]   
        try:
            validate_email( value )
            return attrs
        except ValidationError:
            raise serializers.ValidationError("Enter a valid e-mail address.")