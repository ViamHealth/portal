from django.conf import settings

FACEBOOK_APP_ID = settings.FACEBOOK_APP_ID
FACEBOOK_APP_SECRET = settings.FACEBOOK_APP_SECRET

import requests
from django.contrib.auth.models import User
from api.users.models import UserProfile

import datetime
import facebook
import urllib


def get_user_facebook_data(token):
    graph = facebook.GraphAPI(token)
    profile = graph.get_object("me")
    return profile

#do not send both token and data as None
def facebook_create_user(data):
    fb_username = data.get('username',None)

    if fb_username is None:
        username = generate_random_username()
    else:
        username = fb_username

    email = data.get('email',None)
    first_name = data.get('first_name',None)
    last_name = data.get('last_name',None)

    user = User.objects.create_user(username=username, email=email,first_name=first_name,last_name=last_name)
    facebook_populate_profile(data,user)

    return user

def facebook_populate_profile(user,data,token):
    profile =  UserProfile.objects.get_or_create(user=user)[0]
    
    if not profile.gender:
	    gender = data.get('gender',None)
	    if gender is not None:
	    	gender = gender.upper()
	    	profile.gender = gender


    fb_profile_id = data.get('id',None)
    if not profile.fb_profile_id:
    	profile.fb_profile_id = fb_profile_id

    if not profile.date_of_birth:
	    date_of_birth = data.get('birthday',None)
	    if date_of_birth is not None:
	    	date_of_birth = datetime.datetime.strptime(date_of_birth, "%m/%d/%Y").strftime("%Y-%m-%d")
	    	profile.date_of_birth = date_of_birth

    if not profile.mobile:
    	mobile = data.get('mobile',None)
    	if mobile is not None:
    		profile.mobile = mobile

    fb_username = data.get('username',None)
    if not profile.fb_username and fb_username is not None:
    	profile.fb_username = fb_username

    if not profile.address:
    	location = data.get('location',None)
    	if location is not None:
    		location = location.name
    		profile.address = location

    #picture = data.get('picture',None)
    picture = None
    #Move this code to graph api
    pic_resp = requests.get('https://graph.facebook.com/me/picture?redirect=false&type=large', params={'access_token':token} )

    pic_data = pic_resp.json()
    pic_data = pic_data['data']
    
    pic_is_set = pic_data.get('is_silhouette',None)
    if pic_is_set is not None and pic_is_set != 'true':
    	pic_url = pic_data.get('url',None)

    	if pic_url is not None:
            from django.core.files import File

            urllib.urlretrieve(pic_url, "/tmp/s3/pp-"+fb_profile_id+'.jpg')
            f = open("/tmp/s3/pp-"+fb_profile_id+'.jpg')
            pic = File(f)

    		
            profile.profile_picture = pic
    
    profile.save()

