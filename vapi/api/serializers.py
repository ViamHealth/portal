from django.contrib.auth.models import User
from api.models import *
from rest_framework import serializers
from datetime import datetime
from api.serializers_helper import *

from allauth.socialaccount.models import *


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
    access_token = serializers.CharField()

    def validate(self, attrs):
        access_token = attrs.get('access_token').strip()
        if access_token:
            d1 = datetime.now()
            try:
                socialtoken = SocialToken.objects.get(token=access_token)
                user = socialtoken.account.user
            except SocialToken.DoesNotExist:
                raise serializers.ValidationError('Unable to login with provided credentials')    
            if user:
                if not user.is_active:
                    raise serializers.ValidationError('User account is disabled.')
                attrs['user'] = user
                return attrs
            else:
                raise serializers.ValidationError('Unable to login with provided credentials.')
        else:
            raise serializers.ValidationError('Must include "access_token"')
