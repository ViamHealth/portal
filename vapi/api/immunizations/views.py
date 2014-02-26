from api.views_helper import *
from .models import *
from .serializers import *
from rest_framework import viewsets, permissions

class ImmunizationViewSet(ViamModelViewSetNoUser):
    model = Immunization
    serializer_class = ImmunizationSerializer

class UserImmunizationViewSet(viewsets.ModelViewSet):
    model = UserImmunization
    serializer_class = UserImmunizationSerializer
    
    def list(self, request):
        serializer = UserImmunizationListSerializer()
        return Response(serializer.data)

