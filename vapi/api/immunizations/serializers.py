from .models import *
from rest_framework import serializers

class ImmunizationSerializer(serializers.ModelSerializer):
    class Meta:
        model = Immunization
        fields = ( 'id', 'label','recommended_age',)

class UserImmunizationSerializer(serializers.ModelSerializer):
    immunization = serializers.Field(source='immunization.id')
    user = serializers.Field(source='user.id')
    class Meta:
        model = UserImmunization
        fields = ( 'id', 'immunization', 'user','is_completed')

class UserImmunizationListSerializer(serializers.Serializer):
    immunizations = serializers.SerializerMethodField('get_immunizations')
    user_immunizations = serializers.SerializerMethodField('get_user_immunizations')

    def get_immunizations(self, obj=None):
        i = Immunization.objects.filter(is_deleted=False)
        immunizations = ImmunizationSerializer(i, many=True)
        return immunizations.data

    def get_user_immunizations(self, obj=None):
        i = UserImmunization.objects.filter(is_deleted=False)
        immunizations = UserImmunizationSerializer(i, many=True)
        return immunizations.data
