from .models import *
from rest_framework import serializers
from django.core.exceptions import ValidationError
from api.serializers_helper import *


class ReminderSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    class Meta:
        model = Reminder
        fields = ('id','user','type','name','details','morning_count','afternoon_count','evening_count','night_count','start_date','end_date','repeat_mode','repeat_day','repeat_hour','repeat_min','repeat_weekday','repeat_every_x','repeat_i_counter')

class ReminderReadingsSerializer(serializers.HyperlinkedModelSerializer):
    reminder = ReminderSerializer(required=False,read_only=True)
    user = serializers.Field(source='user.id')
    class Meta:
        model = ReminderReadings
        fields = ('id','reminder','reading_date','morning_check','afternoon_check','evening_check','night_check','complete_check','user')