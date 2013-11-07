from .models import *
from rest_framework import serializers
from django.core.exceptions import ValidationError
from api.serializers_helper import *


class ReminderSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    #start_timestamp = serializers.IntegerField(source='start_timestamp',read_only=True)
    class Meta:
        model = Reminder
        fields = ('id','user','type','name','details','morning_count','afternoon_count','evening_count','night_count','start_date','end_date','repeat_mode','repeat_day','repeat_hour','repeat_min','repeat_weekday','repeat_every_x','repeat_i_counter')
    def validate(self, attrs):
        """
        if StringKeyIsNotNull(self.data,'id') and self.data['id'] != '':
            r = Reminder.objects.get(pk=int(self.data['id']))
            if r.start_date != attrs['start_date']:
                raise serializers.ValidationError("can not edit start_date")
        
        if attrs['type'] == '2':
            if FloatIsNull(attrs['morning_count'],"0.0") and FloatIsNull(attrs['afternoon_count'],"0.0") and FloatIsNull(attrs['evening_count'],"0.0") and FloatIsNull(attrs['night_count'],"0.0") :
                raise serializers.ValidationError("Provide atleast one of these 4 - morning_count, afternoon_count, evening_count, night_count")
        elif attrs['type'] == '1':
            if attrs['morning_count'] is not None or attrs['afternoon_count'] is not None or attrs['evening_count'] is not None or attrs['night_count'] is not None:
                raise serializers.ValidationError("Following not allowed for type 'OTHER' - morning_count, afternoon_count, evening_count, night_count")
        
        
        if attrs['repeat_mode'] == '0':
            if StringIsNotNull(attrs['repeat_min']) or StringIsNotNull(attrs['repeat_hour']) or StringIsNotNull(attrs['repeat_day']) or StringIsNotNull(attrs['repeat_weekday']):
                raise serializers.ValidationError("For None repeat_mode reminders make sure you do not provide repeat_weekday, repeat_min, repeat_min repeat_day")
        elif attrs['repeat_mode'] == '1':
            if StringIsNull(attrs['repeat_min']) or StringIsNull(attrs['repeat_hour']) or StringIsNotNull(attrs['repeat_day']) or StringIsNotNull(attrs['repeat_weekday']):
                raise serializers.ValidationError("For Daily reminders make sure you provide repeat_min , repeat_hour and do not provide repeat_weekday and repeat_day")
        elif attrs['repeat_mode'] == '2':
            if StringIsNotNull(attrs['repeat_min']) or StringIsNotNull(attrs['repeat_hour']) or StringIsNotNull(attrs['repeat_day']) or StringIsNotNull(attrs['repeat_weekday']):
                raise serializers.ValidationError("For Daily reminders make sure you provide repeat_min , repeat_hour , repeat_weekday and do not provide repeat_day")
        elif attrs['repeat_mode'] == '3':
            if StringIsNotNull(attrs['repeat_min']) or StringIsNotNull(attrs['repeat_hour']) or StringIsNotNull(attrs['repeat_day']) or StringIsNotNull(attrs['repeat_weekday']):
                raise serializers.ValidationError("For Daily reminders make sure you provide repeat_min , repeat_hour , repeat_day and do not provide repeat_weekday")
        """
        return attrs


class ReminderReadingsSerializer(serializers.HyperlinkedModelSerializer):
    reminder = ReminderSerializer(required=False,read_only=True)
    #reminder = serializers.Field(source='reminder.id')
    user = serializers.Field(source='user.id')
    class Meta:
        model = ReminderReadings
        fields = ('id','reminder','morning_check','afternoon_check','evening_check','night_check','complete_check','user')