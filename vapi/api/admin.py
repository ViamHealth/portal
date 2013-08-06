from django.contrib import admin
from api.models import *

admin.site.register(UserProfile)
admin.site.register(HealthfileTag)
admin.site.register(Healthfile)
#admin.site.register(UserWeightGoal)
#admin.site.register(UserWeightReading)