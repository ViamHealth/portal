from api.views_helper import *
from api.email_helper import *

from django.contrib.auth.models import User, AnonymousUser
from rest_framework import viewsets, filters
from api.models import *
from api.healthfiles.models import *
from api.serializers import *
from api.healthfiles.serializers import *

import hashlib, os

from rest_framework.authtoken.models import Token

from rest_framework.views import APIView
from rest_framework.authtoken.serializers import AuthTokenSerializer
from rest_framework import permissions, renderers, parsers, status
from rest_framework.decorators import api_view


from rest_framework.response import Response

from django.http import  HttpResponse
from itertools import chain

from datetime import datetime, timedelta
from django.shortcuts import get_object_or_404

#from django.core import exceptions
from django.contrib.auth.hashers import *

import boto
from boto.s3.key import Key
from django.conf import settings




#Temporary create code for all users once.
for user in User.objects.all():
    enddate = datetime.today() - timedelta(days=7)
    #pprint.pprint('deleting since the datetime')
    #pprint.pprint(enddate)
    Token.objects.filter(created__lt=enddate).delete()
    #TODO: custom algo for creating token string
    Token.objects.get_or_create(user=user)


@api_view(['GET',])
def heartbeat(request):
    return HttpResponse(status=204)

@api_view(['POST',])
def share_healthfile(request, healthfile_id):
    serializer = FileShareSerializer(data=request.DATA, context={'request': request})
    if serializer.is_valid():
        try:
            m = Healthfile.objects.get(pk=healthfile_id)

            has_permission = False
            if request.user.is_authenticated():
                pass
            else:
                return Response(status=status.HTTP_401_UNAUTHORIZED)

            user_id = int(request.user.id)

            if request.user == m.user:
                has_permission = True
            else:
                qqueryset = UserGroupSet.objects.filter(user_id__in=[user_id,m.user_id],group_id__in=[user_id,m.user_id],status='ACTIVE')
                for p in qqueryset:
                    if(p.user_id != p.group_id):
                        has_permission = True
            if has_permission:
                mm = {}
                mm['url'] = 'media/'+str(m.file)
                mm['name'] = m.name
                share_healthfile_email(request.user,serializer.object.get('email'),mm)
                return Response(status=status.HTTP_204_NO_CONTENT)
            else:
                return Response(status=status.HTTP_401_UNAUTHORIZED)
        except Healthfile.DoesNotExist:
            return Response(content, status=status.HTTP_404_NOT_FOUND)
    return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

@api_view(['GET',])
def handles3downloads(request, healthfile_id):
    LOCAL_PATH = settings.S3_LOCAL_DOWNLOAD_LOCATION

    try:
        m = Healthfile.objects.get(id=healthfile_id)

        has_permission = False
        if request.user.is_authenticated():
            pass
        else:
            return Response(status=status.HTTP_401_UNAUTHORIZED)

        user_id = int(request.user.id)

        if request.user == m.user:
            has_permission = True
        else:
            qqueryset = UserGroupSet.objects.filter(user_id__in=[user_id,m.user_id],group_id__in=[user_id,m.user_id],status='ACTIVE')
            for p in qqueryset:
                if(p.user_id != p.group_id):
                    has_permission = True
            
        if has_permission:
            try:
                conn = boto.connect_s3(settings.AWS_ACCESS_KEY_ID, settings.AWS_SECRET_ACCESS_KEY)
                
                key = conn.get_bucket(settings.AWS_STORAGE_BUCKET_NAME).get_key('media/'+str(m.file))
                # delete file first and after wards
                if os.path.exists(LOCAL_PATH+str(m.id)+'-'+m.name):
                    os.remove(LOCAL_PATH+str(m.id)+'-'+m.name)
                key.get_contents_to_filename(LOCAL_PATH+str(m.id)+'-'+m.name)
            
                response = HttpResponse(file(LOCAL_PATH+str(m.id)+'-'+m.name), content_type = m.mime_type)
                response['Content-Length'] = os.path.getsize(LOCAL_PATH+str(m.id)+'-'+m.name)
                return response
            except:
                return Response(status=status.HTTP_500_INTERNAL_SERVER_ERROR)
        else:
            return Response(status=status.HTTP_401_UNAUTHORIZED)
    except Healthfile.DoesNotExist:
        return Response(status=status.HTTP_404_NOT_FOUND)



@api_view(['GET',])
def api_root(request, format=None):
    return Response({})

class ObtainAuthToken(APIView):
    throttle_classes = ()
    permission_classes = ()
    parser_classes = (parsers.FormParser, parsers.MultiPartParser, parsers.JSONParser,)
    renderer_classes = (renderers.JSONRenderer,)
    serializer_class = AuthTokenSerializer
    model = Token

    def post(self, request):
        access_token = request.POST.get("access_token",None)
        email = request.POST.get("email",None)
        mobile = request.POST.get("mobile",None)
        if access_token is not None:
            serializer = SocialAuthTokenSerializer(data=request.DATA)
        elif email is not None:
            serializer = EmailAuthTokenSerializer(data=request.DATA)
        elif mobile is not None:
            serializer = MobileAuthTokenSerializer(data=request.DATA)
        else:
            serializer = self.serializer_class(data=request.DATA)
        if serializer.is_valid():
            token, created = Token.objects.get_or_create(user=serializer.object['user'])
            return Response({'token': token.key})
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)
