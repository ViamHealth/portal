from __future__ import unicode_literals

from django.db import models
from simple_history.models import HistoricalRecords
from api.models import ApiModel, StaticApiModel

class UserConditionTemp(models.Model):
	user = models.ForeignKey('auth.User', related_name="+")
	condition = models.CharField(max_length=256L,blank=False, null=False)

	history = HistoricalRecords()

	class Meta:
		verbose_name = 'User Condition'
		verbose_name_plural = 'User Conditions'

	def __unicode__(self):
		return u'%s - %s' % (self.user.first_name, self.condition)


