from django.contrib.auth.models import User, AnonymousUser

def v_make_random_password():
	return User.objects.make_random_password(length=6,allowed_chars='1234567890')