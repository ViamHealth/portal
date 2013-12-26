from django.contrib import admin
from simple_history.admin import SimpleHistoryAdmin
from api.models import *
from api.goals.models import *
from api.users.models import *
from api.activity.models import *
from api.diet.models import *
from api.healthfiles.models import *
from api.reminders.models import *

admin.site.register(UserProfile, SimpleHistoryAdmin)
admin.site.register(Reminder, SimpleHistoryAdmin)
admin.site.register(UserGroupSet, SimpleHistoryAdmin)
admin.site.register(UserBmiProfile, SimpleHistoryAdmin)
admin.site.register(HealthfileTag, SimpleHistoryAdmin)
admin.site.register(Healthfile, SimpleHistoryAdmin)
admin.site.register(UserWeightGoal, SimpleHistoryAdmin)
admin.site.register(UserWeightReading, SimpleHistoryAdmin)
admin.site.register(UserBloodPressureGoal, SimpleHistoryAdmin)
admin.site.register(UserBloodPressureReading, SimpleHistoryAdmin)
admin.site.register(UserCholesterolGoal, SimpleHistoryAdmin)
admin.site.register(UserCholesterolReading, SimpleHistoryAdmin)
admin.site.register(UserGlucoseGoal, SimpleHistoryAdmin)
admin.site.register(UserGlucoseReading, SimpleHistoryAdmin)
admin.site.register(FoodItem)
admin.site.register(DietTracker, SimpleHistoryAdmin)
admin.site.register(PhysicalActivity)
admin.site.register(UserPhysicalActivity, SimpleHistoryAdmin)








