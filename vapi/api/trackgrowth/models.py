from __future__ import unicode_literals
from django.db import models
from simple_history.models import HistoricalRecords
from api.models import ApiModel, StaticApiModel
from api.util.enums import GENDER_CHOICES


class TrackGrowthData(StaticApiModel):
    label = models.TextField(blank=False)
    gender = models.CharField(max_length=18L, choices=GENDER_CHOICES, blank=False)
    #days
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
    #months
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
