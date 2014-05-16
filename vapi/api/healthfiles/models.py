from __future__ import unicode_literals

from django.db import models
import mimetypes, pprint, datetime
from simple_history.models import HistoricalRecords
from api.models import ApiModel, StaticApiModel


class HealthfileTag(StaticApiModel):
    healthfile = models.ForeignKey('Healthfile', related_name="+")
    tag = models.CharField(max_length=64L)

    history = HistoricalRecords()

    class Meta:
        db_table = 'tbl_healthfile_tags'
    def __unicode__(self):
        return u' %s of healthfile %s' % (self.id, self.healthfile)


class Healthfile(ApiModel):
    def get_healthfile_path(self, filename):
        return 'healthfiles/'+str(self.user.id)+str(self.name)+str(datetime.datetime.now())

    user = models.ForeignKey('auth.User', related_name="+")
    name = models.CharField(max_length=256L,blank=True, null=True)
    description = models.TextField(blank=True, null=True)
    mime_type = models.CharField(max_length=256L,blank=True, null=True)
    file = models.FileField(upload_to=get_healthfile_path, blank=True, editable=True,)
    #TODO: Use forms instead of using this flag
    uploading_file = False

    history = HistoricalRecords()

    class Meta:
        db_table = 'tbl_healthfiles'

    def __unicode__(self):
        return u'%s' % self.id

    def download_url(self):
        return 'http://api.viamhealth.com/healthfiles/download/'+str(self.id)+'/';
        """
        from django.http import HttpRequest
        from django.core.urlresolvers import reverse 

        return HttpRequest.build_absolute_uri(reverse('download-healthfiles'),args=(self.id));
        
        if self.file:
            return s3_image_root + 'media/' + str(self.file)
        else:
            return ''
        """

    def save(self, *args, **kwargs):
        if self.uploading_file:
            hfile = self.file
            self.name = hfile.name
            mime_type = mimetypes.guess_type(hfile.name)[0]
            if mime_type is None:
                mime_type = "application/octet-stream"
            self.mime_type = mime_type
        super(Healthfile, self).save(*args, **kwargs)

