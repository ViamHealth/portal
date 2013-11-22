from django.contrib.auth.models import User
from .models import *
from rest_framework import serializers
from django.core.validators import validate_email
from django.core.exceptions import ValidationError
from api.serializers_helper import *

class UserProfileSerializer(serializers.ModelSerializer):
    profile_picture_url = serializers.Field(source='profile_picture_url')
    class Meta:
        model = UserProfile
        fields = ( 'gender', 'date_of_birth', 'profile_picture_url','mobile','blood_group','fb_profile_id','fb_username','organization', 'street','city','state','country','zip_code','lattitude','longitude','address',)

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
    """
    def validate_username(self, attrs, source):
        value = attrs[source]   
        try:
            validate_email( value )
            return attrs
        except ValidationError:
            raise serializers.ValidationError("Enter a valid e-mail address.")
    """
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