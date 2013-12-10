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
