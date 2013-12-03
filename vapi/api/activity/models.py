from __future__ import unicode_literals
from django.db import models
import  pprint, datetime
from simple_history.models import HistoricalRecords


MEASURE_CHOICES = (
        ('METRIC','METRIC'),
        ('STANDARD','STANDARD')
)


class PhysicalActivity(models.Model):
    label = models.TextField()
    value = models.FloatField()

    history = HistoricalRecords()

    class Meta:
        db_table = 'tbl_physical_activities'

    def __unicode__(self):
        return u'Id %s Label: %s' % (self.id, self.label)

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
    activity_date = models.DateField(null=True,blank=True)

    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')
    
    history = HistoricalRecords()

    def save(self, *args, **kwargs):
        if not self.id:
            if self.activity_date is None:
                self.activity_date = datetime.date.today()
        super(UserPhysicalActivity, self).save(*args, **kwargs)

    class Meta:
        db_table = 'tbl_user_physical_activities'