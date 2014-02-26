# This is an auto-generated Django model module.
# You'll have to do the following manually to clean this up:
#     * Rearrange models' order
#     * Make sure each model has one field with primary_key=True
# Feel free to rename the models, but don't rename db_table values or field names.
#
# Also note: You'll have to insert the output of 'django-admin.py sqlcustom [appname]'
# into your database.

from __future__ import unicode_literals
from django.db import models



class StaticApiModel(models.Model):
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    is_deleted = models.BooleanField(default=False,db_index=True)

    def soft_delete(self, *args, **kwargs):
        self.is_deleted = True
        super(StaticApiModel, self).save(*args, **kwargs)

    def get_last_timestamp(self):
        return self.updated_at

    class Meta:
        abstract = True

class ApiModel(StaticApiModel):
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')

    class Meta:
        abstract = True
    """
    @property
    def _history_user(self):
        return self.updated_by

    @_history_user.setter
    def _history_user_setter(self, value):
        self.updated_by = value
    """
"""
def commit_changeset(model, **kw):
    from django.contrib.auth.models import User

    if model  in [User, ] or issubclass(model.__class__, ApiModel):
        #model = kw["instance"]
        deleted = False
        for key, value in kw.iteritems():
            if key == 'deleted':
                deleted = value
        obj, created =  ChangeSet.objects.get_or_create(model=model.__class__.__name__,model_id=model.id,deleted=deleted)
        print created
        if not created:
            obj.commit()
"""
