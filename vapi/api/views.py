# Create your views here.
from django.contrib.auth.models import User, Group, AnonymousUser
from rest_framework import viewsets
from api.models import *
from api.serializers import UserSerializer, GroupSerializer, HealthfileSerializer, HealthfileTagSerializer
from rest_framework.authtoken.models import Token
from django.core.signals import request_started
from rest_framework.views import APIView
from rest_framework import status
from rest_framework import parsers
from rest_framework import renderers
from rest_framework.response import Response
#from rest_framework.authtoken.serializers import AuthTokenSerializer
from rest_framework import generics
from rest_framework.decorators import api_view
from rest_framework.mixins import DestroyModelMixin
from rest_framework.reverse import reverse
from rest_framework.response import Response
import pprint
from django.db.models import Q


#Temporary create code for all users once.
for user in User.objects.all():
    Token.objects.get_or_create(user=user)
    

class UserViewsSet(viewsets.ModelViewSet):
    """
    API endpoint that allows users to be viewed or edited.
    """
    queryset = User.objects.all()
    serializer_class = UserSerializer


class UserFamilyViewSet(APIView):
    #parser_classes = (parsers.FormParser, parsers.MultiPartParser, parsers.JSONParser,)
    #renderer_classes = (renderers.JSONRenderer,) 
    #model = User

    def get(self, request):
        qqueryset = UsersMap.objects.filter(
            Q(connection_status='ACTIVE'),
            Q(initiatior_user_id=request.user.id) | Q(connected_user_id=request.user.id)
        )
        users = [p.connected_user for p in qqueryset]
        serializer = UserSerializer(users)
        return Response(serializer.data)

class UserCurrentViewSet(APIView):
    #parser_classes = (parsers.FormParser, parsers.MultiPartParser, parsers.JSONParser,)
    #renderer_classes = (renderers.JSONRenderer,) 
    #model = User

    def get(self, request):
        serializer = UserSerializer(request.user)
        return Response(serializer.data)

class GroupViewSet(viewsets.ModelViewSet):
    """
    API endpoint that allows groups to be viewed or edited.
    """
    queryset = Group.objects.all()
    serializer_class = GroupSerializer

class HealthfilesViewSet(viewsets.ModelViewSet):
    """
    API endpoint that allows groups to be viewed or edited.
    """
    queryset = Healthfile.objects.all()
    serializer_class = HealthfileSerializer

class HealthfileViewSet(generics.ListCreateAPIView):
    model = Healthfile
    serializer_class = HealthfileSerializer

class HealthfileTagViewSet(viewsets.ModelViewSet):
    """
    API endpoint that allows groups to be viewed or edited.
    """
    queryset = HealthfileTag.objects.all()
    serializer_class = HealthfileTagSerializer

#class ObtainAuthToken(APIView):
#    throttle_classes = ()
#    permission_classes = ()
#    parser_classes = (parsers.FormParser, parsers.MultiPartParser, parsers.JSONParser,)
#    renderer_classes = (renderers.JSONRenderer,) 
#    model = Token

#    def post(self, request):
#        serializer = AuthTokenSerializer(data=request.DATA)
#        if serializer.is_valid():
#            token, created = Token.objects.get_or_create(user=serializer.object['user'])
#            return Response({'token': token.key, 'user_id': serializer.object['user'].id})
#        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)
