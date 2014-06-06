from __future__ import unicode_literals

from django.db import models
from simple_history.models import HistoricalRecords
from api.models import ApiModel, StaticApiModel
#from api.goals.models import UserBloodPressureReading

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

	TASK_TYPE_CHOICES = (
        ('1','TEXT_WITH_BUTTONS'),
        ('2','BP_INPUT_FORM')
    )

	message = models.CharField(max_length=256L,blank=False, null=False)
	label_choice_1 = models.CharField(max_length=32L,blank=True, null=True)
	choice_1_message = models.ForeignKey('TaskChoiceFeedback', related_name="+", blank=True, null=True, on_delete=models.PROTECT)
	label_choice_2 = models.CharField(max_length=32L,blank=True, null=True)
	choice_2_message = models.ForeignKey('TaskChoiceFeedback', related_name="+", blank=True, null=True, on_delete=models.PROTECT)
	task_type = models.CharField(max_length=64L, choices=TASK_TYPE_CHOICES, default='1', null=False, blank=False)
	

	history = HistoricalRecords()

	def __unicode__(self):
		return u'%s' % (self.message)



class UserTask(ApiModel):
	task = models.ForeignKey('Task', related_name="+", on_delete=models.CASCADE)
	user = models.ForeignKey('auth.User', related_name="+")
	weight = models.PositiveIntegerField(default=0)
	set_choice = models.CharField(max_length=1L, choices=SET_CHOICE_CHOICES, default='0',null=False)
	blood_pressure_reading = models.ForeignKey('goals.UserBloodPressureReading', related_name="+", blank=True, null=True)

	history = HistoricalRecords()

	def __unicode__(self):
		return u'%s - %s' % (self.user, self.task)

class Personality(models.Model):
	label = models.CharField(max_length=256L,null=False,blank=False)
	pid = models.PositiveIntegerField()

	history = HistoricalRecords()

	class Meta:
		verbose_name_plural = 'Personalities'
		verbose_name = 'Personality'

	def __unicode__(self):
		return u'%s' % (self.label)

class TaskPersonalityMap(StaticApiModel):
	task = models.ForeignKey('Task', related_name="+")
	personality = models.ForeignKey('Personality', related_name="+")

	history = HistoricalRecords()

	class Meta:
		verbose_name_plural = 'TaskPersonalityMaps'
		verbose_name = 'TaskPersonalityMap'
        
	def __unicode__(self):
		return u'%s - %s' % (self.personality, self.task)
"""
class UserPersonalityMap(StaticApiModel):
	personality = models.ForeignKey('Personality', related_name="+")
	user = models.ForeignKey('auth.User', related_name="+")

	history = HistoricalRecords()
"""