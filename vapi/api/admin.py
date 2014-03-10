from django.contrib import admin
from simple_history.admin import SimpleHistoryAdmin
from api.models import *
from api.goals.models import *
from api.users.models import *
from api.activity.models import *
from api.diet.models import *
from api.healthfiles.models import *
from api.reminders.models import *
from api.immunizations.models import *
from api.trackgrowth.models import *

USER_ADMIN_LINK = "/admin/auth/user/"
def USER_DISPLAY_STRING(obj):
	
	if obj.email is not None:
		display_string =  obj.email
	if obj.first_name is not None:
		display_string =  obj.first_name
	if len(display_string) == 0:
		display_string = obj.username
	return display_string

def USER_LINK(obj):
	return u'<a href="'+ USER_ADMIN_LINK +'%s/">%s</a>' % (obj.id,USER_DISPLAY_STRING(obj))

class UserProfileAdmin(admin.ModelAdmin):
    list_display = ['fb_profile_id','user_link', 'blood_group','gender','date_of_birth','mobile','created_at','updated_at','updated_by']
    list_filter = [ 'blood_group','gender','created_at','updated_at']

    def user_link(self,obj):
    	return USER_LINK(obj.user)
    user_link.allow_tags = True
    user_link.short_description = "User"
    user_link.admin_order_field  = 'user__id'

class UserGroupSetAdmin(admin.ModelAdmin):
    list_display = ['id','group_link','user_link', 'status','created_at','updated_at','updated_by','is_deleted']
    list_filter = ['status','created_at','updated_at','is_deleted']

    def group_link(self,obj):
    	return USER_LINK(obj.group)
    group_link.allow_tags = True
    group_link.short_description = "Connector"
    group_link.admin_order_field  = 'group__id'

    def user_link(self,obj):
    	return USER_LINK(obj.user)
    user_link.allow_tags = True
    user_link.short_description = "User"
    user_link.admin_order_field  = 'user__id'

class UserBmiProfileAdmin(admin.ModelAdmin):
    list_display = [
		'user_link', 
		'height' , 
		'weight' ,
		'lifestyle',
		'systolic_pressure',
		'diastolic_pressure',
		'pulse_rate',
		'random',
		'fasting',
		'hdl',
		'ldl',
		'triglycerides',
		'total_cholesterol',
    ]
    list_filter = [ 'created_at','updated_at']

    def user_link(self,obj):
    	return USER_LINK(obj.user)
    user_link.allow_tags = True
    user_link.short_description = "User"
    user_link.admin_order_field  = 'user__id'

class ReminderAdmin(admin.ModelAdmin):
    list_display = ['name','user_link', 'type','details','morning_count','afternoon_count','evening_count','night_count','start_date','end_date','repeat_mode','repeat_day','repeat_hour','repeat_min','repeat_weekday','repeat_every_x','repeat_i_counter','created_at','updated_at','updated_by','is_deleted']
    list_filter = [ 'type','repeat_mode','repeat_i_counter','created_at','updated_at','is_deleted']
    search_fields = ['name']

    def user_link(self,obj):
    	return USER_LINK(obj.user)
    user_link.allow_tags = True
    user_link.short_description = "User"
    user_link.admin_order_field  = 'user__id'


class HealthfileAdmin(admin.ModelAdmin):
    list_display = ['name','user_link', 'description','mime_type','file','created_at','updated_at','updated_by','is_deleted']
    list_filter = ['mime_type','created_at','updated_at','is_deleted']
    search_fields = ['name','description']

    def user_link(self,obj):
    	return USER_LINK(obj.user)
    user_link.allow_tags = True
    user_link.short_description = "User"
    user_link.admin_order_field  = 'user__id'

class WeightGoalAdmin(admin.ModelAdmin):
    list_display = ['weight','user_link', 'target_date','created_at','updated_at','updated_by','is_deleted']
    list_filter = ['target_date','created_at','updated_at','is_deleted']

    def user_link(self,obj):
    	return USER_LINK(obj.user)
    user_link.allow_tags = True
    user_link.short_description = "User"
    user_link.admin_order_field  = 'user__id'

class WeightReadingAdmin(admin.ModelAdmin):
    list_display = ['weight','user_link', 'reading_date','created_at','updated_at','updated_by','is_deleted']
    list_filter = ['reading_date','created_at','updated_at','is_deleted']

    def user_link(self,obj):
    	return USER_LINK(obj.user)
    user_link.allow_tags = True
    user_link.short_description = "User"
    user_link.admin_order_field  = 'user__id'
 
class BPGoalAdmin(admin.ModelAdmin):
    list_display = ['systolic_pressure','user_link','diastolic_pressure','pulse_rate', 'target_date','created_at','updated_at','updated_by','is_deleted']
    list_filter = ['target_date','created_at','updated_at','is_deleted']

    def user_link(self,obj):
    	return USER_LINK(obj.user)
    user_link.allow_tags = True
    user_link.short_description = "User"
    user_link.admin_order_field  = 'user__id'

