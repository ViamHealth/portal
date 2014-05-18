from django.conf.urls import patterns, include, url
from rest_framework import routers
from api.views import *
from api.goals.views import *
from api.activity.views import *
from api.diet.views import *
from api.healthfiles.views import *
from api.reminders.views import *
from api.users.views import *
from api.immunizations.views import *
from api.trackgrowth.views import *
from api.tasks.views import *
#from api.watchdog.views import *

from django.views.decorators.csrf import csrf_exempt
#from allauth.socialaccount.providers.facebook.views import login_by_token

#from api import ote

router = routers.DefaultRouter(trailing_slash=True)
router.register(r'healthfiles', HealthfileViewSet)
router.register(r'weight-goals', UserWeightGoalViewSet)
router.register(r'user-physical-activity', UserPhysicalActivityViewSet)
router.register(r'physical-activity', PhysicalActivityViewSet)
router.register(r'blood-pressure-goals', UserBloodPressureGoalViewSet)
router.register(r'cholesterol-goals', UserCholesterolGoalViewSet)
router.register(r'glucose-goals', UserGlucoseGoalViewSet)
router.register(r'diet-tracker', DietTrackerViewSet)
router.register(r'user-immunizations', UserImmunizationViewSet)
router.register(r'user-track-growth', UserTrackGrowthDataViewSet)

#router.register(r'food-items', FoodItemViewSet)

#router.register(r'goals', GoalViewSet)


# Uncomment the next two lines to enable the admin:
from django.contrib import admin
admin.autodiscover()

obtain_auth_token = ObtainAuthToken.as_view()

