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
from rest_framework.decorators import api_view, link
from rest_framework.mixins import DestroyModelMixin
from rest_framework.reverse import reverse
from rest_framework.response import Response
import pprint
from django.db.models import Q
from django.http import Http404


#Temporary create code for all users once.
for user in User.objects.all():
    Token.objects.get_or_create(user=user)
    

#class UserViewSet(viewsets.ModelViewSet):
#    """
#    API endpoint that allows users to be viewed or edited.
#    """
#    queryset = User.objects.all()
#    serializer_class = UserSerializer

#TODO: Move to mixins for less  code
class UserList(viewsets.ViewSet):
    def list(self, request, format=None):
        qqueryset = UsersMap.objects.filter(
            Q(connection_status='ACTIVE'),
            Q(initiatior_user_id=request.user.id) | Q(connected_user_id=request.user.id)
        )
        users = [p.connected_user for p in qqueryset]
        serializer = UserSerializer(users, many=True)
        return Response(serializer.data)

    def create(self, request, format=None):
        serializer = UserSerializer(data=request.DATA)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data, status=status.HTTP_201_CREATED)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    @link()
    def current_user(self, request):
        serializer = UserSerializer(request.user)
        return Response(serializer.data)

class UserDetail(APIView):
    def check_permission(self, request , pk):
        has_permission = False
        pk = int(pk)
        user_id = int(request.user.id)
        if user_id == pk:
            return True
        qqueryset = UsersMap.objects.filter(
            Q(connection_status='ACTIVE'),
            Q(initiatior_user_id=user_id) | Q(connected_user_id=user_id)
        )
        users = [p.connected_user for p in qqueryset]
        for u in users:
            if u.id == pk:
                has_permission = True
        return has_permission

    def get_object(self, pk):
        try:
            return User.objects.get(pk=pk)
        except User.DoesNotExist:
            return Response(status=status.HTTP_404_BAD_REQUEST)

    def get(self, request, pk, format=None):
        if self.check_permission(request, pk) == False:
            return Response(status=status.HTTP_404_BAD_REQUEST)
        user = self.get_object(pk)
        serializer = UserSerializer(user)
        return Response(serializer.data)

    def put(self, request, pk, format=None):
        if self.check_permission(request, pk) == False:
            return Response(status=status.HTTP_404_BAD_REQUEST)
        user = self.get_object(pk)
        serializer = UserSerializer(user, data=request.DATA)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    def delete(self, request, pk, format=None):
        snippet = self.get_object(pk)
        snippet.delete()
        return Response(status=status.HTTP_204_NO_CONTENT)

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
