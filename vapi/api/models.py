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
from django.db.models.signals import post_save, pre_save
from django.dispatch import receiver
import hashlib, os, mimetypes, pprint, datetime
from dateutil.relativedelta import relativedelta
from dateutil.rrule import *
from django.utils.dateformat import format
from history.models import HistoricalRecords
from dateutil.parser import *

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
    phone_number = models.CharField(max_length=16L, blank=True)
    fb_profile_id = models.CharField(max_length=62L, blank=True)
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

class HealthfileTag(models.Model):
    healthfile = models.ForeignKey('Healthfile', related_name="+")
    tag = models.CharField(max_length=64L)

    history = HistoricalRecords()

    class Meta:
        db_table = 'tbl_healthfile_tags'
    def __unicode__(self):
        return u' %s of healthfile %s' % (self.id, self.healthfile)

class Healthfile(models.Model):
    def get_healthfile_path(self, filename):
        return 'healthfiles/'+hashlib.sha224(str(self.id)).hexdigest()

    user = models.ForeignKey('auth.User', related_name="+")
    name = models.CharField(max_length=256L,blank=True, null=True)
    description = models.TextField(blank=True, null=True)
    mime_type = models.CharField(max_length=256L,blank=True, null=True)
    file = models.FileField(upload_to=get_healthfile_path, blank=True, editable=True,)
    status = models.CharField(max_length=18L, choices=GLOBAL_STATUS_CHOICES, default='ACTIVE', db_index=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')
    #TODO: Use forms instead of using this flag
    uploading_file = False

    class Meta:
        db_table = 'tbl_healthfiles'

    def __unicode__(self):
        return u'%s' % self.id

    def download_url(self):
        return 'http://api.viamhealth.com/healthfiles/download/'+str(self.id)+'/';
        """
        from django.http import HttpRequest
        from django.core.urlresolvers import reverse 

        return HttpRequest.build_absolute_uri(reverse('download-healthfiles'),args=(self.id));
        
        if self.file:
            return s3_image_root + 'media/' + str(self.file)
        else:
            return ''
        """

    def save(self, *args, **kwargs):
        if self.uploading_file:
            hfile = self.file
            self.name = hfile.name
            mime_type = mimetypes.guess_type(hfile.name)[0]
            if mime_type is None:
                mime_type = "application/octet-stream"
            self.mime_type = mime_type
        super(Healthfile, self).save(*args, **kwargs)



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


#####################
######### GOALS #####
#####################

def goal_get_target_date(obj, *args, **kwargs):
    if obj.interval_num > 0 and obj.interval_unit is not None:
        d1 = datetime.datetime.now()
    if obj.interval_unit == 'DAY':
        obj.target_date = d1 + relativedelta(days=obj.interval_num)
    elif obj.interval_unit == 'WEEK':
        obj.target_date = d1 + relativedelta(weeks=obj.interval_num)
    elif obj.interval_unit == 'MONTH':
        obj.target_date = d1 + relativedelta(months=obj.interval_num)
    elif obj.interval_unit == 'YEAR':
        obj.target_date = d1 + relativedelta(years=obj.interval_num)
    if obj.target_date is not None:
        obj.target_date = obj.target_date.date()
    return obj.target_date

class UserWeightGoal(models.Model):
    
    user = models.ForeignKey('auth.User', related_name="+")
    weight = models.FloatField()
    weight_measure = models.CharField(max_length=12L, choices=MEASURE_CHOICES, default='METRIC',null=True)
    target_date = models.DateField(blank=True,null=True)
    interval_num = models.IntegerField(blank=True,default=0)
    interval_unit = models.CharField(max_length=6L, choices=INTERVAL_UNIT_CHOICES,blank=True,null=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')

    history = HistoricalRecords()

    class Meta:
        db_table = 'tbl_user_weight_goals'
        verbose_name_plural = 'Weight Goals'
        verbose_name = 'Weight Goal'
    def __unicode__(self):
        return u'%s' % (self.id)
    
    def save(self, *args, **kwargs):
        if self.target_date is None:
            self.target_date = goal_get_target_date(self, *args, **kwargs)
        super(UserWeightGoal, self).save(*args, **kwargs)
    
class UserWeightReading(models.Model):
    user = models.ForeignKey('auth.User', related_name="+")
    weight = models.FloatField()
    weight_measure = models.CharField(max_length=12L, choices=MEASURE_CHOICES, default='METRIC',null=True)
    reading_date = models.DateField()
    comment = models.TextField(blank=True, null=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')
    
    history = HistoricalRecords()
    
    class Meta:
        db_table = 'tbl_user_weight_readings'
        verbose_name_plural = 'Weight Readings'
        verbose_name = 'Weight Reading'
        ordering = ['reading_date']

    def __unicode__(self):
        return u'reading %s ' % (self.id)


class UserBloodPressureGoal(models.Model):
    user = models.ForeignKey('auth.User', related_name="+")
    target_date = models.DateField(blank=True,null=True)
    systolic_pressure = models.FloatField()
    diastolic_pressure = models.FloatField()
    pulse_rate = models.IntegerField()
    interval_num = models.IntegerField(blank=True,default=0)
    interval_unit = models.CharField(max_length=6L, choices=INTERVAL_UNIT_CHOICES,blank=True, null=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')

    history = HistoricalRecords()
    
    class Meta:
        db_table = 'tbl_user_blood_pressure_goals'
        verbose_name_plural = 'BP Goals'
        verbose_name = 'BP Goal'
    def __unicode__(self):
        return u'%s' % (self.id)

    def save(self, *args, **kwargs):
        if self.target_date is None:
            self.target_date = goal_get_target_date(self, *args, **kwargs)
        super(UserBloodPressureGoal, self).save(*args, **kwargs)


class UserBloodPressureReading(models.Model):
    user = models.ForeignKey('auth.User', related_name="+")
    systolic_pressure = models.FloatField()
    diastolic_pressure = models.FloatField()
    pulse_rate = models.IntegerField()
    reading_date = models.DateField()
    comment = models.TextField(blank=True,null=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')

    history = HistoricalRecords()

    class Meta:
        db_table = 'tbl_user_blood_pressure_readings'
        verbose_name_plural = 'BP Readings'
        verbose_name = 'BP Reading'
        ordering = ['reading_date']
    def __unicode__(self):
        return u'reading %s ' % (self.id, )

class UserCholesterolGoal(models.Model):
    user = models.ForeignKey('auth.User', related_name="+")
    target_date = models.DateField(blank=True,null=True)
    hdl = models.FloatField()
    ldl = models.FloatField()
    triglycerides = models.FloatField()
    interval_num = models.IntegerField(blank=True,default=0)
    interval_unit = models.CharField(max_length=6L, choices=INTERVAL_UNIT_CHOICES,blank=True, null=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')
    
    history = HistoricalRecords()

    class Meta:
        db_table = 'tbl_user_cholesterol_goals'
        verbose_name_plural = 'Cholesterol Goals'
        verbose_name = 'Cholesterol Goal'
    def __unicode__(self):
        return u'%s' % (self.id)
    def save(self, *args, **kwargs):
        if self.target_date is None:
            self.target_date = goal_get_target_date(self, *args, **kwargs)
        super(UserCholesterolGoal, self).save(*args, **kwargs)

    def total_cholesterol(self):
        total_cholesterol = None
        if self.id:
            if self.hdl is not None and self.ldl is not None and self.triglycerides is not None:
                total_cholesterol = float(self.hdl) + float(self.ldl) + 0.2 * float(self.triglycerides)

        return total_cholesterol

class UserCholesterolReading(models.Model):
    user = models.ForeignKey('auth.User', related_name="+")
    hdl = models.FloatField()
    ldl = models.FloatField()
    triglycerides = models.FloatField()
    reading_date = models.DateField()
    comment = models.TextField(blank=True, null=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')

    history = HistoricalRecords()

    class Meta:
        db_table = 'tbl_user_cholesterol_readings'
        verbose_name_plural = 'Cholesterol Readings'
        verbose_name = 'Cholesterol Reading'
        ordering = ['reading_date']
    def __unicode__(self):
        return u'reading %s ' % (self.id, )

    def total_cholesterol(self):
        total_cholesterol = None
        if self.id:
            if self.hdl is not None and self.ldl is not None and self.triglycerides is not None:
                total_cholesterol = float(self.hdl) + float(self.ldl) + 0.2 * float(self.triglycerides)

        return total_cholesterol
        

class UserGlucoseGoal(models.Model):
    user = models.ForeignKey('auth.User', related_name="+")
    target_date = models.DateField(blank=True,null=True)
    random = models.FloatField()
    fasting = models.FloatField()
    interval_num = models.IntegerField(blank=True,default=0)
    interval_unit = models.CharField(max_length=6L, choices=INTERVAL_UNIT_CHOICES,blank=True, null=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')
    
    history = HistoricalRecords()

    class Meta:
        db_table = 'tbl_user_glucose_goals'
        verbose_name_plural = 'Glucose Goals'
        verbose_name = 'Glucose Goal'
    def __unicode__(self):
        return u'%s' % (self.id)
    def save(self, *args, **kwargs):
        if self.target_date is None:
            self.target_date = goal_get_target_date(self, *args, **kwargs)
        super(UserGlucoseGoal, self).save(*args, **kwargs)

class UserGlucoseReading(models.Model):
    user = models.ForeignKey('auth.User', related_name="+")
    random = models.FloatField(blank=True, null=True)
    fasting = models.FloatField(blank=True, null=True)
    reading_date = models.DateField()
    comment = models.TextField(blank=True, null=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')

    history = HistoricalRecords()

    class Meta:
        db_table = 'tbl_user_glucose_readings'
        verbose_name_plural = 'Glucose Readings'
        verbose_name = 'Glucose Reading'
        ordering = ['reading_date']
    def __unicode__(self):
        return u'reading %s ' % (self.id, )


#######################
########### DietTracker
#######################

class DietTracker(models.Model):
    MEAL_TYPE_CHOICES = (
        ('BREAKFAST','BREAKFAST'),
        ('LUNCH','LUNCH'),
        ('SNACKS','SNACKS'),
        ('DINNER','DINNER'),
    )
    user = models.ForeignKey('auth.User', related_name="+")
    food_item = models.ForeignKey('FoodItem', related_name = "+", blank=False)
    food_quantity_multiplier = models.IntegerField(blank=False)
    meal_type = models.CharField(max_length=18L, choices=MEAL_TYPE_CHOICES, db_index=True, blank=False)
    diet_date = models.DateField()
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')
    #status = models.CharField(max_length=18L, choices=GLOBAL_STATUS_CHOICES, default='ACTIVE', db_index=True)

    history = HistoricalRecords()

    class Meta:
        db_table = 'tbl_diet_tracker'
        verbose_name_plural = 'Diet Trackers'
        verbose_name = 'Diet Tracker'

    def __unicode__(self):
        return u'id=%s user=%s meal_type=%s food=%s' % (self.id, self.user.id, self.meal_type, self.food_item.name)

class FoodItem(models.Model):

    def get_display_image_path(self, filename): 
        return 'fooditems/display_image_'+hashlib.sha224(str(self.id)).hexdigest()+os.path.splitext(filename)[1]

    def display_image_url(self):
        if self.display_image:
            pic = 'media/'+str(self.display_image)
        else:
            pic = 'static/api/default_food_display_image_n.jpg'
        return s3_image_root + pic

    name = models.TextField(blank=False)
    display_image = models.ImageField(upload_to=get_display_image_path, blank=True, null=True)
    quantity = models.FloatField(blank=False)
    quantity_unit = models.TextField(blank=False)
    calories = models.FloatField(blank=True,default=0)
    total_fat = models.FloatField(blank=True,default=0)
    saturated_fat = models.FloatField(blank=True,default=0)
    polyunsaturated_fat  = models.FloatField(blank=True,default=0)
    monounsaturated_fat  = models.FloatField(blank=True,default=0)
    trans_fat     = models.FloatField(blank=True,default=0)
    cholesterol   = models.FloatField(blank=True,default=0)
    sodium    = models.FloatField(blank=True,default=0)
    potassium     = models.FloatField(blank=True,default=0)
    total_carbohydrates   = models.FloatField(blank=True,default=0)
    dietary_fiber     = models.FloatField(blank=True,default=0)
    sugars    = models.FloatField(blank=True,default=0)
    protein   = models.FloatField(blank=True,default=0)
    vitamin_a     = models.FloatField(blank=True,default=0)
    vitamin_c     = models.FloatField(blank=True,default=0)
    calcium   = models.FloatField(blank=True,default=0)
    iron  = models.FloatField(blank=True,default=0)

    calories_unit = models.CharField(max_length=64L,blank=True, null=True)
    total_fat_unit = models.CharField(max_length=64L,blank=True, null=True)
    saturated_fat_unit = models.CharField(max_length=64L,blank=True, null=True)
    polyunsaturated_fat_unit = models.CharField(max_length=64L,blank=True, null=True)
    monounsaturated_fat_unit = models.CharField(max_length=64L,blank=True, null=True)
    trans_fat_unit = models.CharField(max_length=64L,blank=True, null=True)
    cholesterol_unit = models.CharField(max_length=64L,blank=True, null=True)
    sodium_unit = models.CharField(max_length=64L,blank=True, null=True)
    potassium_unit = models.CharField(max_length=64L,blank=True, null=True)
    total_carbohydrates_unit = models.CharField(max_length=64L,blank=True, null=True)
    dietary_fiber_unit = models.CharField(max_length=64L,blank=True, null=True)
    sugars_unit = models.CharField(max_length=64L,blank=True, null=True)
    protein_unit = models.CharField(max_length=64L,blank=True, null=True)
    vitamin_a_unit = models.CharField(max_length=64L,blank=True, null=True)
    vitamin_c_unit = models.CharField(max_length=64L,blank=True, null=True)
    calcium_unit = models.CharField(max_length=64L,blank=True, null=True)
    iron_unit = models.CharField(max_length=64L,blank=True, null=True)
    created_at = models.DateTimeField(auto_now_add=True, null=True)
    updated_at = models.DateTimeField(auto_now=True, null=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')
    status = models.CharField(max_length=18L, choices=GLOBAL_STATUS_CHOICES, default='ACTIVE', db_index=True)
    class Meta:
        db_table = 'tbl_food_items'
        verbose_name_plural = 'Food Items'
        verbose_name = 'Food Item'
    def __unicode__(self):
        return u'%s' % self.name

class PhysicalActivity(models.Model):
    label = models.TextField()
    value = models.FloatField()

    history = HistoricalRecords()

    class Meta:
        db_table = 'tbl_physical_activities'

class UserPhysicalActivity(models.Model):
    TIME_SPENT_UNIT_CHOICES = (
        ('1','MINUTE'),
        ('2','HOUR'),
    )
    user = models.ForeignKey('auth.User', related_name="+")
    weight = models.FloatField()
    weight_measure = models.CharField(max_length=40L, choices=MEASURE_CHOICES, blank=True, default='METRIC',null=True)
    time_spent = models.CharField(max_length=40L)
    time_spent_unit = models.CharField(max_length=40L, choices=TIME_SPENT_UNIT_CHOICES, blank=True, default='MINUTE',null=True)
    physical_activity = models.ForeignKey('PhysicalActivity', related_name="+")
    user_calories_spent = models.CharField(max_length=40L,blank=True,null=True)

    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')
    
    history = HistoricalRecords()


    class Meta:
        db_table = 'tbl_user_physical_activities'

User.profile = property(lambda u: UserProfile.objects.get_or_create(user=u)[0])
