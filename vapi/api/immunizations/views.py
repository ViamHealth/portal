from api.views_helper import *
from .models import *
from .serializers import *
from rest_framework import viewsets, permissions

class ImmunizationViewSet(ViamModelViewSetNoUser):
    model = Immunization
    serializer_class = ImmunizationSerializer

class UserImmunizationViewSet(ViamModelViewSet):
    model = UserImmunization
    serializer_class = UserImmunizationSerializer
    
    def list(self, request):
        serializer = UserImmunizationListSerializer(context={'request': request})
        return Response(serializer.data)

