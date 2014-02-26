from __future__ import unicode_literals
from django.db import models
import  pprint, datetime
from simple_history.models import HistoricalRecords
from api.models import ApiModel, StaticApiModel



class Immunization(StaticApiModel):
    label = models.TextField(blank=False)
    recommended_age = models.IntegerField(blank=False,db_index=True)

    history = HistoricalRecords()

    class Meta:
        db_table = 'tbl_immunizations'

    def __unicode__(self):
        return u'Id %s Label: %s' % (self.id, self.label)

class UserImmunization(ApiModel):
	immunization = models.ForeignKey('Immunization', related_name="+")
	user = models.ForeignKey('auth.User', related_name="+")
	is_completed = models.BooleanField(default=False,db_index=True)

	history = HistoricalRecords()

	class Meta:
		db_table = 'tbl_user_immunizations'

	def __unicode__(self):
		return u'Immunization: %s User: %s' % (self.immunization.label, self.user.id)
