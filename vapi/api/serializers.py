from django.contrib.auth.models import User
from api.models import *
from rest_framework import serializers
from django.core.validators import validate_email
from django.core.exceptions import ValidationError
import pprint
from datetime import date
from api.serializers_helper import *


"""
from django.contrib.auth import authenticate


class AuthTokenSerializer(serializers.Serializer):
    username = serializers.CharField()
    password = serializers.CharField()

    def validate(self, attrs):
        username = attrs.get('username')
        password = attrs.get('password')

        if username and password:
            user = authenticate(username=username, password=password)

            if user:
                if not user.is_active:
                    raise serializers.ValidationError('User account is disabled.')
                attrs['user'] = user
                return attrs
            else:
                raise serializers.ValidationError('Unable to login with provided credentials.')
        else:
            raise serializers.ValidationError('Must include "username" and "password"')
"""

class UserProfileSerializer(serializers.ModelSerializer):
    profile_picture_url = serializers.Field(source='profile_picture_url')
    class Meta:
        model = UserProfile
        fields = ( 'gender', 'date_of_birth', 'profile_picture_url','phone_number','blood_group','fb_profile_id','fb_username','organization', 'street','city','state','country','zip_code','lattitude','longitude','address',)

class UserPasswordSerializer(serializers.ModelSerializer):
    class Meta:
        model = User
        fields = ( 'password',)
    

class UserProfilePicSerializer(serializers.ModelSerializer):
    class Meta:
        model = UserProfile
        fields = ( 'profile_picture',)

class UserSerializer(serializers.HyperlinkedModelSerializer):
    profile = UserProfileSerializer(required=False)
    class Meta:
        model = User
        fields = ('id',  'username', 'email', 'first_name', 'last_name', 'profile')

class UserEditSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = User
        fields = ('id',  'first_name', 'last_name',)

class UserCreateSerializer(serializers.HyperlinkedModelSerializer):
    
    def validate_username(self, attrs, source):
        value = attrs[source]
        try:
            validate_email( value )
            return attrs
        except ValidationError:
            raise serializers.ValidationError("Enter a valid e-mail address.")
    class Meta:
        model = User
        fields = ('id',  'first_name', 'last_name','username', )


class UserSignupSerializer(serializers.HyperlinkedModelSerializer):
    
    def validate_username(self, attrs, source):
        value = attrs[source]   
        try:
            validate_email( value )
            return attrs
        except ValidationError:
            raise serializers.ValidationError("Enter a valid e-mail address.")
    class Meta:
        model = User
        fields = ('username', 'password')

class ReminderSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    #start_timestamp = serializers.IntegerField(source='start_timestamp',read_only=True)
    class Meta:
        model = Reminder
        fields = ('id','user','type','name','details','morning_count','afternoon_count','evening_count','night_count','start_date','end_date','repeat_mode','repeat_day','repeat_hour','repeat_min','repeat_weekday','repeat_every_x','repeat_i_counter')
    def validate(self, attrs):
        """
        if StringKeyIsNotNull(self.data,'id') and self.data['id'] != '':
            r = Reminder.objects.get(pk=int(self.data['id']))
            if r.start_date != attrs['start_date']:
                raise serializers.ValidationError("can not edit start_date")
        
        if attrs['type'] == '2':
            if FloatIsNull(attrs['morning_count'],"0.0") and FloatIsNull(attrs['afternoon_count'],"0.0") and FloatIsNull(attrs['evening_count'],"0.0") and FloatIsNull(attrs['night_count'],"0.0") :
                raise serializers.ValidationError("Provide atleast one of these 4 - morning_count, afternoon_count, evening_count, night_count")
        elif attrs['type'] == '1':
            if attrs['morning_count'] is not None or attrs['afternoon_count'] is not None or attrs['evening_count'] is not None or attrs['night_count'] is not None:
                raise serializers.ValidationError("Following not allowed for type 'OTHER' - morning_count, afternoon_count, evening_count, night_count")
        
        
        if attrs['repeat_mode'] == '0':
            if StringIsNotNull(attrs['repeat_min']) or StringIsNotNull(attrs['repeat_hour']) or StringIsNotNull(attrs['repeat_day']) or StringIsNotNull(attrs['repeat_weekday']):
                raise serializers.ValidationError("For None repeat_mode reminders make sure you do not provide repeat_weekday, repeat_min, repeat_min repeat_day")
        elif attrs['repeat_mode'] == '1':
            if StringIsNull(attrs['repeat_min']) or StringIsNull(attrs['repeat_hour']) or StringIsNotNull(attrs['repeat_day']) or StringIsNotNull(attrs['repeat_weekday']):
                raise serializers.ValidationError("For Daily reminders make sure you provide repeat_min , repeat_hour and do not provide repeat_weekday and repeat_day")
        elif attrs['repeat_mode'] == '2':
            if StringIsNotNull(attrs['repeat_min']) or StringIsNotNull(attrs['repeat_hour']) or StringIsNotNull(attrs['repeat_day']) or StringIsNotNull(attrs['repeat_weekday']):
                raise serializers.ValidationError("For Daily reminders make sure you provide repeat_min , repeat_hour , repeat_weekday and do not provide repeat_day")
        elif attrs['repeat_mode'] == '3':
            if StringIsNotNull(attrs['repeat_min']) or StringIsNotNull(attrs['repeat_hour']) or StringIsNotNull(attrs['repeat_day']) or StringIsNotNull(attrs['repeat_weekday']):
                raise serializers.ValidationError("For Daily reminders make sure you provide repeat_min , repeat_hour , repeat_day and do not provide repeat_weekday")
        """
        return attrs


