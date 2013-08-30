from django.conf.urls import patterns, include, url
from rest_framework import routers
from api import views

router = routers.DefaultRouter(trailing_slash=True)
router.register(r'healthfiles', views.HealthfileViewSet)
#router.register(r'healthfiletags', views.HealthfileTagViewSet)
router.register(r'weight-goals', views.UserWeightGoalViewSet)
router.register(r'blood-pressure-goals', views.UserBloodPressureGoalViewSet)
router.register(r'cholesterol-goals', views.UserCholesterolGoalViewSet)
router.register(r'glucose-goals', views.UserGlucoseGoalViewSet)
router.register(r'diet-tracker', views.DietTrackerViewSet)

#router.register(r'goals', views.GoalViewSet)


# Uncomment the next two lines to enable the admin:
from django.contrib import admin
admin.autodiscover()

#obtain_auth_token = views.ObtainAuthToken.as_view()
urlpatterns = patterns('',
    # Examples:
    # url(r'^$', 'vapi.views.home', name='home'),
    #url(r'^', include('api.urls')),
    url(r'^$', views.api_root),
    url(r'^', include(router.urls)),
    url(r'^api-auth/', include('rest_framework.urls', namespace='rest_framework')),
    #url(r'^api-token-auth/', obtain_auth_token),
    url(r'^api-token-auth/', 'rest_framework.authtoken.views.obtain_auth_token'),
    url(r'^signup/$', views.SignupView.as_view({'post': 'user_signup'}), name='user-signup'),
    url(r'^users/$', views.UserView.as_view({'get': 'list','post': 'create'}),name='user-list'),
    url(r'^users/me/$', views.UserView.as_view({'get': 'current_user'}), name='user-me'),

    url(r'^reminders/$', views.ReminderViewSet.as_view({'get':'list','post':'create'}),name='reminder-list'),
    url(r'^reminders/(?P<pk>[0-9]+)/$', views.ReminderViewSet.as_view({'get':'retrieve','put':'update','delete':'destroy'}),name='reminder-detail'),

    #url(r'^bmi-profile/$', views.UserBmiProfileViewSet.as_view({'get':'list','post':'create'}),name='userbmiprofile-list'),
    #url(r'^bmi-profile/(?P<pk>[0-9]+)/$', views.UserBmiProfileViewSet.as_view({'get':'retrieve','put':'update','delete':'destroy'}),name='userbmiprofile-detail'),

    #url(r'^goals/$', views.GoalViewSet.as_view(),name='goal-list'),
    url(r'^weight-goals/(?P<pk>[0-9]+)/set-reading/$', views.UserWeightGoalViewSet.as_view({'post':'set_reading'}),name='goal-weight-reading-detail'),
    url(r'^weight-goals/(?P<pk>[0-9]+)/destroy-reading/$', views.UserWeightGoalViewSet.as_view({'delete':'destroy_reading'}),name='goal-weight-reading-detail'),

    url(r'^blood-pressure-goals/(?P<pk>[0-9]+)/set-reading/$', views.UserBloodPressureGoalViewSet.as_view({'post':'set_reading'}),name='goal-blood-pressure-reading-detail'),
    url(r'^blood-pressure-goals/(?P<pk>[0-9]+)/destroy-reading/$', views.UserBloodPressureGoalViewSet.as_view({'delete':'destroy_reading'}),name='goal-blood-pressure-reading-detail'),

    url(r'^cholesterol-goals/(?P<pk>[0-9]+)/set-reading/$', views.UserCholesterolGoalViewSet.as_view({'post':'set_reading'}),name='goal-cholesterol-reading-detail'),
    url(r'^cholesterol-goals/(?P<pk>[0-9]+)/destroy-reading/$', views.UserCholesterolGoalViewSet.as_view({'delete':'destroy_reading'}),name='goal-cholesterol-reading-detail'),
    
    url(r'^glucose-goals/(?P<pk>[0-9]+)/set-reading/$', views.UserGlucoseGoalViewSet.as_view({'post':'set_reading'}),name='goal-glucose-reading-detail'),
    url(r'^glucose-goals/(?P<pk>[0-9]+)/destroy-reading/$', views.UserGlucoseGoalViewSet.as_view({'delete':'destroy_reading'}),name='goal-glucose-reading-detail'),

    
    url(r'^users/(?P<pk>[0-9]+)/$', views.UserView.as_view({'get': 'retrieve', 'put': 'update','delete':'destroy'}),name='user-detail'),
    url(r'^users/(?P<pk>[0-9]+)/profile/$', views.UserView.as_view({'put':'update_profile'}),name='profile-detail'),
    url(r'^users/(?P<pk>[0-9]+)/profile-picture/$', views.UserView.as_view({'put':'update_profile_pic'}),name='profile-detail'),
    url(r'^users/(?P<pk>[0-9]+)/bmi-profile/$', views.UserView.as_view({'get':'retrieve_bmi_profile','put':'update_bmi_profile'}),name='userbmiprofile-detail'),
    url(r'^users/(?P<pk>[0-9]+)/change-password/$', views.UserView.as_view({'post':'change_password'}),name='password-detail'),
    url(r'^food-items/(?P<pk>[0-9]+)/$', views.FoodItemViewSet.as_view({'get':'retrieve'}),name='fooditem-detail'),
    url(r'^food-items/search/(?P<search_string>[0-9A-Za-z]+)/$', views.FoodItemViewSet.as_view({'get':'search'}),name='fooditem-list'),
    #Uncomment the admin/doc line below to enable admin documentation:
    #url(r'^admin/doc/', include('django.contrib.admindocs.urls')),
    #url(r'^upload/', ote.upload_food_items),

    #Uncomment the next line to enable the admin:
    url(r'^admin/', include(admin.site.urls)),
)
