from .models import *
from rest_framework import serializers

class PhysicalActivitySerializer(serializers.ModelSerializer):
    class Meta:
        model = PhysicalActivity
        fields = ( 'id', 'label','value',)

class UserPhysicalActivitySerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    physical_activity = PhysicalActivitySerializer(many=False)
    calories_spent = serializers.SerializerMethodField('get_calories_spent')
    class Meta:
        model = UserPhysicalActivity
        fields = ('id','user','weight','time_spent','physical_activity','calories_spent','activity_date')

    def get_calories_spent(self, obj=None):
        if obj is not None:
            if obj.user_calories_spent is not None and obj.user_calories_spent != '':
                return obj.user_calories_spent
            elif obj.weight is not None and obj.time_spent is not None and obj.physical_activity is not None:
                return ( float(obj.weight) * float(obj.time_spent) * obj.physical_activity.value * 2.2 ) / 60
        else:
            return None


class UserPhysicalActivityCreateSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    physical_activity = serializers.PrimaryKeyRelatedField(many=False)
    calories_spent = serializers.SerializerMethodField('get_calories_spent')
    #physical_activity = serializers.HyperlinkedRelatedField(many=False, read_only=False,view_name='physicalactivity-detail')
    class Meta:
        model = UserPhysicalActivity
        fields = ('id','user','weight','time_spent','physical_activity','user_calories_spent','calories_spent','activity_date')

    def get_calories_spent(self, obj=None):
        if obj is not None:
            if obj.user_calories_spent is not None and obj.user_calories_spent != '':
                return obj.user_calories_spent
            elif obj.weight is not None and obj.time_spent is not None and obj.physical_activity is not None:
                return ( float(obj.weight) * float(obj.time_spent) * obj.physical_activity.value * 2.2 ) / 60
        else:
            return None