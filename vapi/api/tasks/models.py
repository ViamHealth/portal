from __future__ import unicode_literals

from django.db import models
from simple_history.models import HistoricalRecords
from api.models import ApiModel, StaticApiModel

SET_CHOICE_CHOICES = (
		('0','0'),
        ('1','1'),
        ('2','2')
    )

class TaskChoiceFeedback(models.Model):
	feedback = models.CharField(max_length=256L,blank=False, null=False)

	history = HistoricalRecords()

	def __unicode__(self):
		return u'%s' % (self.feedback)


class Task(models.Model):
	message = models.CharField(max_length=256L,blank=False, null=False)
	label_choice_1 = models.CharField(max_length=32L,blank=True, null=True)
	choice_1_message = models.ForeignKey('TaskChoiceFeedback', related_name="+", blank=True, null=True, on_delete=models.PROTECT)
	label_choice_2 = models.CharField(max_length=32L,blank=True, null=True)
	choice_2_message = models.ForeignKey('TaskChoiceFeedback', related_name="+", blank=True, null=True, on_delete=models.PROTECT)
	

	history = HistoricalRecords()

	def __unicode__(self):
		return u'%s' % (self.message)


class UserTask(ApiModel):
	task = models.ForeignKey('Task', related_name="+", on_delete=models.CASCADE)
	user = models.ForeignKey('auth.User', related_name="+")
	weight = models.PositiveIntegerField(default=0)
	set_choice = models.CharField(max_length=1L, choices=SET_CHOICE_CHOICES, default='0',null=False)

	history = HistoricalRecords()

	def __unicode__(self):
		return u'%s - %s' % (self.user, self.task)

class Personality(models.Model):
	label = models.CharField(max_length=256L,null=False,blank=False)
	pid = models.PositiveIntegerField()

	history = HistoricalRecords()

class TaskPersonalityMap(StaticApiModel):
	task = models.ForeignKey('Task', related_name="+")
	personality = models.ForeignKey('Personality', related_name="+")

	history = HistoricalRecords()
"""
class UserPersonalityMap(StaticApiModel):
	personality = models.ForeignKey('Personality', related_name="+")
	user = models.ForeignKey('auth.User', related_name="+")

	history = HistoricalRecords()
"""