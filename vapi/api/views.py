# Create your views here.
from django.contrib.auth.models import User, Group
from rest_framework import viewsets
from api.models import Healthfile, HealthfileTag
from api.serializers import UserSerializer, GroupSerializer, HealthfileSerializer, HealthfileTagSerializer
from rest_framework.authtoken.models import Token
from django.core.signals import request_started
from rest_framework.views import APIView
from rest_framework import status
from rest_framework import parsers
from rest_framework import renderers
from rest_framework.response import Response
from rest_framework.authtoken.serializers import AuthTokenSerializer

#Temporary create code for all users once.
#for user in User.objects.all():
#    Token.objects.get_or_create(user=user)
    

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

class ObtainAuthToken(APIView):
    throttle_classes = ()
    permission_classes = ()
    parser_classes = (parsers.FormParser, parsers.MultiPartParser, parsers.JSONParser,)
    renderer_classes = (renderers.JSONRenderer,) 
    model = Token

    def post(self, request):
        serializer = AuthTokenSerializer(data=request.DATA)
        if serializer.is_valid():
            token, created = Token.objects.get_or_create(user=serializer.object['user'])
            return Response({'token': token.key, 'user_id': serializer.object['user'].id})
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)
