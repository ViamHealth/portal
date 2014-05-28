from __future__ import unicode_literals
from django.db import models
from simple_history.models import HistoricalRecords
from api.models import ApiModel, StaticApiModel
from api.util.enums import GENDER_CHOICES


#16327222

class TrackGrowthAdvancedData(StaticApiModel):
    label = models.TextField(blank=True)
    gender = models.CharField(max_length=18L, choices=GENDER_CHOICES, blank=False, db_index=True)
    #days
    age = models.IntegerField(blank=False,db_index=True)

    height_3n = models.FloatField(blank=False)
    weight_3n = models.FloatField(blank=False)
    height_2n = models.FloatField(blank=False)
    weight_2n = models.FloatField(blank=False)
    height_1n = models.FloatField(blank=False)
    weight_1n = models.FloatField(blank=False)
    height_0 = models.FloatField(blank=False)
    weight_0 = models.FloatField(blank=False)
    height_1 = models.FloatField(blank=False)
    weight_1 = models.FloatField(blank=False)
    height_2 = models.FloatField(blank=False)
    weight_2 = models.FloatField(blank=False)
    height_3 = models.FloatField(blank=False)
    weight_3 = models.FloatField(blank=False)

    history = HistoricalRecords()

    class Meta:
        db_table = 'tbl_track_growth_adv_data'

    def __unicode__(self):
        return u'Id %s Label: %s Gender %s Age %s' % (self.id, self.label, self.gender, self.age)    

class TrackGrowthData(StaticApiModel):
    label = models.TextField(blank=False)
    gender = models.CharField(max_length=18L, choices=GENDER_CHOICES, blank=False)
    #weeks
    age = models.IntegerField(blank=False,db_index=True)
    #cm
    height = models.FloatField(blank=False,db_index=True)
    #kg
    weight = models.FloatField(blank=False,db_index=True)
    
    history = HistoricalRecords()

    class Meta:
        db_table = 'tbl_track_growth_data'

    def __unicode__(self):
        return u'Id %s Label: %s Gender %s Age %s' % (self.id, self.label, self.gender, self.age)

class UserTrackGrowthData(ApiModel):
    user = models.ForeignKey('auth.User', related_name="+")
    entry_date = models.DateField(blank=False,null=False)
    #cm
    height = models.FloatField(blank=True,null=True)
    #kg
    weight = models.FloatField(blank=True,null=True)

    history = HistoricalRecords()

    class Meta:
        db_table = 'tbl_user_track_growth_data'

    def __unicode__(self):
        return u'Id: %s User: %s Date %s' % (self.id, self.user.id, self.entry_date)
