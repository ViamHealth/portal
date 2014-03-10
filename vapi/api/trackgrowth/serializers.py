from .models import *
from rest_framework import serializers
from datetime import datetime, timedelta

class TrackGrowthDataSerializer(serializers.ModelSerializer):
    class Meta:
        model = TrackGrowthData
        fields = ( 'id', 'label','gender','age','height','weight')

class UserTrackGrowthDataSerializer(serializers.ModelSerializer):
    class Meta:
        model = UserTrackGrowthData
        fields = ( 'id', 'user', 'entry_date','height','weight')

    def validate_entry_date(self, attrs):
        user = attrs['user']
        entry_date = attrs['entry_date']
        u = UserProfile.objects.get(pk=user)
        if u.date_of_birth is not None:
            if entry_date < u.date_of_birth:
                raise serializers.ValidationError("Entry date before date of birth")
        return attrs
