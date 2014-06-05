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

def f7(seq):
    seen = set()
    seen_add = seen.add
    return [ x for x in seq if x not in seen and not seen_add(x)]

@periodic_task(run_every=crontab(minute='*/1'))
def set_user_personalities():
	from django.contrib.auth.models import User
	from api.users.models import UserGroupSet
	from api.tasks.models import Personality, TaskPersonalityMap, UserTask
	from django.db.models import Q
	from dateutil.relativedelta import relativedelta

	cronUser = User.objects.get(username='superadmin')

	updatedbydatetime = datetime.datetime.today() - datetime.timedelta(minutes=2)
	years25ago = datetime.datetime.now() - relativedelta(years=25)
	

	personalities = Personality.objects.all()
	users = []

	for personality in personalities:
		insertData = []

		if personality.pid == 1:
			#Female age more than 25
			#userPersonalityMap = UserPersonalityMap.objects.filter(~Q(personality = personality), user__userprofile__gender='FEMALE')
			users = User.objects.filter(
				userprofile__gender='FEMALE',
				userprofile__date_of_birth__lt=years25ago,
				userprofile__updated_at__gt=updatedbydatetime )

		elif personality.pid == 2:
			#Male age more than 25
			#userPersonalityMap = UserPersonalityMap.objects.filter(~Q(personality = personality), user__userprofile__gender='FEMALE')
			users = User.objects.filter(
				userprofile__gender='MALE',
				userprofile__date_of_birth__lt=years25ago,
				userprofile__updated_at__gt=updatedbydatetime )

		elif personality.pid == 3:
			#Female age less than 25
			#userPersonalityMap = UserPersonalityMap.objects.filter(~Q(personality = personality), user__date_joined__gt=enddate)
			users = User.objects.filter(
				userprofile__gender='FEMALE',
				userprofile__date_of_birth__gt=years25ago,
				userprofile__updated_at__gt=updatedbydatetime )

		elif personality.pid == 4:
			#Male age less than 25
			#userPersonalityMap = UserPersonalityMap.objects.filter(~Q(personality = personality), user__date_joined__gt=enddate)
			users = User.objects.filter(
				userprofile__gender='MALE',
				userprofile__date_of_birth__gt=years25ago,
				userprofile__updated_at__gt=updatedbydatetime )

		elif personality.pid == 5:
			#All
			users = User.objects.filter(
				userprofile__updated_at__gt=updatedbydatetime )

		elif personality.pid == 6:
			#Have family member less than 10 in group set
			years10ago = datetime.datetime.now() - relativedelta(years=10)

			usergroupset = UserGroupSet.objects.filter(
				Q(group__userprofile__date_of_birth__gt=years10ago,
					group__userprofile__updated_at__gt=updatedbydatetime) | 
				Q(user__userprofile__date_of_birth__gt=years10ago,
					user__userprofile__updated_at__gt=updatedbydatetime) ).filter(is_deleted=False,status='ACTIVE')
			users_list = []
			users_list = list(set(users_list))

			for usergs in usergroupset:
				users_list = [usergs.group] + users_list
				users_list = [usergs.user] + users_list
			
			users_unique_list = f7(users_list)

                        if users_unique_list:
        			users = User.objects.filter(pk__in=[usera.id for usera in users_unique_list]).exclude(userprofile__date_of_birth__gt=years10ago)

		elif personality.pid == 7:
			#Have family member or self with diabetes
			uct_list = []
			uct_list = list(set(uct_list))

			uct = UserConditionTemp.objects.only('user').filter(
				condition='cholesterol',
				user__userprofile__updated_at__gt=updatedbydatetime,
				user__is_active=True)

			for ucta in uct:
				uct_list = [ucta.user.id] + uct_list

			if uct_list:
				usergroupset = UserGroupSet.objects.filter(
					Q(group__in=uct_list) | 
					Q(user__in=uct_list) ).filter(is_deleted=False,status='ACTIVE')

				users_list = []
				users_list = list(set(users_list))

				for usergs in usergroupset:
					users_list = [usergs.group.id] + users_list
					users_list = [usergs.user.id] + users_list
				
				users_unique_list = f7(users_list)
				if users_unique_list:
					users = User.objects.filter(pk__in=users_list)



		taskPersonalityMap = TaskPersonalityMap.objects.filter(personality=personality)

		if taskPersonalityMap:
			for taskPersonality in taskPersonalityMap:
				for user in users:
					userTasks = UserTask.objects.filter(task=taskPersonality.task,user=user)
					if not userTasks:
						insertData.append(UserTask(user=user, task=taskPersonality.task,updated_by=cronUser))

			if len(insertData):
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