class BPReadingAdmin(admin.ModelAdmin):
    list_display = ['systolic_pressure','user_link','diastolic_pressure','pulse_rate', 'reading_date','created_at','updated_at','updated_by','is_deleted']
    list_filter = ['reading_date','created_at','updated_at','is_deleted']

    def user_link(self,obj):
    	return USER_LINK(obj.user)
    user_link.allow_tags = True
    user_link.short_description = "User"
    user_link.admin_order_field  = 'user__id'

class CholesterolGoalAdmin(admin.ModelAdmin):
    list_display = ['hdl','user_link','ldl','triglycerides', 'target_date','created_at','updated_at','updated_by','is_deleted']
    list_filter = ['target_date','created_at','updated_at','is_deleted']

    def user_link(self,obj):
    	return USER_LINK(obj.user)
    user_link.allow_tags = True
    user_link.short_description = "User"
    user_link.admin_order_field  = 'user__id'

class CholesterolReadingAdmin(admin.ModelAdmin):
    list_display = ['hdl','user_link','ldl','triglycerides', 'reading_date','created_at','updated_at','updated_by','is_deleted']
    list_filter = ['reading_date','created_at','updated_at','is_deleted']

    def user_link(self,obj):
    	return USER_LINK(obj.user)
    user_link.allow_tags = True
    user_link.short_description = "User"
    user_link.admin_order_field  = 'user__id'


class GlucoseGoalAdmin(admin.ModelAdmin):
    list_display = ['random','user_link','fasting', 'target_date','created_at','updated_at','updated_by','is_deleted']
    list_filter = ['target_date','created_at','updated_at','is_deleted']

    def user_link(self,obj):
    	return USER_LINK(obj.user)
    user_link.allow_tags = True
    user_link.short_description = "User"
    user_link.admin_order_field  = 'user__id'

class GlucoseReadingAdmin(admin.ModelAdmin):
    list_display = ['random','user_link','fasting', 'reading_date','created_at','updated_at','updated_by','is_deleted']
    list_filter = ['reading_date','created_at','updated_at','is_deleted']

    def user_link(self,obj):
    	return USER_LINK(obj.user)
    user_link.allow_tags = True
    user_link.short_description = "User"
    user_link.admin_order_field  = 'user__id'

class ImmunizationAdmin(admin.ModelAdmin):
    list_display = [
        'label', 
        'recommended_age' , 
    ]
    list_filter = [ 'recommended_age','created_at','updated_at']

class UserImmunizationAdmin(admin.ModelAdmin):
    list_display = [
        'id', 
        'immunization_link' ,
        'user_link',
        'is_completed'
    ]
    list_filter = [ 'is_completed','created_at','updated_at']

    def user_link(self,obj):
        return USER_LINK(obj.user)
    def immunization_link(self,obj):
        return u'<a href="/admin/immunizations/immunization/%s/">%s</a>' % (obj.immunization.id,obj.immunization.label)

    immunization_link.allow_tags = True
    immunization_link.short_description = "Immunization"
    immunization_link.admin_order_field  = 'user__id'
    user_link.allow_tags = True
    user_link.short_description = "User"
    user_link.admin_order_field  = 'user__id'


class TrackGrowthDataAdmin(admin.ModelAdmin):
    list_display = [
        'label', 
        'gender', 
        'age',
        'height',
        'weight',
    ]
    list_filter = [ 'age','created_at','updated_at']

class UserTrackGrowthDataAdmin(admin.ModelAdmin):
    list_display = ['id','user_link','entry_date', 'height','weight','created_at','updated_at','updated_by','is_deleted']
    list_filter = ['entry_date','created_at','updated_at','is_deleted']

    def user_link(self,obj):
        return USER_LINK(obj.user)
    user_link.allow_tags = True
    user_link.short_description = "User"
    user_link.admin_order_field  = 'user__id'



admin.site.register(UserProfile, UserProfileAdmin)
admin.site.register(Reminder, ReminderAdmin)
admin.site.register(UserGroupSet, UserGroupSetAdmin)
admin.site.register(UserBmiProfile, UserBmiProfileAdmin)
admin.site.register(Healthfile, HealthfileAdmin)

admin.site.register(UserWeightGoal, WeightGoalAdmin)
admin.site.register(UserWeightReading, WeightReadingAdmin)
admin.site.register(UserBloodPressureGoal, BPGoalAdmin)
admin.site.register(UserBloodPressureReading, BPReadingAdmin)
admin.site.register(UserCholesterolGoal, CholesterolGoalAdmin)
admin.site.register(UserCholesterolReading, CholesterolReadingAdmin)
admin.site.register(UserGlucoseGoal, GlucoseGoalAdmin)
admin.site.register(UserGlucoseReading, GlucoseReadingAdmin)
#admin.site.register(FoodItem)
#admin.site.register(DietTracker, SimpleHistoryAdmin)
#admin.site.register(PhysicalActivity)
#admin.site.register(UserPhysicalActivity, SimpleHistoryAdmin)

admin.site.register(Immunization, ImmunizationAdmin)
admin.site.register(UserImmunization, UserImmunizationAdmin)

admin.site.register(TrackGrowthData, TrackGrowthDataAdmin)
admin.site.register(UserTrackGrowthData, UserTrackGrowthDataAdmin)
