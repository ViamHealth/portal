from api.views_helper import *
from api.email_helper import *
from django.db.models import Q

from django.contrib.auth.models import User, AnonymousUser
from rest_framework import viewsets
from .models import *
from .serializers import *

from api.util.helper import *

from rest_framework.authtoken.models import Token
from rest_framework import permissions,  status, exceptions
from rest_framework.decorators import api_view, link, action
from rest_framework.response import Response
from django.http import Http404
from itertools import chain
import datetime
from django.core.validators import validate_email
from django.core.exceptions import ValidationError
from api.vfacebook import *
import urllib
from django.core.files import File
from django.conf import settings

@api_view(['GET',])
def logout(request):
    if request.user.is_authenticated():
        Token.objects.get(user=request.user).delete()
        Token.objects.get_or_create(user=request.user)
        return Response(status=status.HTTP_204_NO_CONTENT)
    else:
        return Response(status=status.HTTP_401_UNAUTHORIZED)


class ForgotPasswordView(viewsets.ViewSet):
    
    permission_classes=(permissions.AllowAny,)

    @action(methods=['POST',])
    def forgot_password_email(self, request, format=None):
        email = request.DATA.get('email')
        serializer = ForgotPasswordEmailSerializer(data=request.DATA, context={'request': request})
        if serializer.is_valid():
            user = serializer.object.get('user',None)
            if user is not None:
                password = v_make_random_password()
                user.password = make_password(password)
                user.save()
                forgot_password_email(user,password)
            return Response(status=status.HTTP_204_NO_CONTENT)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    @action(methods=['POST',])
    def forgot_password_mobile(self, request, format=None):
        mobile = request.DATA.get('mobile')
        serializer = ForgotPasswordMobileSerializer(data=request.DATA, context={'request': request})
        if serializer.is_valid():
            user = serializer.object.get('user',None)
            if user is not None:
                password = v_make_random_password()
                password = '1111'
                user.password = make_password(password)
                user.save()
                #forgot_password_email(user,password)
            return Response(status=status.HTTP_204_NO_CONTENT)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)


class SignupView(viewsets.ViewSet):
    model = User
    permission_classes=(permissions.AllowAny,)

    @action(methods=['POST',])
    def user_signup(self, request, format=None):
        username = request.POST.get("username",None)
        email = request.POST.get("email",None)
        mobile = request.POST.get("mobile",None)
        password = request.POST.get("password",None)

        if mobile is not None:
            try:
                UserProfile.objects.get(mobile = mobile)
                result = {}
                result['mobile'] = 'Mobile number already exists'
                return Response(result,status=status.HTTP_400_BAD_REQUEST)
            except UserProfile.DoesNotExist:
                user = User(username=generate_random_username(),email=None,password=make_password(password))
                user.save()
                profile = user.profile
                profile.mobile = mobile
                profile.updated_by = user
                profile.save()
                bmi_profile= user.bmi_profile
                bmi_profile.updated_by = user
                bmi_profile.save()

                #UserProfile.objects.get_or_create(user=user, defaults={'mobile':mobile})
                #UserBmiProfile.objects.get_or_create(user=user,defaults={'updated_by': user})
                pserializer = UserSerializer(user, context={'request': request})
                return Response(pserializer.data, status=status.HTTP_201_CREATED)

        else:
            if email is not None:
                serializer = EmailUserSignupSerializer(data={'username':generate_random_username(),'email':email,'password':password,})
            else :
                serializer = UserSignupSerializer(data={'username':username,'email':None,'password':password,})

            if serializer.is_valid():
                serializer.object.password = make_password(serializer.object.password)
                serializer.save()
                user = User.objects.get(pk=serializer.object.id)
                profile = user.profile
                profile.updated_by = user
                profile.save()
                bmi_profile= user.bmi_profile
                bmi_profile.updated_by = user
                bmi_profile.save()
                #UserProfile.objects.get_or_create(user=user)
                #UserBmiProfile.objects.get_or_create(user=user,defaults={'updated_by': user})

                pserializer = UserSerializer(user, context={'request': request})
                

                if email is not None:
                    signup_email(user.email)

                return Response(pserializer.data, status=status.HTTP_201_CREATED)
            return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