urlpatterns = patterns('',
    url(r'^heartbeat/$', heartbeat, name='heartbeat'),
    # Examples:
    # url(r'^$', 'vapi.home', name='home'),
    #url(r'^', include('api.urls')),
    
    

    #url(r'^account/facebook/login/token/$', csrf_exempt(login_by_token),name="facebook_login_by_token"),
    #url(r'^account/', include('allauth.urls')),

    url(r'^api-auth/', include('rest_framework.urls', namespace='rest_framework')),
    url(r'^api-token-auth/', obtain_auth_token),
    #url(r'^social-login-success/', SocialLoginCallback.as_view()),
    
    #url(r'^api-token-auth/', 'rest_framework.authtoken.obtain_auth_token'),

    url(r'^$', api_root),
    url(r'^', include(router.urls)),

    url(r'^signup/$', SignupView.as_view({'post': 'user_signup'}), name='user-signup'),
    url(r'^invite/$', InviteView.as_view({'post': 'user_invite'}), name='user-invite'),
    url(r'^share/$', ShareView.as_view({'post': 'user_share'}), name='user-share'),

    url(r'^forgot-password-email/$', ForgotPasswordView.as_view({'post': 'forgot_password_email'}), name='forgot-password-email'),
    url(r'^forgot-password-mobile/$', ForgotPasswordView.as_view({'post': 'forgot_password_mobile'}), name='forgot-password-mobile'),

    url(r'^users/$', UserView.as_view({'get': 'list','post': 'create'}),name='user-list'),
    url(r'^users/me/$', UserView.as_view({'get': 'current_user'}), name='user-me'),
    url(r'^users/attach-facebook/$', UserView.as_view({'post': 'attach_facebook'}), name='user-me'),

    url(r'^reminders/$', ReminderViewSet.as_view({'get':'list','post':'create'}),name='reminder-list'),
    url(r'^reminders/(?P<pk>[0-9]+)/$', ReminderViewSet.as_view({'get':'retrieve','put':'update','delete':'destroy'}),name='reminder-detail'),
    url(r'^reminders/(?P<pk>[0-9]+)/end/$', ReminderViewSet.as_view({'post':'end_from_today'}),name='reminder-end'),

    url(r'^reminderreadings/$', ReminderReadingsViewSet.as_view({'get':'list'}),name='reminderreadings-list'),
    url(r'^reminderreadings/(?P<pk>[0-9]+)/$', ReminderReadingsViewSet.as_view({'get':'retrieve','put':'update'}),name='reminderreadings-detail'),

    url(r'^weight-readings/$', WeightReadingViewSet.as_view({'get':'list','post':'create'}),name='weight-readings'),
    url(r'^weight-readings/(?P<reading_date>[0-9-]+)/$', WeightReadingViewSet.as_view({'get':'retrieve','delete':'destroy','put':'update'}),name='weight-reading-detail'),
    url(r'^blood-pressure-readings/$', BloodPressureReadingViewSet.as_view({'get':'list','post':'create'}),name='blood-pressure-readings'),
    url(r'^blood-pressure-readings/(?P<reading_date>[0-9-]+)/$', BloodPressureReadingViewSet.as_view({'get':'retrieve','delete':'destroy','put':'update'}),name='blood-pressure-reading-detail'),
    url(r'^cholesterol-readings/$', CholesterolReadingViewSet.as_view({'get':'list','post':'create'}),name='cholesterol-readings'),
    url(r'^cholesterol-readings/(?P<reading_date>[0-9-]+)/$', CholesterolReadingViewSet.as_view({'get':'retrieve','delete':'destroy','put':'update'}),name='cholesterol-reading-detail'),
    url(r'^glucose-readings/$', GlucoseReadingViewSet.as_view({'get':'list','post':'create'}),name='glucose-readings'),
    url(r'^glucose-readings/(?P<reading_date>[0-9-]+)/$', GlucoseReadingViewSet.as_view({'get':'retrieve','delete':'destroy','put':'update'}),name='glucose-reading-detail'),
    
    url(r'^users/(?P<pk>[0-9]+)/$', UserView.as_view({'get': 'retrieve', 'put': 'update','delete':'destroy'}),name='user-detail'),
    url(r'^users/(?P<pk>[0-9]+)/profile/$', UserView.as_view({'put':'update_profile'}),name='profile-detail'),
    url(r'^users/(?P<pk>[0-9]+)/profile-picture/$', UserView.as_view({'put':'update_profile_pic'}),name='profile-detail'),
    url(r'^users/(?P<pk>[0-9]+)/bmi-profile/$', UserView.as_view({'get':'retrieve_bmi_profile','put':'update_bmi_profile'}),name='userbmiprofile-detail'),
    #url(r'^users/(?P<pk>[0-9]+)/sync-user/$', UserView.as_view({'put':'sync_user'}),name='syncuser-detail'),
    #url(r'^users/sync-user/$', UserView.as_view({'put':'sync_user'}),name='syncuser-detail'),
    url(r'^users/change-password/$', UserView.as_view({'post':'change_password'}),name='password-detail'),

    url(r'^food-items/$', FoodItemViewSet.as_view({'get':'list'}),name='fooditem-detail'),
    url(r'^food-items/(?P<pk>[0-9]+)/$', FoodItemViewSet.as_view({'get':'retrieve'}),name='fooditem-detail'),
    #url(r'^food-items/search/(?P<search_string>[0-9A-Za-z]+)/$', FoodItemViewSet.as_view({'get':'search'}),name='fooditem-list'),
    #Uncomment the admin/doc line below to enable admin documentation:
    #url(r'^admin/doc/', include('django.contrib.admindocs.urls')),
    #url(r'^upload/', upload_food_items),

    url(r'^healthfiles/download/(?P<healthfile_id>[0-9]+)/$', handles3downloads, name='download-healthfiles'),
    url(r'^healthfiles/share/(?P<healthfile_id>[0-9]+)/$', share_healthfile, name='share-healthfiles'),
    url(r'^goals/$', all_goals, name='all-goals'),
    #url(r'^watchdog/$', watchdog_data, name='all-data'),
    
    url(r'^logout/$', logout, name='logout'),

    url(r'^tasks/$', ListUserTasks.as_view()),
    url(r'^tasks/(?P<task_id>[0-9]+)/set_choice/$', UpdateSetChoiceTask.as_view()),

    #Uncomment the next line to enable the admin:
    url(r'^admin/', include(admin.site.urls)),
    url(r'^docs/', include('rest_framework_swagger.urls')),
    url(r'^explorer/', include('explorer.urls')),
    url(r'', include('gcmserver.urls')),

)
urlpatterns += (url(r'^admin/ses-stats/', include('django_ses.urls')),)
