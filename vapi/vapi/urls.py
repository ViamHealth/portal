from django.conf.urls import patterns, include, url
from rest_framework import routers
from api import views

router = routers.DefaultRouter(trailing_slash=True)
router.register(r'groups', views.GroupViewSet)
router.register(r'healthfiles', views.HealthfilesViewSet)
router.register(r'healthfiletags', views.HealthfileTagViewSet)

# Uncomment the next two lines to enable the admin:
from django.contrib import admin
admin.autodiscover()

#obtain_auth_token = views.ObtainAuthToken.as_view()
urlpatterns = patterns('',
    # Examples:
    # url(r'^$', 'vapi.views.home', name='home'),
    #url(r'^', include('api.urls')),
    url(r'^', include(router.urls)),
    #url(r'^api-token-auth/', obtain_auth_token),
    url(r'^users/$', views.UserList.as_view({'get': 'list','post': 'create'}),name='user-list'),
    url(r'^users/me/$', views.UserList.as_view({'get': 'current_user'}), name='snippet-highlight'),
    url(r'^users/(?P<pk>[0-9]+)/$', views.UserDetail.as_view(),name='user-detail'),

    #Uncomment the admin/doc line below to enable admin documentation:
    #url(r'^admin/doc/', include('django.contrib.admindocs.urls')),

    #Uncomment the next line to enable the admin:
    url(r'^admin/', include(admin.site.urls)),
)
