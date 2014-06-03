from __future__ import absolute_import

import os
from celery import Celery

from django.conf import settings


# set the default Django settings module for the 'celery' program.
os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'vapi.settings')
app = Celery('vapi')

# Using a string here means the worker will not have to
# pickle the object when using Windows.
app.config_from_object('django.conf:settings')
app.autodiscover_tasks(lambda: settings.INSTALLED_APPS)

from celery import task
import datetime
from celery.decorators import periodic_task
from celery.schedules import crontab

@app.task(bind=True)
def debug_task(self):
    print('Request: {0!r}'.format(self.request))

@periodic_task(run_every=crontab(hour=7, minute=20))
def refresh_tokens():
	from django.db import models
	from django.contrib.auth.models import User
	from rest_framework.authtoken.models import Token

	enddate = datetime.datetime.today() - datetime.timedelta(days=30)

	Token.objects.filter(created__lt=enddate).delete()
	for user in User.objects.all():
		Token.objects.get_or_create(user=user)

	return 'Token Refresh Complete'

@periodic_task(run_every=crontab(hour=0, minute=30))
def set_user_personalities():
	from django.contrib.auth.models import User
	from api.models import Personality, UserPersonalityMap
	from django.db.models import Q

	cronUser = User.objects.get(username='superadmin')

	updatedbydatetime = datetime.datetime.today() - datetime.timedelta(minutes=25)
	years25ago = datetime.datetime.today() - datetime.timedelta(years=25)
	

	personalities = Personality.objects.all()
	for personality in personalities:
		insertData = []

		if personality.pid == '1':
			#Female age more than 25
			#userPersonalityMap = UserPersonalityMap.objects.filter(~Q(personality = personality), user__userprofile__gender='FEMALE')
			users = User.objects.filter(
				user__userprofile__gender='FEMALE',
				user__userprofile__date_of_birth__lt=years25ago,
				user__userprofile__updated_at__gt=updatedbydatetime )

		elif personality.pid == '2':
			#Male age more than 25
			#userPersonalityMap = UserPersonalityMap.objects.filter(~Q(personality = personality), user__userprofile__gender='FEMALE')
			users = User.objects.filter(
				user__userprofile__gender='MALE',
				user__userprofile__date_of_birth__lt=years25ago,
				user__userprofile__updated_at__gt=updatedbydatetime )

		elif personality.pid == '3':
			#Female age less than 25
			#userPersonalityMap = UserPersonalityMap.objects.filter(~Q(personality = personality), user__date_joined__gt=enddate)
			users = User.objects.filter(
				user__userprofile__gender='FEMALE',
				user__userprofile__date_of_birth__gt=years25ago,
				user__userprofile__updated_at__gt=updatedbydatetime )

		elif personality.pid == '4':
			#Male age less than 25
			#userPersonalityMap = UserPersonalityMap.objects.filter(~Q(personality = personality), user__date_joined__gt=enddate)
			users = User.objects.filter(
				user__userprofile__gender='MALE',
				user__userprofile__date_of_birth__gt=years25ago,
				user__userprofile__updated_at__gt=updatedbydatetime )

		taskPersonalityMap = TaskPersonalityMap.objects.filter(personality=personality).distinct('task')

		if taskPersonalityMap:
			for taskPersonality in taskPersonalityMap:
				for user in users:
					userTasks = UserTask.objects.filter(task=taskPersonality.task,user=user)
					if not userTasks:
						insertData.append(userTask(user=user, task=taskPersonality.task,udated_by=cronUser))
			if insertData.len():
				UserTask.objects.bulk_create(insertData)


		"""
		if userPersonalityMap:
				for userPersonality in userPersonalityMap:
					insertData.append(userPersonalityMap(user=userPersonality.user, personality= personality,))

				if insertData.len():
					userPersonalityMap.objects.bulk_create(insertData)
		"""

	return 'Done'

"""
@periodic_task(run_every=crontab(minute=2))
def set_user_tasks():
	from django.contrib.auth.models import User
	from api.models import *
	
	enddate = datetime.datetime.today() - datetime.timedelta(hours=1)
	cronUser = User.objects.get(username='superadmin')

	tpm = TaskPersonalityMap.objects.select_related('task').filter(updated_at__gt=enddate)

	if tpm:
		user_ids = []
		for tpm_obj in tpm:
			upm = UserPersonalityMap.objects.filter(personality=tpm.personality,updated_at__gt=enddate)
			if upm:
				for upm_obj in upm:
					try:
						uts = UserTask.objects.get(user_id=upm_obj.user_id,task_id=tpm_obj.task_id)
					except UserTask.DoesNotExist:
						user_ids.append(UserTask(user=upm_obj.user,task=tpm_obj.task,updated_by=cronUser))

		UserTask.objects.bulk_create(user_ids)

	return 'Done'
"""
