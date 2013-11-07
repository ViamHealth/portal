from __future__ import unicode_literals

from django.db import models
import datetime
from dateutil.relativedelta import relativedelta
from simple_history.models import HistoricalRecords

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