class InviteView(viewsets.ViewSet):
    model = User
    permission_classes = (permissions.IsAuthenticated,)
    @action(methods=['POST',])
    def user_invite(self, request, format=None):
        serializer = UserInviteSerializer(data=request.DATA, context={'request': request})
        email = request.DATA.get('email',None)

        if serializer.is_valid() and email is not None:
            try:
                user = User.objects.get(email=email)
                invite_existing_email(user, request.user)
            except User.DoesNotExist:
                password = v_make_random_password()
                user = User.objects.create_user(username=generate_random_username(), email=email,password=password)
                invite_new_email(user, request.user, password)
                bmi_profile= user.bmi_profile
                bmi_profile.updated_by = request.user
                bmi_profile.save()

            #UserProfile.objects.get_or_create(user=user)
            #UserBmiProfile.objects.get_or_create(user=user,defaults={'updated_by': request.user})
            pserializer = UserSerializer(user, data=serializer.object, context={'request': request})

            return Response(pserializer.data, status=status.HTTP_201_CREATED)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

class ShareView(viewsets.ViewSet):
    
    permission_classes = (permissions.IsAuthenticated,)
    @action(methods=['POST',])
    def user_share(self, request, format=None):
        serializer = UserShareSerializer(data=request.DATA, context={'request': request})

        if serializer.is_valid():
            
            share_user = serializer.object.get('share_user',None)
            email = serializer.object.get('email',None)
            if share_user.id != request.user.id:
                try:
                    UserGroupSet.objects.get(group=share_user, user=request.user,status='ACTIVE')
                except UserGroupSet.DoesNotExist:
                    try:
                        UserGroupSet.objects.get(group=request.user, user=share_user,status='ACTIVE')
                    except UserGroupSet.DoesNotExist:
                        return Response(status=status.HTTP_403_FORBIDDEN)
            if serializer.object.get('is_self',True):
                # Self user. validated. All is well. Just update the email, create password and send email
                share_user.email = email
                share_user.password = make_password(v_make_random_password())
                share_user.save()
                invite_new_email(share_user, request.user, share_user.password)

            else:
                try:
                    # sharing with already existing user. Great. Move on
                    user = User.objects.get(email=email)
                    if user.password is None or user.password == '':
                        password = v_make_random_password()
                        user.password = make_password(password)
                        user.save()
                        invite_new_email(user, request.user, password)
                except User.DoesNotExist:
                    password = v_make_random_password()
                    user = User.objects.create_user(username=generate_random_username(), email=email,password=password)
                    profile = user.profile
                    profile.updated_by = user
                    profile.save()
                    bmi_profile= user.bmi_profile
                    bmi_profile.updated_by = user
                    bmi_profile.save()
                    #UserProfile.objects.get_or_create(user=user)
                    #UserBmiProfile.objects.get_or_create(user=user,defaults={'updated_by': request.user})        
                    invite_new_email(user, request.user, password)

                try:
                    #check if connection exists. Do nothing
                    UserGroupSet.objects.get(group=share_user, user=user)
                except UserGroupSet.DoesNotExist:
                    try:
                        UserGroupSet.objects.get(group=user, user=share_user)
                    except UserGroupSet.DoesNotExist:
                        #create connection and send share email
                        umap = UserGroupSet(group=share_user, user=user,status='ACTIVE',updated_by=request.user);
                        umap.save()
                        share_user_email(user, request.user, share_user)
            return Response(status=status.HTTP_204_NO_CONTENT)
        else:
            return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

