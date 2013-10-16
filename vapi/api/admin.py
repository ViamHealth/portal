from django.contrib import admin
from api.models import *

admin.site.register(UserProfile)
admin.site.register(Reminder)
admin.site.register(UserGroupSet)
admin.site.register(UserBmiProfile)
admin.site.register(HealthfileTag)
admin.site.register(Healthfile)
admin.site.register(UserWeightGoal)
admin.site.register(UserWeightReading)
admin.site.register(UserBloodPressureGoal)
admin.site.register(UserBloodPressureReading)
admin.site.register(UserCholesterolGoal)
admin.site.register(UserCholesterolReading)
admin.site.register(UserGlucoseGoal)
admin.site.register(UserGlucoseReading)
admin.site.register(FoodItem)
admin.site.register(DietTracker)
admin.site.register(PhysicalActivity)
admin.site.register(UserPhysicalActivity)








