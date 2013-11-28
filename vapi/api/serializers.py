from django.contrib.auth.models import User
from django.core.exceptions import ValidationError
from api.models import *
from api.users.models import *
from rest_framework import serializers
from api.serializers_helper import *
from api.vfacebook import *
#from allauth.socialaccount.models import *


class MobileAuthTokenSerializer(serializers.Serializer):
    mobile = serializers.CharField()
    password = serializers.CharField()

    def validate(self, attrs):
        mobile = attrs.get('mobile').strip()
        password = attrs.get('password')

        if mobile and password:
            try:
                userprofile = UserProfile.objects.get(mobile=mobile)
                user = userprofile.user
                if user.check_password(password):
                    if not user.is_active:
                        raise serializers.ValidationError('User account is disabled.')
                    attrs['user'] = user
                    return attrs
                else:
                    raise serializers.ValidationError('Unable to login with provided credentials.')    
            except UserProfile.DoesNotExist:
                raise serializers.ValidationError('Unable to login with provided credentials.')
        else:
            raise serializers.ValidationError('Must include "mobile" and "password"')


class EmailAuthTokenSerializer(serializers.Serializer):
    email = serializers.CharField()
    password = serializers.CharField()

    def validate(self, attrs):
        email = attrs.get('email').strip()
        password = attrs.get('password')

        if email and password:
            try:
                user = User.objects.get(email=email)
                if user.check_password(password):
                    if not user.is_active:
                        raise serializers.ValidationError('User account is disabled.')
                    attrs['user'] = user
                    return attrs
                else:
                    raise serializers.ValidationError('Unable to login with provided credentials.')    
            except User.DoesNotExist:
                raise serializers.ValidationError('Unable to login with provided credentials.')
        else:
            raise serializers.ValidationError('Must include "email" and "password"')


class SocialAuthTokenSerializer(serializers.Serializer):
    access_token = serializers.CharField(required=True)

    def validate(self, attrs):
        access_token = attrs.get('access_token').strip()
        try:
            data = get_user_facebook_data(access_token)

            fb_profile_id = data.get('id',None)
            if fb_profile_id is None:
                raise serializers.ValidationError('Invalid access_token')
            try:
                profile = UserProfile.objects.get(fb_profile_id=fb_profile_id)
                user = profile.user
                if not user.is_active:
                    raise serializers.ValidationError('User account is disabled.')
                ####################User Login#################
                attrs['user'] = user
                return attrs    

            except UserProfile.DoesNotExist:
                try:
                    user = User.objects.get(email=data.get('email'))
                    #catching the user via email field. Security Flaw ?
                    if not user.is_active:
                        raise serializers.ValidationError('User account is disabled.')
                    ################ User Login + Merging of accounts ###############
                    #This user now belongs to fb_profile_id facebook user !!
                    facebook_populate_profile(user,data,access_token)
                    attrs['user'] = user
                    return attrs
                except User.DoesNotExist:
                    #raise serializers.ValidationError('Signup with facebook disabled')    
                    ############### User Signup Via Facebook ###############
                    user = facebook_create_user(data,access_token)
                    attrs['user'] = user
                    return attrs
            except MultipleObjectsReturned:
                raise serializers.ValidationError('Unable to login with provided credentials. Multiple accounts attached with same fb id')
        except ValidationError, err:
            print '; '.join(err.messages)
            raise serializers.ValidationError('Could not connect to facebook')
