#keep setings in this file, which vary with env ( local or production )

from settings_static import *

DEBUG = False
TEMPLATE_DEBUG = DEBUG
ADMINS = (
    ('Kunal', 'kunal.rachhoya@viamhealth.com'),
)
MANAGERS = ADMINS

DATABASES = {
    'default': {
        'ENGINE': 'django.db.backends.mysql',
        'NAME': 'viam',
        'USER': 'root',
        'PASSWORD': '',
        'HOST': '',
        'PORT': '',
    }
}

ALLOWED_HOSTS = [ '*' ]

USE_TZ = True

XS_SHARING_ALLOWED_ORIGINS = "http://alpha.viamhealth.com"

TEMPLATE_DIRS = (
    '/home/kunalr/codebase/portal/vapi/templates',
)

ENABLE_EMAIL_SANDBOX = False
