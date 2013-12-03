from django.contrib.auth.models import User, AnonymousUser
from .models import *
from api.goals.models import *
from rest_framework import serializers
from django.core.validators import validate_email
from django.core.exceptions import ValidationError
from api.serializers_helper import *
from api.vfacebook import *


class UserProfileSerializer(serializers.ModelSerializer):
    profile_picture_url = serializers.Field(source='profile_picture_url')
    class Meta:
        model = UserProfile
        fields = ( 'gender', 'date_of_birth', 'profile_picture_url','mobile','blood_group','fb_profile_id','fb_username','organization', 'street','city','state','country','zip_code','lattitude','longitude','address',)

class UserPasswordSerializer(serializers.Serializer):
    old_password = serializers.CharField(required=True)
    password = serializers.CharField(required=True)
    
    def validate_old_password(self, attrs, source):
        value = attrs[source]
        user = self.context['request'].user
        if not user.check_password(value):
            raise serializers.ValidationError("Incorrect password")
        return attrs
    

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


class UserShareSerializer(serializers.Serializer):
    share_user_id = serializers.CharField(required=True)
    email = serializers.CharField(required=True)
    is_self = serializers.BooleanField(default=False)
    
    def validate_email(self, attrs, source):
        value = attrs[source]   
        try:
            validate_email( value )
            return attrs
        except ValidationError:
            raise serializers.ValidationError("Enter a valid e-mail address.")
    
    def validate(self, attrs):
        is_self = attrs['is_self']
        share_user_id = attrs['share_user_id']
        email = attrs['email']
        try:
            share_user = User.objects.get(pk=share_user_id)
            if not share_user.is_active:
                raise serializers.ValidationError('Share User account is disabled.')
            attrs['share_user'] = share_user

            if is_self:
                if share_user.email is None:
                    try:
                        User.objects.get(email=email)
                        raise serializers.ValidationError('Email belongs to some other user.')
                    except User.DoesNotExist:
                        return attrs
                elif share_user.email == email:
                    return attrs
                else:
                    raise serializers.ValidationError('Email nonempty and  mis match for self user')
            else:
                return attrs
        except User.DoesNotExist:
            raise serializers.ValidationError('Can not share user as user id does not exist')


class UserFacebookConnectSerializer(serializers.Serializer):
    access_token = serializers.CharField(required=True)

    def validate(self, attrs):
        access_token = attrs.get('access_token').strip()
        try:
            data = get_user_facebook_data(access_token)
        except:
            raise serializers.ValidationError('Could not connect to facebook')

        fb_profile_id = data.get('id',None)
        if fb_profile_id is None:
            raise serializers.ValidationError('Invalid access_token')
        else:
            try:
                profile = UserProfile.objects.get(fb_profile_id=fb_profile_id)
                if profile.user.id != self.context['request'].user.id:
                    raise serializers.ValidationError('FB_ACCOUNT_BELONGS_OTHER')
                else:
                    attrs['fb_data'] = data
                    return attrs
            except UserProfile.DoesNotExist:
                attrs['fb_data'] = data
                return attrs

class UserCreateSerializer(serializers.HyperlinkedModelSerializer):

    class Meta:
        model = User
        fields = ('id',  'first_name', 'last_name','username', 'email')


class EmailUserSignupSerializer(serializers.HyperlinkedModelSerializer):
    
    class Meta:
        model = User
        fields = ('email', 'password','username')

    def validate_email(self, attrs, source):
        value = attrs[source]   
        try:
            validate_email( value )
        except ValidationError:
            raise serializers.ValidationError("Enter a valid e-mail address.")

        try:
            u = User.objects.get(email=value)
            raise serializers.ValidationError("e-mail already exists")
        except User.DoesNotExist:
            return attrs


class UserSignupSerializer(serializers.HyperlinkedModelSerializer):
    class Meta:
        model = User
        fields = ('username', 'password','email')

class UserInviteSerializer(serializers.HyperlinkedModelSerializer):
    email = serializers.CharField(required=True)
    class Meta:
        model = User
        fields = ('email',)

class ForgotPasswordEmailSerializer(serializers.Serializer):
    email = serializers.CharField(required=True)

    def validate_email(self, attrs, source):
        value = attrs[source]
        try:
            validate_email(value)
            try:
                user = User.objects.get(email=value)
                if not user.is_active:
                    raise serializers.ValidationError('User account is disabled.')
                attrs['user'] = user
                return attrs
            except User.DoesNotExist:
                raise serializers.ValidationError("E-mail address not found.") 
        except ValidationError:
            raise serializers.ValidationError("Enter a valid e-mail address.")

class ForgotPasswordMobileSerializer(serializers.Serializer):
    mobile = serializers.CharField(required=True)

    def validate_mobile(self,attrs, source):
        value = attrs[source]
        try:
            userprofile = UserProfile.objects.get(mobile=value)
            user = userprofile.user
            if not user.is_active:
                raise serializers.ValidationError('User account is disabled.')
            attrs['user'] = user
            return attrs
        except UserProfile.DoesNotExist:
            raise serializers.ValidationError("Mobile not found.")


## The BMI profile serializer

