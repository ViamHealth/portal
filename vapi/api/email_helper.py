from django.conf import settings
import boto
from boto.s3.key import Key
from django.core.mail import send_mail, EmailMessage
import pprint


FROM_VIAM_EMAIL = getattr(settings, "FROM_VIAM_EMAIL", None)
ENABLE_EMAIL_SANDBOX = getattr(settings, "ENABLE_EMAIL_SANDBOX", None)

def send_mail(subject, message, recipient_list, from_email=None ):
	html_message = True
	if ENABLE_EMAIL_SANDBOX is not None and ENABLE_EMAIL_SANDBOX == True:
		recipient_list = ['kunal.rachhoya@viamhealth.com','narendra.mudigal@viamhealth.com',]

	try:
		if from_email is None:
			from_email = FROM_VIAM_EMAIL
		email = EmailMessage(subject, message, from_email, recipient_list)
		if html_message:
		    email.content_subtype = 'html'
		email.send()
		return True
	except Exception as e:
		print '%s (%s)' % (e.message, type(e))
		return False

def signup_email(email):
	subject = 'Welcome to Viamhealth'
	message = 'You have signed up on Viamhealth.<br/> Your username = ' + str(email)
	send_mail(subject, message, [email])

def invite_new_email(invited, inviter, password):
	by_invited = ''
	
	if inviter.first_name is not None and str(inviter.first_name) != '':
		by_invited = 'by '+ str(inviter.first_name)
	email = invited.email
	subject = 'You have been invited to Viamhealth'
	message = 'You have invited to join Viamhealth ' + by_invited + '.<br/> Your username = ' + str(email) + '<br/> Your password = '+ password
	send_mail(subject, message, [email])

def invite_existing_email(invited, inviter):
	by_invited = ''
	
	if inviter.first_name is not None and str(inviter.first_name) != '':
		by_invited = 'by '+ str(inviter.first_name)
	email = invited.email
	subject = 'You have been invited to Viamhealth'
	message = 'You have invited to join Viamhealth ' + by_invited + '.<br/> Your username = ' + str(email)
	send_mail(subject, message, [email])
