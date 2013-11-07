from __future__ import unicode_literals

from django.db import models
from django.db.models.signals import post_save, pre_save
from django.dispatch import receiver
import datetime
from dateutil.rrule import *
from simple_history.models import HistoricalRecords


s3_image_root = 'http://viamhealth-docsbucket.s3.amazonaws.com/';

GLOBAL_STATUS_CHOICES = (
        ('ACTIVE','ACTIVE'),
        ('DELETED','DELETED')
    )
MEASURE_CHOICES = (
        ('METRIC','METRIC'),
        ('STANDARD','STANDARD')
)
INTERVAL_UNIT_CHOICES = (
    ('DAY','DAY'),
    ('WEEK','WEEK'),
    ('MONTH','MONTH'),
    ('YEAR','YEAR'),
)


class Reminder(models.Model):
    REMINDER_REPEAT_MODE_CHOICES = (
        ('0','NONE'),
        ('1','MONTHLY'),
        ('2','WEEKLY'),
        ('3','DAILY'),
        ('4','YEARLY'),
    )
    REMINDER_TYPES = (
        ('1','OTHER'),
        ('2','MEDICATION'),
        ('3','MEDICALTEST')
    )
    WEEKDAY_CHOICES = (
        ('1','SUNDAY'),
        ('2','MONDAY'),
        ('3','TUESDAY'),
        ('4','WEDNESDAY'),
        ('5','THURSDAY'),
        ('6','FRIDAY'),
        ('7','SATURDAY'),
    )
    

    user = models.ForeignKey('auth.User', related_name="+")
    type = models.CharField(max_length=64L,choices=REMINDER_TYPES, default='1',db_index=True)
    name = models.TextField(blank=False)
    details = models.TextField(blank=True, null=True)

    morning_count = models.FloatField(blank=True,null=True)
    afternoon_count = models.FloatField(blank=True,null=True)
    evening_count = models.FloatField(blank=True,null=True)
    night_count = models.FloatField(blank=True,null=True)

    start_date = models.DateField(null=True,blank=True)
    end_date = models.DateField(null=True,blank=True)
    repeat_mode = models.CharField(max_length=32L,blank=True,choices=REMINDER_REPEAT_MODE_CHOICES, default='0',db_index=True)
    repeat_day = models.CharField(max_length=2L,blank=True,null=True)
    repeat_hour = models.CharField(max_length=2L,blank=True,null=True)
    repeat_min = models.CharField(max_length=2L,blank=True,null=True)
    repeat_weekday = models.CharField(max_length=9L,choices=WEEKDAY_CHOICES,blank=True,null=True)
    repeat_every_x = models.IntegerField(blank=True,null=True)
    repeat_i_counter = models.IntegerField(blank=True,null=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')    
    
    history = HistoricalRecords()
    
    def save(self, *args, **kwargs):
        if not self.id:
            if self.start_date is None:
                self.start_date = datetime.date.today()

        super(Reminder,self).save()
    
    class Meta:
        db_table = 'tbl_reminders'
    
class ReminderReadings(models.Model):
    user = models.ForeignKey('auth.User', related_name="+")
    reminder = models.ForeignKey('Reminder', related_name="+")
    morning_check = models.BooleanField(blank=True,default=False)
    afternoon_check = models.BooleanField(blank=True,default=False)
    evening_check = models.BooleanField(blank=True,default=False)
    night_check = models.BooleanField(blank=True,default=False)
    complete_check = models.BooleanField(blank=True,default=False)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by') 

    reading_date = models.DateField()

    history = HistoricalRecords()

    class Meta:
        db_table = 'tbl_reminderreadings'

#def delete_reminder_readings(sender, instance, **kwargs):
#    if instance.id:
#        r = Reminder.objects.get(pk=instance.id)
#        if instance.start_date != r.start_date:
#            ReminderReadings.objects.filter(reminder=instance).delete()

def create_reminder_readings(sender, instance, created, **kwargs):
    if created is True:
        if instance.repeat_mode != '0' :
            if instance.repeat_mode == '1':
                repeat = DAILY
            elif instance.repeat_mode == '2':
                repeat = WEEKLY
            elif instance.repeat_mode == '3':
                repeat = MONTHLY
            elif instance.repeat_mode == '4':
                repeat = YEARLY

            if instance.repeat_i_counter is None:
                repeat_counter = 20
            else:
                repeat_counter = instance.repeat_i_counter

            if instance.repeat_every_x is not None:
                interval = instance.repeat_every_x
            else:
                interval = 1

            if instance.end_date is None:
                list_of_dates = list(rrule(repeat, interval=interval, count=repeat_counter,dtstart=instance.start_date))
            else:
                list_of_dates = list(rrule(repeat, interval=interval, dtstart=instance.start_date, until=instance.end_date))

            for d in list_of_dates:
                reading_date = d
                reading = ReminderReadings(user=instance.user,reminder=instance,updated_by=instance.user,reading_date=reading_date)
                reading.save()
        else:
            reading = ReminderReadings(user=instance.user,reminder=instance,updated_by=instance.user,reading_date=instance.start_date)
            reading.save()


# register the signal
post_save.connect(create_reminder_readings, sender=Reminder)
#pre_save.connect(delete_reminder_readings, sender=Reminder)


