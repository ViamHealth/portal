import requests
#from django.contrib.auth.models import User, Group

import datetime
import urllib

def populate_profile(token,user):
    from api.models import *
    profile =  UserProfile.objects.get_or_create(user=user)[0]

    resp = requests.get( 'https://graph.facebook.com/me', params={'access_token': token } )

    data = resp.json()
    
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

    pic_resp = requests.get('https://graph.facebook.com/me/picture?redirect=false&type=large', params={'access_token':token} )

    pic_data = pic_resp.json()
    pic_data = pic_data['data']
    
    pic_is_set = pic_data.get('is_silhouette',None)
    if pic_is_set is not None and pic_is_set != 'true':
    	pic_url = pic_data.get('url',None)

    	if pic_url is not None:
            import urllib
            from django.core.files import File

            urllib.urlretrieve(pic_url, "/tmp/s3/pp-"+fb_profile_id+'.jpg')
            f = open("/tmp/s3/pp-"+fb_profile_id+'.jpg')
            pic = File(f)

    		
            profile.profile_picture = pic
    
    profile.save()

