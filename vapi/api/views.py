# Create your views here.
from django.contrib.auth.models import User, Group
from rest_framework import viewsets
from api.models import Healthfile, HealthfileTag
from api.serializers import UserSerializer, GroupSerializer, HealthfileSerializer, HealthfileTagSerializer
from rest_framework.authtoken.models import Token
from django.core.signals import request_started

#Temporary create code for all users once.
for user in User.objects.all():
    Token.objects.get_or_create(user=user)
    

class UserViewSet(viewsets.ModelViewSet):
    """
    API endpoint that allows users to be viewed or edited.
    """
    queryset = User.objects.all()
    serializer_class = UserSerializer

class GroupViewSet(viewsets.ModelViewSet):
    """
    API endpoint that allows groups to be viewed or edited.
    """
    queryset = Group.objects.all()
    serializer_class = GroupSerializer

class HealthfileViewSet(viewsets.ModelViewSet):
    """
    API endpoint that allows groups to be viewed or edited.
    """
    queryset = Healthfile.objects.all()
    serializer_class = HealthfileSerializer

class HealthfileTagViewSet(viewsets.ModelViewSet):
    """
    API endpoint that allows groups to be viewed or edited.
    """
    queryset = HealthfileTag.objects.all()
    serializer_class = HealthfileTagSerializer