#TODO: Move to mixins for less  code
class UserView(viewsets.ViewSet):
    permission_classes = (permissions.IsAuthenticated,)


    def has_permission_user_view(self, request):
        family_user_id = self.kwargs.get('pk')
        if family_user_id is None:
            return True
        family_user_id = int(family_user_id)
        user_id = int(request.user.id)
        if user_id == family_user_id:
            return True
        has_permission = False
        qqueryset = UserGroupSet.objects.filter(user_id__in=[user_id,family_user_id],group_id__in=[user_id,family_user_id],status='ACTIVE',is_deleted=False)
        for p in qqueryset:
            if(p.user_id != p.group_id):
                has_permission = True
        if has_permission:
            return True
        else:
            raise exceptions.PermissionDenied

    def get_object(self, pk):
        try:
            self.has_permission_user_view(self.request)
            user = User.objects.get(pk=pk,is_active=True)
            return user
        except User.DoesNotExist:
            raise Http404

    def list(self, request, format=None):
        """
        List of users accessible to current logged in user
        """
        qqueryset = UserGroupSet.objects.filter(Q(group=request.user)|Q(user=request.user)).filter(status='ACTIVE')
        sync_ts = request.QUERY_PARAMS.get('last_sync', None)
        if sync_ts is None:
            qqueryset.filter(is_deleted=False)

        f = '%Y-%m-%d %H:%M:%S'

        users = []
        users = list(set(users))
        #users = [p.user for p in qqueryset]
        for p in qqueryset:
            if p.group.id != request.user.id:
                if sync_ts is not None:
                    ut = p.group.updated_at.replace(tzinfo=None)
                    but = p.group.bmi_profile.updated_at.replace(tzinfo=None)
                    st = datetime.datetime.strptime(sync_ts, f)
                    if st < ut or st < but:
                        p.group.is_deleted = p.is_deleted
                        users = [p.group] + users
                else:
                    if p.is_deleted == False:
                        p.group.is_deleted = p.is_deleted
                        users = [p.group] + users
            if p.user.id != request.user.id:
                if sync_ts is not None:
                    ut = p.user.profile.updated_at.replace(tzinfo=None)
                    but = p.user.bmi_profile.updated_at.replace(tzinfo=None)
                    st = datetime.datetime.strptime(sync_ts, f)
                    if st < ut or st < but:
                        p.user.is_deleted = p.is_deleted
                        users = [p.user] + users
                else:
                    if p.is_deleted == False:
                        p.user.is_deleted = p.is_deleted
                        users = [p.user] + users

        self_user = request.user
        

        if sync_ts is not None:
            ut = self_user.profile.updated_at.replace(tzinfo=None)
            but = self_user.bmi_profile.updated_at.replace(tzinfo=None)
            st = datetime.datetime.strptime(sync_ts, f)
            if st < ut or st < but:
                self_user.is_deleted = False
                users = [self_user] + users
        else:
            self_user.is_deleted = False
            users = [self_user] + users
        

        
        sync_ts = request.QUERY_PARAMS.get('last_sync', None)
        if sync_ts is None:
            serializer = UserListSerializer(users, fields=('id',  'username', 'email', 'first_name', 'last_name', 'profile', 'bmi_profile'), many=True)
        else:
            serializer = UserListSerializer(users,fields=('id',  'username', 'email', 'first_name', 'last_name', 'profile', 'bmi_profile','is_deleted'), many=True)    

        return Response(serializer.data)

    def create_connections(self, request, user):
        try:
            umap = UserGroupSet.objects.get(group=request.user, user=user,is_deleted=False)
            if umap.status == 'ACTIVE':
                #why add again . 400 for you moron
                result = {}
                result['email'] = 'User already added as a family member'
                return Response(result,status=status.HTTP_400_BAD_REQUEST)
            else:
                umap.status = 'ACTIVE'
                umap.updated_by = request.user
                umap.save()
        except UserGroupSet.DoesNotExist:
            try:
                umap = UserGroupSet.objects.get(group=user, user=request.user,is_deleted=False)
                if umap.status == 'ACTIVE':
                    result = {}
                    result['email'] = 'User already added as a family member'
                    return Response(result,status=status.HTTP_400_BAD_REQUEST)
                else:
                    umap.status = 'ACTIVE'
                    umap.updated_by = request.user
                    umap.save()
            except UserGroupSet.DoesNotExist:
                #create connection
                umap = UserGroupSet(group=request.user, user=user,status='ACTIVE',updated_by=request.user);
                umap.save()

        if user.email:
            invite_existing_email(user, request.user)
        """
        profile = user.get_profile()
        if profile.mobile:
            #send sms
        """
        serializer = UserSerializer(user)
        return Response(serializer.data, status=status.HTTP_201_CREATED)


    def create(self, request, format=None):
        """
        Create new family user for current logged in user
        
        email -- email id
        """

        email_exists = False
        mobile_exists = False

        data = request.DATA.copy()
        username = None
        first_name = data.get('first_name',None)
        last_name = data.get('last_name', None)
        email = data.get('email',None)
        password = v_make_random_password()
        mobile = data.get('mobile',None)

        username = generate_random_username()

        if email and len(email.strip()):
            email = email.strip()
            try:
                validate_email(email)
            except ValidationError:
                result = {}
                result['email'] = 'Enter a valid e-mail address.'
                return Response(result,status=status.HTTP_400_BAD_REQUEST)

        if mobile and len(mobile.strip()):
            mobile = mobile.strip()
            #TODO mobile validation

        if email is not None:
            try:
                user_email = User.objects.get(email=email)
                email_exists = True
            except User.DoesNotExist:
                email_exists = False

        if mobile is not None:
            try:
                user_profile_mobile = UserProfile.objects.get(mobile=mobile)
                user_mobile = user_profile_mobile.user
                mobile_exists = True
            except UserProfile.DoesNotExist:
                mobile_exists = False

        if mobile_exists and email_exists:
            if user_email.id != user_mobile.id:
                result = {}
                result['non_field_errors'] = 'Mobile and Email belong to different users'
                return Response(result,status=status.HTTP_400_BAD_REQUEST)
            else:
                return self.create_connections(request,user_email)    

        elif email_exists:
            #existing user. Bring in the permission framework, which is yet to be built
            return self.create_connections(request,user_email)
        elif mobile_exists:
            #existing user. Bring in the permission framework, which is yet to be built
            return self.create_connections(request,user_mobile)
        else:
            #create new user
            #authenticating user
            serializer = UserCreateSerializer(data={'username':username,'email':email,'password':make_password(password),'first_name':first_name,'last_name':last_name})
            if serializer.is_valid():
                serializer.save()
                user=User.objects.get(pk=serializer.data.get('id'))
                profile = user.profile
                profile.mobile = mobile
                profile.updated_by = user
                profile.save()
                bmi_profile= user.bmi_profile
                bmi_profile.updated_by = user
                bmi_profile.save()

                #UserProfile.objects.get_or_create(user=user,defaults={'mobile':mobile})
                #UserBmiProfile.objects.get_or_create(user=user,defaults={'updated_by': user})

                umap = UserGroupSet(group=request.user, user=user,status='ACTIVE',updated_by=request.user);
                umap.save()
                if email is not None:
                    invite_new_email(user, request.user, password)
                else:
                    send_no_email_user_created_alert(user,request.user)

                pserializer = UserSerializer(user)
                return Response(pserializer.data, status=status.HTTP_201_CREATED)
            return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)     

    def retrieve(self, request, pk=None):
        """
        get user with id <pk>
        """
        user = self.get_object(pk)
        serializer = UserSerializer(user, context={'request': request})
        return Response(serializer.data)

    def update(self, request, pk=None):
        """
        Update User object
        """

        user = self.get_object(pk)
        serializer = UserEditSerializer(user, data=request.DATA, context={'request': request})
        if serializer.is_valid():
            serializer.save()
            pserializer = UserSerializer(user, data=serializer.object, context={'request': request})
            return Response(pserializer.data)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    @link()
    def current_user(self, request):
        """
        get current logged in user
        """

        serializer = UserSerializer(request.user, context={'request': request})
        return Response(serializer.data)

    def destroy(self, request, pk=None):
        """
        Delete users with id <pk>
        """
        UserGroupSet.objects.filter(group=pk,user=request.user.id).update(status='DELETED',is_deleted=True,updated_by=request.user)
        UserGroupSet.objects.filter(user=pk,group=request.user.id).update(status='DELETED',is_deleted=True,updated_by=request.user)
        return Response(status=status.HTTP_204_NO_CONTENT)

    @action(methods=['POST'])
    def change_password(self, request):
        serializer = UserPasswordSerializer(data=request.DATA, context={'request': request})
        if serializer.is_valid():
            user = request.user
            user.password = make_password(serializer.object['password'])
            user.save()
            return Response(status=status.HTTP_204_NO_CONTENT)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    @action(methods=['PUT'])
    def update_profile(self, request, pk=None):
        user = self.get_object(pk)
        profile = user.profile
        fb_profile_id = request.DATA.get('fb_profile_id',None)
        fb_username = request.DATA.get('fb_username',None)
        graph_url = None
        social_uid = None
        if not profile.profile_picture:
            if profile.fb_profile_id is None and fb_profile_id is not None  :
                graph_url = 'https://graph.facebook.com/'+ str(fb_profile_id) +'/picture?redirect=false&type=large'
                social_uid =  fb_profile_id
            elif  profile.fb_username is None and fb_username is not None :
                graph_url = 'https://graph.facebook.com/'+ str(fb_username) +'/picture?redirect=false&type=large'
                social_uid = fb_username
        serializer = UserProfileSerializer(profile, data=request.DATA)
        if serializer.is_valid():
            serializer.save()
            #Too late to make this api auto fetch data from vfacebook lib.
            #forced code repetition
            #limits us to only public data
            if graph_url is not None:
                picture = None
                pic_resp = requests.get(graph_url )
                pic_data = pic_resp.json()
                pic_data = pic_data['data']
                
                pic_is_set = pic_data.get('is_silhouette',None)
                if pic_is_set is not None and pic_is_set != 'true':
                    pic_url = pic_data.get('url',None)

                    if pic_url is not None:
                        urllib.urlretrieve(pic_url,  settings.S3_LOCAL_DOWNLOAD_LOCATION+"pp-"+social_uid+'.jpg')
                        f = open(settings.S3_LOCAL_DOWNLOAD_LOCATION+"pp-"+social_uid+'.jpg')
                        pic = File(f)
                        profile.profile_picture = pic
                        profile.updated_by = request.user
                        profile.save()
            return Response(serializer.data)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    @link()
    def retrieve_bmi_profile(self, request, pk):
        user = self.get_object(pk)
        bmi_profile= user.bmi_profile
        #bmi_profile = UserBmiProfile.objects.get_or_create(user=user,defaults={'updated_by': user})[0]
        serializer = UserBmiProfileSerializer(bmi_profile, context={'request': request})
        return Response(serializer.data)

    @action(methods=['PUT'])
    def update_bmi_profile(self, request, pk=None):
        user = self.get_object(pk)
        bmi_profile= user.bmi_profile
        #bmi_profile = UserBmiProfile.objects.get_or_create(user=user,defaults={'updated_by': user})[0]
        serializer = UserBmiProfileSerializer(bmi_profile, data=request.DATA, context={'request': request})
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    @action(methods=['PUT'])
    def update_profile_pic(self, request, pk=None):
        user = self.get_object(pk)
        data = request.DATA
        profile = user.profile
        data.profile_picture = self.request.FILES['profile_picture']
        serializer = UserProfilePicSerializer(profile, data=data)
        if serializer.is_valid():
            serializer.object.profile_picture = self.request.FILES['profile_picture']
            serializer.save()
            pserializer = UserProfileSerializer(profile, data=serializer.object)
            return Response(pserializer.data)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    @action(methods=['POST'])
    def attach_facebook(self, request):
        serializer = UserFacebookConnectSerializer(data=request.DATA,context={'request': request})
        if serializer.is_valid():
            facebook_populate_profile(request.user, serializer.object.get('fb_data'),serializer.object.get('access_token'))
            pserializer = UserSerializer(request.user)
            return Response(pserializer.data)
        else:
            return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    """
    @action(methods=['PUT',])
    def sync_user(self,request,pk=None):
        import json

        data =  json.loads(request.body)
        if pk is None:
            user = User()
        else:
            user = User.objects.get(pk=pk)

        serializer = UserSyncEditSerializer(user, data=data,context={'request': request})
        if serializer.is_valid():
            profile_serializer = UserProfileSerializer(user.profile, data=data.get('profile'), context={'request':request})
            if profile_serializer.is_valid():
                bmi_profile_serializer = UserBmiProfileSerializer(user.bmi_profile, data=data.get('bmi_profile'), context={'request':request})
                if bmi_profile_serializer.is_valid():
                    serializer.save()
                    profile_serializer.save()
                    bmi_profile_serializer.save()
                    return Response(serializer.data)
                else:
                    return Response(bmi_profile_serializer.errors, status=status.HTTP_400_BAD_REQUEST)
            else:
                return Response(profile_serializer.errors, status=status.HTTP_400_BAD_REQUEST)
        else:
            return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    @action(methods=['POST',])
    def sync_user(self,request):
        import json

        data =  json.loads(request.body)
        username = generate_random_username()
        user = User.objects.create_user(username=username, email=username)
        
        serializer = UserSyncEditSerializer(user, data=data,context={'request': request})
        if serializer.is_valid():
            profile_serializer = UserProfileSerializer(user.profile, data=data.get('profile'), context={'request':request})
            if profile_serializer.is_valid():
                bmi_profile_serializer = UserBmiProfileSerializer(user.bmi_profile, data=data.get('bmi_profile'), context={'request':request})
                if bmi_profile_serializer.is_valid():
                    serializer.save()
                    profile_serializer.save()
                    bmi_profile_serializer.save()
                    return Response(serializer.data)
                else:
                    return Response(bmi_profile_serializer.errors, status=status.HTTP_400_BAD_REQUEST)
            else:
                return Response(profile_serializer.errors, status=status.HTTP_400_BAD_REQUEST)
        else:
            return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)
    """