class LatestReadings:
    
    def __init__(self,data=None):
        if data is not None:
            self.height = data.height
            self.weight = data.weight
            self.lifestyle = data.lifestyle
            self.systolic_pressure = data.systolic_pressure
            self.diastolic_pressure = data.diastolic_pressure
            self.pulse_rate = data.pulse_rate
            self.random = data.random
            self.fasting = data.fasting
            self.hdl = data.hdl
            self.ldl = data.ldl
            self.triglycerides = data.triglycerides

    height = None
    weight = None
    lifestyle = None
    bmi_classification = ''
    bmr = ''
    systolic_pressure = None
    diastolic_pressure = None
    pulse_rate = None
    bp_classification = ''
    random = None
    fasting = None
    sugar_classification = ''
    hdl = None
    ldl = None
    triglycerides = None
    total_cholesterol = None
    cholesterol_classification = None

    def get_bmi_classification(self,obj=None):
        bmi_classification = ''
        print 'getoong bmi'
        if obj is not None:
            print 'in bmi'
            if obj.weight is not None and obj.height is not None:
                bmi = float(obj.weight)/( ( float(obj.height)/100.00 ) * ( float(obj.height)/100.00 ))
                print bmi
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
        bmr = ''
        if obj is not None:
            p = UserProfile.objects.get(user=obj.user)
            age_years = None
            if p.date_of_birth is not None:
                age_years = calculate_age(p.date_of_birth)

            gender = p.gender
            
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

    def get_total_cholesterol(self, obj = None):
        total_cholesterol = None
        if obj is not None:
            if obj.hdl is not None and obj.ldl is not None and obj.triglycerides is not None:
                total_cholesterol = float(obj.hdl) + float(obj.ldl) + 0.2 * float(obj.triglycerides)

        return total_cholesterol

class LatestBmiProfileData(serializers.Serializer):
    user = serializers.CharField()
    height = serializers.CharField()
    weight = serializers.CharField()
    lifestyle = serializers.CharField()
    bmr = serializers.SerializerMethodField('get_bmr')
    bmi_classification = serializers.SerializerMethodField('get_bmi_classification')
    systolic_pressure = serializers.CharField()
    diastolic_pressure = serializers.CharField()
    pulse_rate = serializers.CharField()
    bp_classification = serializers.SerializerMethodField('get_bp_classification')
    random = serializers.CharField()
    fasting = serializers.CharField()
    sugar_classification = serializers.SerializerMethodField('get_sugar_classification')
    hdl = serializers.CharField()
    ldl= serializers.CharField()
    triglycerides = serializers.CharField()
    total_cholesterol = serializers.SerializerMethodField('get_total_cholesterol')
    cholesterol_classification = serializers.SerializerMethodField('get_cholesterol_classification')
    

    def get_bmi_classification(self, obj=None):
        data = LatestReadings(obj)
        return data.get_bmi_classification(obj)
    def get_bmr(self, obj=None):
        data = LatestReadings(obj)
        return data.get_bmr(obj)

    def get_bp_classification(self, obj=None):
        data = LatestReadings(obj)
        return data.get_bp_classification(obj)

    def get_sugar_classification(self, obj=None):
        data = LatestReadings(obj)
        return data.get_sugar_classification(obj)

    def get_cholesterol_classification(self, obj=None):
        data = LatestReadings(obj)
        return data.get_cholesterol_classification(obj)

    def get_total_cholesterol(self, obj=None):
        data = LatestReadings(obj)
        return data.get_total_cholesterol(obj)

class UserBmiProfileSerializer(serializers.HyperlinkedModelSerializer):
    user = serializers.Field(source='user.id')
    bmr = serializers.SerializerMethodField('get_bmr')
    bmi_classification = serializers.SerializerMethodField('get_bmi_classification')
    bp_classification = serializers.SerializerMethodField('get_bp_classification')
    sugar_classification = serializers.SerializerMethodField('get_sugar_classification')
    cholesterol_classification = serializers.SerializerMethodField('get_cholesterol_classification')
    total_cholesterol = serializers.Field(source='total_cholesterol')
    latest_readings = serializers.SerializerMethodField('get_latest_readings')
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
            'latest_readings',
            )

    def get_latest_readings(self, obj=None):
        cp = obj
        user = obj.user
        q = UserWeightReading.objects.filter(user=user).order_by('-reading_date')[:1]
        if len(q) == 1:
            weight = q[0]
            cp.weight = weight.weight

        q = UserBloodPressureReading.objects.filter(user=user).order_by('-reading_date')[:1]
        if len(q) == 1:
            bp = q[0]
            if bp.systolic_pressure is not None:
                cp.systolic_pressure = bp.systolic_pressure
            if bp.diastolic_pressure is not None:
                cp.diastolic_pressure = bp.diastolic_pressure
            if bp.pulse_rate is not None:
                cp.pulse_rate = bp.pulse_rate

        q = UserGlucoseReading.objects.filter(user=user).order_by('-reading_date')[:1]
        if len(q) == 1:
            bp = q[0]
            if bp.random is not None:
                cp.random = bp.random
            if bp.fasting is not None:
                cp.fasting = bp.fasting
        
        q = UserCholesterolReading.objects.filter(user=user).order_by('-reading_date')[:1]
        if len(q) == 1:
            bp = q[0]
            if bp.hdl is not None:
                cp.hdl = bp.hdl
            if bp.ldl is not None:
                cp.ldl = bp.ldl
            if bp.triglycerides is not None:
                cp.triglycerides = bp.triglycerides

        s = LatestBmiProfileData(cp)
        return s.data

    def get_bmi_classification(self, obj=None):
        data = LatestReadings(obj)
        return data.get_bmi_classification(obj)

    def get_bmr(self, obj=None):
        data = LatestReadings(obj)
        return data.get_bmr(obj)
    
    def get_bp_classification(self, obj=None):
        data = LatestReadings(obj)
        return data.get_bp_classification(obj)

    def get_sugar_classification(self, obj=None):
        data = LatestReadings(obj)
        return data.get_sugar_classification(obj)

    def get_cholesterol_classification(self, obj=None):
        data = LatestReadings(obj)
        return data.get_cholesterol_classification(obj)
    
