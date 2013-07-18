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
    id = models.AutoField(primary_key=True)
    user = models.ForeignKey('auth.User', related_name="+")
    details = models.TextField()
    start_datetime = models.DateTimeField()
    repeat_mode = models.CharField(max_length=32L)
    repeat_day = models.CharField(max_length=2L)
    repeat_hour = models.CharField(max_length=2L)
    repeat_min = models.CharField(max_length=2L)
    repeat_weekday = models.CharField(max_length=9L)
    repeat_day_interval = models.CharField(max_length=3L)
    status = models.CharField(max_length=18L)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')
    class Meta:
        db_table = 'tbl_reminders'

class UserWeightGoal(models.Model):
    id = models.AutoField(primary_key=True)
    user = models.ForeignKey('auth.User', related_name="+")
    weight = models.IntegerField()
    weight_measure = models.CharField(max_length=12L)
    target_date = models.DateField()
    interval_num = models.IntegerField()
    interval_unit = models.CharField(max_length=6L)
    created_at = models.DateTimeField()
    updated_at = models.DateTimeField()
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')
    status = models.CharField(max_length=64L)
    class Meta:
        db_table = 'tbl_user_weight_goals'

class UserWeightReading(models.Model):
    id = models.AutoField(primary_key=True)
    user_weight_goal = models.ForeignKey('UserWeightGoal', related_name="readings")
    weight = models.IntegerField()
    weight_measure = models.CharField(max_length=12L)
    reading_date = models.DateField()
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')
    class Meta:
        db_table = 'tbl_user_weight_readings'