class ReminderReadingsSerializer(serializers.HyperlinkedModelSerializer):
    reminder = ReminderSerializer(required=False,read_only=True)
    #reminder = serializers.Field(source='reminder.id')
    user = serializers.Field(source='user.id')
    class Meta:
        model = ReminderReadings
        fields = ('id','reminder','morning_check','afternoon_check','evening_check','night_check','complete_check','user')


class UserBmiProfileSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    bmr = serializers.SerializerMethodField('get_bmr')
    bmi_classification = serializers.SerializerMethodField('get_bmi_classification')
    bp_classification = serializers.SerializerMethodField('get_bp_classification')
    sugar_classification = serializers.SerializerMethodField('get_sugar_classification')
    cholesterol_classification = serializers.SerializerMethodField('get_cholesterol_classification')
    total_cholesterol = serializers.Field(source='total_cholesterol')

    class Meta:
        model = UserBmiProfile
        fields = (
            'id', 
            'user', 
            'height' , 
            'weight' ,
            'lifestyle',
            'bmi_classification',
            'bmr',
            'systolic_pressure',
            'diastolic_pressure',
            'pulse_rate',
            'bp_classification',
            'random',
            'fasting',
            'sugar_classification',
            'hdl',
            'ldl',
            'triglycerides',
            'total_cholesterol',
            'cholesterol_classification',
            )

    def get_bmi_classification(self, obj=None):
        bmi_classification = ''
        if obj.weight is not None and obj.height is not None:
            bmi = float(obj.weight)/( float(obj.height)/100.00 )
            if bmi < 16.00:
                #bmi_classification = 'Underweight'
                bmi_classification = '1'
            elif bmi >= 18.50 and bmi <= 24.99:
                #bmi_classification = 'Normal range'
                bmi_classification = '2'
            elif bmi >= 25.00 and bmi <= 29.99:
                #bmi_classification = 'Overweight'
                bmi_classification = '3'
            elif bmi >= 30.00:
                #bmi_classification = 'Obese'
                bmi_classification = '4'
        return bmi_classification


    def get_bmr(self, obj=None):
        p = UserProfile.objects.get(user=obj.user)
        age_years = None
        if p.date_of_birth is not None:
            age_years = calculate_age(p.date_of_birth)

        gender = p.gender
        bmr = ''
        if obj.weight is not None and obj.height is not None and age_years is not None and gender is not None:
            if gender == 'MALE':
                bmr = 655 + 9.6*float(obj.weight) + 1.8*float(obj.height) - 4.7*int(age_years)
            elif gender == 'MALE':
                bmr = 66 + 13.7*float(obj.weight) + 5*float(obj.height) - 6.8*float(age_years)

        return bmr
            
    def get_bp_classification(self, obj=None):
        bp_classification = ''
        if obj is not None:
            if obj.systolic_pressure is not None and obj.diastolic_pressure is not None:
                if int(obj.systolic_pressure) < 90 or int(obj.diastolic_pressure) < 60:
                    #bp_classification = 'Low'
                    bp_classification = '1'
                elif ( int(obj.systolic_pressure) >= 90 and int(obj.systolic_pressure) < 120 )  or ( int(obj.diastolic_pressure) >= 60 and int(obj.diastolic_pressure) < 80 ) :
                    #bp_classification = 'Normal'
                    bp_classification = '2'
                elif int(obj.systolic_pressure) >= 120 or int(obj.diastolic_pressure) >=80 :
                    #bp_classification = 'High'
                    bp_classification = '3'
        return bp_classification


    def get_sugar_classification(self, obj=None):
        sugar_classification = ''
        if obj is not None:
            if obj.fasting is not None:
                if int(obj.fasting) < 70 :
                    #sugar_classification = 'Low'
                    sugar_classification = '1'
                elif  int(obj.fasting) >= 70 and int(obj.fasting) <= 100 :
                    #sugar_classification = 'Normal'
                    sugar_classification = '2'
                elif int(obj.fasting) > 100 :
                    #sugar_classification = 'High'
                    sugar_classification = '3'
        return sugar_classification

    def get_cholesterol_classification(self, obj=None):
        cholesterol_classification = ''
        return cholesterol_classification

