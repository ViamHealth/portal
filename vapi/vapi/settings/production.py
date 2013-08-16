from .base import *

# Django settings for vapi project.

DEBUG = False
TEMPLATE_DEBUG = DEBUG

ADMINS = (
    ('Kunal', 'kunal.rachhoya@viamhealth.com'),
)

# Make this unique, and don't share it with anybody.
SECRET_KEY = '^2a-22@4m_5s7it^_4uz(ubc83j^021grryxrrn(qd$=$i^v&)'

DATABASES = {
    'default': {
        'ENGINE': 'django.db.backends.mysql', # Add 'postgresql_psycopg2', 'mysql', 'sqlite3' or 'oracle'.
        'NAME': 'viam',                      # Or path to database file if using sqlite3.
        # The following settings are not used with sqlite3:
        'USER': '',
        'PASSWORD': '',
        'HOST': '',                      # Empty for localhost through domain sockets or '127.0.0.1' for localhost through TCP.
        'PORT': '',                      # Set to empty string for default.
    }
}


# Hosts/domain names that are valid for this site; required if DEBUG is False
# See https://docs.djangoproject.com/en/1.5/ref/settings/#allowed-hosts
ALLOWED_HOSTS = [  '*' ]

TEMPLATE_DIRS = (
    '/home/kunalr/codebase/portal/vapi/templates',
    # Put strings here, like "/home/html/django_templates" or "C:/www/django/templates".
    # Always use forward slashes, even on Windows.
    # Don't forget to use absolute paths, not relative paths.
)

AWS_ACCESS_KEY_ID = ''
AWS_SECRET_ACCESS_KEY = ''
AWS_STORAGE_BUCKET_NAME = ''

S3_URL = 'http://%s.s3.amazonaws.com' % AWS_STORAGE_BUCKET_NAME
STATIC_DIRECTORY = '/static/'
MEDIA_DIRECTORY = '/media/'
STATIC_URL = S3_URL + STATIC_DIRECTORY
MEDIA_URL = S3_URL + MEDIA_DIRECTORY