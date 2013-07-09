from django.conf.urls import patterns, include, url
from rest_framework import routers
from api import views

router = routers.DefaultRouter()
router.register(r'users', views.UserViewSet)
router.register(r'groups', views.GroupViewSet)
router.register(r'healthfiles', views.HealthfileViewSet)
router.register(r'healthfiletags', views.HealthfileTagViewSet)

# Uncomment the next two lines to enable the admin:
from django.contrib import admin
admin.autodiscover()

urlpatterns = patterns('',
    # Examples:
    # url(r'^$', 'vapi.views.home', name='home'),
    # url(r'^vapi/', include('vapi.foo.urls')),
    #url(r'^', include('api.urls')),
    url(r'^', include(router.urls)),
    #url(r'^api-auth/', include('rest_framework.urls', namespace='rest_framework')),
    url(r'^api-token-auth/', 'rest_framework.authtoken.views.obtain_auth_token'),

    #Uncomment the admin/doc line below to enable admin documentation:
    #url(r'^admin/doc/', include('django.contrib.admindocs.urls')),

    #Uncomment the next line to enable the admin:
    url(r'^admin/', include(admin.site.urls)),
)
