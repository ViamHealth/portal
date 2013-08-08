from django.contrib.auth.models import User, AnonymousUser
from rest_framework import viewsets
from api.models import *
from api.serializers import *
from rest_framework.authtoken.models import Token
from django.core.signals import request_started
from rest_framework.views import APIView
from rest_framework.generics import ListAPIView
#from rest_framework.authtoken.serializers import AuthTokenSerializer
from rest_framework import permissions, renderers, parsers, status
from rest_framework.decorators import api_view, link, action
from rest_framework.mixins import DestroyModelMixin
from rest_framework.reverse import reverse
from rest_framework.response import Response
import pprint
from django.db.models import Q
from django.http import Http404
from itertools import chain
import time
from datetime import datetime, timedelta
from django.shortcuts import get_object_or_404
from rest_framework.parsers import MultiPartParser
import mimetypes
from django.core import exceptions
from django.contrib.auth.hashers import *

class FamilyPermission(permissions.BasePermission):
    def check_family_permission(self,user_id, family_user_id):
        """
        User id : current authenticated user
        Family User id : current profile
        """
        if user_id == family_user_id:
            return True

        has_permission = False
        qqueryset = UserGroupSet.objects.filter(user_id__in=[user_id,family_user_id],group_id__in=[user_id,family_user_id],status='ACTIVE')
        for p in qqueryset:
            if p.group.id != p.user.id:
                has_permission = True

        return has_permission

    #TODO:optimize function
    def has_permission(self, request, view):
        has_permission = False
        family_user_id = request.QUERY_PARAMS.get('user', None)
        
        if family_user_id is None:
            #List page and
            #Current user is the family user
            return True
        family_user_id = int(family_user_id)
        user_id = int(request.user.id)
        return self.check_family_permission(user_id, family_user_id)

    def has_object_permission(self, request, view, obj):
        family_user_id =  int(obj.user.id)
        user_id = int(request.user.id)
        return self.check_family_permission(user_id, family_user_id)
   

 
class ViamModelViewSet(viewsets.ModelViewSet):
    
    permission_classes = (permissions.IsAuthenticated,FamilyPermission,)
    filter_fields = ('user',)

    #Custom helper functions
    def get_user_object(self):
        fuser = self.request.QUERY_PARAMS.get('user', None)
        if fuser is not None:
            return User.objects.get(pk=fuser)
        else:
            return self.request.user

    #Over riding viewset functions
    def get_queryset(self):
        queryset = self.model.objects.filter(status='ACTIVE')
        if self.request.method not in permissions.SAFE_METHODS:
            return queryset
        user = self.request.QUERY_PARAMS.get('user', None)
        if user is not None:
            queryset = queryset.filter(user=user)
        else:
            queryset = queryset.filter(user=self.request.user)
        return queryset

    def get_object(self, pk=None):
        try:
            o = self.model.objects.get(pk=pk,status='ACTIVE')
            self.check_object_permissions(self.request, o)
            return o
        except self.model.DoesNotExist:
            raise Http404

    def pre_save(self, obj):
        obj.user = self.get_user_object()
        obj.updated_by = self.request.user

    def retrieve(self, request, pk=None):
        m = self.get_object(pk)
        serializer = self.get_serializer(m)
        return Response(serializer.data)

    def create(self, request, format=None):
        serializer = self.get_serializer(data=request.DATA,)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data, status=status.HTTP_201_CREATED)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    def update(self, request, pk=None):
        m = self.get_object(pk)
        serializer = self.get_serializer(m, data=request.DATA)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    def destroy(self, request, pk=None):
        m = self.get_object(pk)
        m.status = 'DELETED'
        m.updated_by = self.request.user
        m.save()
        return Response(status=status.HTTP_204_NO_CONTENT)

"""
function to get object , with ACTIVE status

def global_get_object(view):
    queryset = view.get_queryset()
    filter = {}
    filter[view.lookup_field] = view.kwargs[view.lookup_field]
    model = get_object_or_404(queryset, **filter)
    #Redundant as queryset will have auto check
    #view.check_object_permissions(view.request, model)
    return model
"""



"""
function used for creating and updating]
"""
def global_create_update(request, view, pk=None, data=None):
    serializerObj = view.get_serializer_class()
    if data is None:
        data = request.DATA
    if pk is not None:
        mObj = view.get_object()
        serializer = serializerObj(mObj, data=data, context={'request': request})
    else:
        serializer = serializerObj(data=data, context={'request': request})
    if serializer.is_valid():
        serializer.object.user = global_get_user_object(request)
        serializer.object.updated_by = request.user
        serializer.save()
        return Response(serializer.data, status=status.HTTP_201_CREATED)
    return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)