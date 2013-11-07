from __future__ import unicode_literals

from django.db import models
import hashlib, os, pprint
from simple_history.models import HistoricalRecords

s3_image_root = 'http://viamhealth-docsbucket.s3.amazonaws.com/';

GLOBAL_STATUS_CHOICES = (
        ('ACTIVE','ACTIVE'),
        ('DELETED','DELETED')
    )

class DietTracker(models.Model):
    MEAL_TYPE_CHOICES = (
        ('BREAKFAST','BREAKFAST'),
        ('LUNCH','LUNCH'),
        ('SNACKS','SNACKS'),
        ('DINNER','DINNER'),
    )
    user = models.ForeignKey('auth.User', related_name="+")
    food_item = models.ForeignKey('FoodItem', related_name = "+", blank=False)
    food_quantity_multiplier = models.FloatField(blank=False)
    meal_type = models.CharField(max_length=18L, choices=MEAL_TYPE_CHOICES, db_index=True, blank=False)
    diet_date = models.DateField()
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')

    history = HistoricalRecords()

    class Meta:
        db_table = 'tbl_diet_tracker'
        verbose_name_plural = 'Diet Trackers'
        verbose_name = 'Diet Tracker'

    def __unicode__(self):
        return u'id=%s user=%s meal_type=%s food=%s' % (self.id, self.user.id, self.meal_type, self.food_item.name)

class FoodItem(models.Model):

    def get_display_image_path(self, filename): 
        return 'fooditems/display_image_'+hashlib.sha224(str(self.id)).hexdigest()+os.path.splitext(filename)[1]

    def display_image_url(self):
        if self.display_image:
            pic = 'media/'+str(self.display_image)
        else:
            pic = 'static/api/default_food_display_image_n.jpg'
        return s3_image_root + pic

    name = models.TextField(blank=False)
    display_image = models.ImageField(upload_to=get_display_image_path, blank=True, null=True)
    quantity = models.FloatField(blank=False)
    quantity_unit = models.TextField(blank=False)
    calories = models.FloatField(blank=True,default=0)
    total_fat = models.FloatField(blank=True,default=0)
    saturated_fat = models.FloatField(blank=True,default=0)
    polyunsaturated_fat  = models.FloatField(blank=True,default=0)
    monounsaturated_fat  = models.FloatField(blank=True,default=0)
    trans_fat     = models.FloatField(blank=True,default=0)
    cholesterol   = models.FloatField(blank=True,default=0)
    sodium    = models.FloatField(blank=True,default=0)
    potassium     = models.FloatField(blank=True,default=0)
    total_carbohydrates   = models.FloatField(blank=True,default=0)
    dietary_fiber     = models.FloatField(blank=True,default=0)
    sugars    = models.FloatField(blank=True,default=0)
    protein   = models.FloatField(blank=True,default=0)
    vitamin_a     = models.FloatField(blank=True,default=0)
    vitamin_c     = models.FloatField(blank=True,default=0)
    calcium   = models.FloatField(blank=True,default=0)
    iron  = models.FloatField(blank=True,default=0)

    calories_unit = models.CharField(max_length=64L,blank=True, null=True)
    total_fat_unit = models.CharField(max_length=64L,blank=True, null=True)
    saturated_fat_unit = models.CharField(max_length=64L,blank=True, null=True)
    polyunsaturated_fat_unit = models.CharField(max_length=64L,blank=True, null=True)
    monounsaturated_fat_unit = models.CharField(max_length=64L,blank=True, null=True)
    trans_fat_unit = models.CharField(max_length=64L,blank=True, null=True)
    cholesterol_unit = models.CharField(max_length=64L,blank=True, null=True)
    sodium_unit = models.CharField(max_length=64L,blank=True, null=True)
    potassium_unit = models.CharField(max_length=64L,blank=True, null=True)
    total_carbohydrates_unit = models.CharField(max_length=64L,blank=True, null=True)
    dietary_fiber_unit = models.CharField(max_length=64L,blank=True, null=True)
    sugars_unit = models.CharField(max_length=64L,blank=True, null=True)
    protein_unit = models.CharField(max_length=64L,blank=True, null=True)
    vitamin_a_unit = models.CharField(max_length=64L,blank=True, null=True)
    vitamin_c_unit = models.CharField(max_length=64L,blank=True, null=True)
    calcium_unit = models.CharField(max_length=64L,blank=True, null=True)
    iron_unit = models.CharField(max_length=64L,blank=True, null=True)
    created_at = models.DateTimeField(auto_now_add=True, null=True)
    updated_at = models.DateTimeField(auto_now=True, null=True)
    updated_by = models.ForeignKey('auth.User', related_name="+", db_column='updated_by')
    status = models.CharField(max_length=18L, choices=GLOBAL_STATUS_CHOICES, default='ACTIVE', db_index=True)
    class Meta:
        db_table = 'tbl_food_items'
        verbose_name_plural = 'Food Items'
        verbose_name = 'Food Item'
    def __unicode__(self):
        return u'%s' % self.name