#class HealthfileListSerializer(serializers.HyperlinkedModelSerializer):
#    class Meta:
#        model = Healthfile
#        fields = ('id', 'user','tags','name' ,'description','mime_type','file','status','created_at','updated_at')
"""
class HealthfileTagSerializer(serializers.HyperlinkedModelSerializer):
    healthfile = serializers.Field(source='healthfile.id')
    class Meta:
        model = HealthfileTag
        fields = ('id','healthfile','tag')
"""
class HealthfileTagAddSerializer(serializers.HyperlinkedModelSerializer):
    healthfile = serializers.PrimaryKeyRelatedField(many=False)
    class Meta:
        model = HealthfileTag
        fields = ('tag','id','healthfile')

class HealthfileTagListingField(serializers.RelatedField):
    def to_native(self, value):
        return '%s' % (value.tag)

class HealthfileSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    download_url = serializers.Field(source='download_url')
    tags = HealthfileTagListingField(many=True)
    class Meta:
        model = Healthfile
        fields = ('id', 'user','tags','name' ,'description','mime_type','download_url')

class HealthfileEditSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    tags = HealthfileTagListingField(many=True,read_only=True)
    class Meta:
        model = Healthfile
        fields = ('id','description','tags')

class HealthfileUploadSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    class Meta:
        model = Healthfile
        fields = ('id','file','description')


class UserWeightReadingSerializer(serializers.HyperlinkedModelSerializer):
    user_weight_goal = serializers.Field(source='user_weight_goal.id')
    class Meta:
        model = UserWeightReading
        fields = ('id','user_weight_goal','weight','weight_measure' ,'reading_date','comment')

class UserWeightGoalSerializer(serializers.HyperlinkedModelSerializer):
    readings = UserWeightReadingSerializer(required=False,read_only=True)
    user = serializers.Field(source='user.id')
    healthy_range = serializers.SerializerMethodField('get_healthy_range')
    class Meta:
        model = UserWeightGoal
        fields = ('id', 'user','readings','weight','weight_measure','healthy_range' ,'target_date','interval_num','interval_unit',)

    #def validate(self, attrs):
    #    return goals_date_validate(self,attrs);

    def get_healthy_range(self, obj=None):
        u = UserBmiProfile.objects.get(user=obj.user)
        if u.height == '':
                height = 155
        else:
                height = u.height
        max_bmi = 24.9
        min_bmi = 18.6
        min_weight = min_bmi * ( float(height) / 100 ) * ( float(height) / 100 )
        max_weight = max_bmi * ( float(height) / 100 ) * ( float(height) / 100 )
        a = {}
        a['weight'] = {}
        a['weight']['max'] = max_weight
        a['weight']['max_measure'] = 'METRIC'
        a['weight']['min'] = min_weight
        a['weight']['min_measure'] = 'METRIC'
        return a


class UserBloodPressureReadingSerializer(serializers.HyperlinkedModelSerializer):
    user_blood_pressure_goal = serializers.Field(source='user_blood_pressure_goal.id')
    class Meta:
        model = UserBloodPressureReading
        fields = ('id','user_blood_pressure_goal','systolic_pressure','diastolic_pressure', 'pulse_rate' ,'reading_date','comment')

class UserBloodPressureGoalSerializer(serializers.HyperlinkedModelSerializer):
    readings = UserBloodPressureReadingSerializer(required=False,read_only=True)
    user = serializers.Field(source='user.id')
    healthy_range = serializers.SerializerMethodField('get_healthy_range')
    class Meta:
        model = UserBloodPressureGoal
        fields = ('id', 'user','readings','systolic_pressure','diastolic_pressure', 'pulse_rate' ,'healthy_range','target_date','interval_num','interval_unit',)

    def validate(self, attrs):
        return goals_date_validate(self,attrs);

    def get_healthy_range(self, obj=None):
        max_systolic_pressure  = 120;
        min_systolic_pressure  = 90;
        max_diastolic_pressure  = 80;
        min_diastolic_pressure  = 60;
        a = {}
        a['systolic_pressure'] ={}
        a['systolic_pressure']['max'] = max_systolic_pressure;
        a['systolic_pressure']['min'] = min_systolic_pressure;
        a['diastolic_pressure'] ={}
        a['diastolic_pressure']['max'] = max_diastolic_pressure;
        a['diastolic_pressure']['min'] = min_diastolic_pressure;
        return a;


