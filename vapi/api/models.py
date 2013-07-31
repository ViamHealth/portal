# This is an auto-generated Django model module.
# You'll have to do the following manually to clean this up:
#     * Rearrange models' order
#     * Make sure each model has one field with primary_key=True
# Feel free to rename the models, but don't rename db_table values or field names.
#
# Also note: You'll have to insert the output of 'django-admin.py sqlcustom [appname]'
# into your database.
from __future__ import unicode_literals

from django.db import models
from django.contrib.auth.models import User, Group
from rest_framework.authtoken.models import Token
from django.db.models.signals import post_save
from django.dispatch import receiver

class UserProfile(models.Model):  
    user = models.ForeignKey('auth.User', unique=True)
    location = models.CharField(max_length=140)  
    gender = models.CharField(max_length=140)  
    #profile_picture = models.ImageField(upload_to='thumbpath', blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    class Meta:
        db_table = 'tbl_user_profiles'

    def __unicode__(self):
        return u'Profile of user: %s' % self.user.username

class UsersMap(models.Model):
    id = models.AutoField(primary_key=True)
    initiatior_user = models.ForeignKey('auth.User', related_name="+")
    connected_user = models.ForeignKey('auth.User', related_name="+")
    connection_status = models.CharField(max_length=18L)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    class Meta:
        db_table = 'tbl_users_map'

class UserGroupSet(models.Model):
    id = models.AutoField(primary_key=True)
    user = models.ForeignKey('auth.User', related_name="+")
    group = models.ForeignKey('auth.User', related_name="+")
    connection_status = models.CharField(max_length=18L)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    class Meta:
        db_table = 'tbl_user_group_set'

class HealthfileTag(models.Model):
    id = models.AutoField(primary_key=True)
    healthfile = models.ForeignKey('Healthfile', related_name="tags")
    tag = models.CharField(max_length=64L)
    created_at = models.DateTimeField()
    class Meta:
        db_table = 'tbl_healthfile_tags'

class Healthfile(models.Model):
    id = models.AutoField(primary_key=True)
    user = models.ForeignKey('auth.User', related_name="+")
    name = models.CharField(max_length=256L)
    description = models.TextField()
    mime_type = models.CharField(max_length=256L)
    stored_url = models.CharField(max_length=256L)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')
    class Meta:
        db_table = 'tbl_healthfiles'

class Reminder(models.Model):
    user = models.ForeignKey('auth.User', related_name="+")
    details = models.TextField()
    start_datetime = models.IntegerField()
    repeat_mode = models.CharField(max_length=32L,blank=True)
    repeat_day = models.CharField(max_length=2L,blank=True)
    repeat_hour = models.CharField(max_length=2L,blank=True)
    repeat_min = models.CharField(max_length=2L,blank=True)
    repeat_weekday = models.CharField(max_length=9L,blank=True)
    repeat_day_interval = models.CharField(max_length=3L,blank=True)
    status = models.CharField(max_length=18L,blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')
    class Meta:
        db_table = 'tbl_reminders'

class UserWeightGoal(models.Model):
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
    GOAL_STATUS_CHOICES = (
        ('ACTIVE','ACTIVE'),
        ('DELETED','DELETED')
    )
    user = models.ForeignKey('auth.User', related_name="+")
    weight = models.IntegerField()
    weight_measure = models.CharField(max_length=12L, choices=MEASURE_CHOICES, default='METRIC')
    target_date = models.DateField(blank=True)
    interval_num = models.IntegerField(blank=True)
    interval_unit = models.CharField(max_length=6L, choices=INTERVAL_UNIT_CHOICES,blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')
    status = models.CharField(max_length=64L, choices=GOAL_STATUS_CHOICES, default='ACTIVE')
    class Meta:
        db_table = 'tbl_user_weight_goals'
    def __unicode__(self):
        return u'%s %s' % (self.id, self.user.username)

class UserWeightReading(models.Model):
    MEASURE_CHOICES = (
        ('METRIC','METRIC'),
        ('STANDARD','STANDARD')
    )
    id = models.AutoField(primary_key=True)
    user_weight_goal = models.ForeignKey('UserWeightGoal', related_name="readings")
    weight = models.IntegerField()
    weight_measure = models.CharField(max_length=12L, choices=MEASURE_CHOICES, default='METRIC')
    reading_date = models.DateField()
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')
    class Meta:
        db_table = 'tbl_user_weight_readings'
    def __unicode__(self):
        return u'%s %s' % (self.id, self.user_weight_goal)

User.profile = property(lambda u: UserProfile.objects.get_or_create(user=u)[0])