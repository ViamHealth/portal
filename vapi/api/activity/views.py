from api.views_helper import *
from .models import *
from .serializers import *
from rest_framework import viewsets, permissions


class PhysicalActivityViewSet(ViamModelViewSetNoUser):
    model = PhysicalActivity
    serializer_class = PhysicalActivitySerializer


class UserPhysicalActivityViewSet(ViamModelViewSet):
    model = UserPhysicalActivity
    filter_fields = ('user','activity_date',)

    def get_serializer_class(self):
        if self.request.method in permissions.SAFE_METHODS:
            return UserPhysicalActivitySerializer
        else:
            return UserPhysicalActivityCreateSerializer