class UserCholesterolReadingSerializer(serializers.HyperlinkedModelSerializer):
    user_cholesterol_goal = serializers.Field(source='user_cholesterol_goal.id')
    class Meta:
        model = UserCholesterolReading
        fields = ('id','user_cholesterol_goal','hdl','ldl', 'triglycerides', 'total_cholesterol' ,'reading_date','comment')

class UserCholesterolGoalSerializer(serializers.HyperlinkedModelSerializer):
    readings = UserCholesterolReadingSerializer(required=False,read_only=True)
    user = serializers.Field(source='user.id')
    healthy_range = serializers.SerializerMethodField('get_healthy_range')
    class Meta:
        model = UserCholesterolGoal
        fields = ('id', 'user','readings','hdl','ldl', 'triglycerides', 'total_cholesterol' ,'healthy_range','target_date','interval_num','interval_unit',)

    def validate(self, attrs):
        return goals_date_validate(self,attrs);

    def get_healthy_range(self, obj=None):
        max_total_cholesterol  = 200;
        min_total_cholesterol  = 0;
        max_hdl  = 50;
        min_hdl  = 0;
        max_ldl  = 100;
        min_ldl  = 0;
        a = {}
        a['total_cholesterol'] ={}
        a['total_cholesterol']['max'] = max_total_cholesterol;
        a['total_cholesterol']['min'] = min_total_cholesterol;
        a['hdl'] ={}
        a['hdl']['max'] = max_hdl;
        a['hdl']['min'] = min_hdl;
        a['ldl'] ={}
        a['ldl']['max'] = max_ldl;
        a['ldl']['min'] = min_ldl;
        return a;

class UserGlucoseReadingSerializer(serializers.HyperlinkedModelSerializer):
    user_glucose_goal = serializers.Field(source='user_glucose_goal.id')
    class Meta:
        model = UserGlucoseReading
        fields = ('id','user_glucose_goal','fasting','random' ,'reading_date','comment')

class UserGlucoseGoalSerializer(serializers.HyperlinkedModelSerializer):
    readings = UserGlucoseReadingSerializer(required=False,read_only=True)
    user = serializers.Field(source='user.id')
    healthy_range = serializers.SerializerMethodField('get_healthy_range')
    class Meta:
        model = UserGlucoseGoal
        fields = ('id', 'user','readings','random','fasting','healthy_range','target_date','interval_num','interval_unit',)

    def validate(self, attrs):
        return goals_date_validate(self,attrs);

    def get_healthy_range(self, obj=None):
        max_random  = 140;
        min_random  = 100;
        max_fasting  = 100;
        min_fasting  = 70;
        
        a = {}
        a['random'] ={}
        a['random']['max'] = max_random;
        a['random']['min'] = min_random;
        a['fasting'] ={}
        a['fasting']['max'] = max_fasting;
        a['fasting']['min'] = min_fasting;
        return a;

class FoodItemSerializer(serializers.HyperlinkedModelSerializer):
    display_image_url = serializers.Field(source='display_image_url')
    class Meta:
        model = FoodItem
        fields = ( 'id', 'name','display_image_url' ,'quantity', 'quantity_unit', 'calories', 'total_fat', 'saturated_fat', 'polyunsaturated_fat', 'monounsaturated_fat', 'trans_fat', 'cholesterol', 'sodium', 'potassium', 'total_carbohydrates', 'dietary_fiber', 'sugars', 'protein', 'vitamin_a', 'vitamin_c', 'calcium', 'iron', 'calories_unit', 'total_fat_unit', 'saturated_fat_unit', 'polyunsaturated_fat_unit', 'monounsaturated_fat_unit', 'trans_fat_unit', 'cholesterol_unit', 'sodium_unit', 'potassium_unit', 'total_carbohydrates_unit', 'dietary_fiber_unit', 'sugars_unit', 'protein_unit', 'vitamin_a_unit', 'vitamin_c_unit', 'calcium_unit', 'iron_unit',)

class DietTrackerSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    food_item = serializers.PrimaryKeyRelatedField(many=False)
    class Meta:
        model = DietTracker
        fields = ('id','food_item','user','food_quantity_multiplier','meal_type')
        
"""

class GoalSerializer(serializers.HyperlinkedModelSerializer):
    weight = UserWeightGoalSerializer

    class Meta():
        fields = ('weight')
"""

#class AuthTokenSerializer(serializers.Serializer):
#    username = serializers.CharField()
#    password = serializers.CharField()

#    def validate(self, attrs):
#        username = attrs.get('username')
#        password = attrs.get('password')
#
#        if username and password:
#            user = authenticate(username=username, password=password)
#
#            if user:
#                if not user.is_active:
#                    raise serializers.ValidationError('User account is disabled.')
#                attrs['user'] = user
#                return attrs
#            else:
#                raise serializers.ValidationError('Unable to login with provided credentials.')
#        else:
#            raise serializers.ValidationError('Must include "username" and "password"')
