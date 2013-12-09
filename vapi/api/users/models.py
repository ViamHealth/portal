from __future__ import unicode_literals

from django.db import models
from django.contrib.auth.models import User
import hashlib, os

from simple_history.models import HistoricalRecords
#from dateutil.parser import *

s3_image_root = 'http://viamhealth-docsbucket.s3.amazonaws.com/';

MEASURE_CHOICES = (
        ('METRIC','METRIC'),
        ('STANDARD','STANDARD')
)


class UserProfile(models.Model):  
    GENDER_CHOICES = (
        ('MALE','MALE'),
        ('FEMALE','FEMALE')
    )
    BLOOD_GROUP_CHOICES = (
        ('1','A+'),
        ('2','A-'),
        ('3','B+'),
        ('4','B-'),
        ('5','AB+'),
        ('6','AB-'),
        ('7','O+'),
        ('8','O-'),
    )
    #'SON DAUGHTER FATHER MOTHER BROTHER SISTER COUSIN UNCLE AUNT OTHER'
    def get_profile_image_path(self, filename): 
        return 'users/profile_picture_'+hashlib.sha224(str(self.user.id)).hexdigest()+os.path.splitext(filename)[1]

    user = models.ForeignKey('auth.User', unique=True)
    blood_group = models.CharField(max_length=64L,choices=BLOOD_GROUP_CHOICES,blank=True,null=True)
    gender = models.CharField(max_length=140L, choices=GENDER_CHOICES, blank=True)  
    profile_picture = models.ImageField(upload_to=get_profile_image_path, blank=True, null=True)
    date_of_birth = models.DateField(blank=True,null=True)
    mobile = models.CharField(max_length=16L, blank=True,null=True, unique=True )
    fb_profile_id = models.CharField(max_length=62L, blank=True, null=True, unique=True)
    fb_username = models.TextField(blank=True, null=True)
    organization = models.TextField(blank=True, null=True)

    street = models.TextField(blank=True, null=True)
    city = models.TextField(blank=True, null=True)
    state = models.TextField(blank=True, null=True)
    country = models.TextField(blank=True, null=True)
    zip_code = models.TextField(blank=True, null=True)
    lattitude = models.FloatField(blank=True, null=True)
    longitude = models.FloatField(blank=True, null=True)
    address = models.TextField(blank=True, null=True)

    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    #updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')

    history = HistoricalRecords()

    class Meta:
        db_table = 'tbl_user_profiles'

    def profile_picture_url(self):
        if self.profile_picture:
            pic = 'media/'+str(self.profile_picture)
        else:
            pic = 'static/api/default_profile_picture_n.jpg'
        return s3_image_root + pic

    def __unicode__(self):
        return u'Profile %s of user: %s' % (self.id, self.user.username)

    def save(self, *args, **kwargs):
        if not self.fb_profile_id:
            self.fb_profile_id = None
        if not self.mobile:
            self.mobile = None
        super(UserProfile, self).save(*args, **kwargs)

class UserBmiProfile(models.Model):  
    LIFESTYLE_CHOICES = (
        ('1', 'SEDENTARY'),
        ('2', 'LIGHTLY ACTIVE'),
        ('3', 'MODERATELY ACTIVE'),
        ('4', 'VERY ACTIVE'),
        ('5', 'EXTREMELY ACTIVE'),
    )
    user = models.ForeignKey('auth.User', unique=True)
    height = models.FloatField(blank=True, null=True)
    height_measure = models.CharField(max_length=40L, choices=MEASURE_CHOICES, blank=True, default='METRIC',null=True)
    weight = models.FloatField(blank=True, null=True)
    weight_measure = models.CharField(max_length=40L, choices=MEASURE_CHOICES, blank=True, default='METRIC',null=True)  
    lifestyle = models.CharField(max_length=32L, choices=LIFESTYLE_CHOICES, blank=True, null=True)

    systolic_pressure = models.IntegerField(blank=True,null=True)
    diastolic_pressure = models.IntegerField(blank=True,null=True)
    pulse_rate = models.IntegerField(blank=True,null=True)

    random = models.IntegerField(blank=True,null=True)
    fasting = models.IntegerField(blank=True,null=True)

    hdl = models.IntegerField(blank=True,null=True)
    ldl = models.IntegerField(blank=True,null=True)
    triglycerides = models.IntegerField(blank=True,null=True)
    #total_cholesterol = models.IntegerField(blank=True,null=True)
    #Your total cholesterol score is calculated by the following: HDL + LDL + 20% of your triglyceride level.

    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')


    history = HistoricalRecords()

    class Meta:
        db_table = 'tbl_user_bmi_profile'
        verbose_name_plural = 'BMI Profiles'
        verbose_name = 'BMI Profile'
    def __unicode__(self):
        return u'BMI Profile %s of user: %s' % (self.id, self.user.username)

    def total_cholesterol(self):
        total_cholesterol = None
        if self.id:
            if self.hdl is not None and self.ldl is not None and self.triglycerides is not None:
                total_cholesterol = float(self.hdl) + float(self.ldl) + 0.2 * float(self.triglycerides)

        return total_cholesterol


class UserGroupSet(models.Model):
    group = models.ForeignKey('auth.User', related_name="+")
    user = models.ForeignKey('auth.User', related_name="+")
    status = models.CharField(max_length=18L)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')

    history = HistoricalRecords()
    
    class Meta:
        db_table = 'tbl_user_group_set'
    def __unicode__(self):
        return u' %s - %s' % (self.group.username, self.user.username)

User.profile = property(lambda u: UserProfile.objects.get_or_create(user=u)[0])