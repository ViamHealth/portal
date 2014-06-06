from rest_framework.views import APIView
from rest_framework.response import Response
from rest_framework import authentication, permissions, status
from django.http import Http404
from .models import *
import datetime
from api.goals.models import UserBloodPressureReading
from django.contrib.auth.models import User

class ListUserTasks(APIView):
	permission_classes = (permissions.IsAuthenticated,)
	def get(self, request, format=None):
		result = []
		usertasks = UserTask.objects.filter(user=request.user).order_by('-weight','-updated_at')[:7]
		for ut in usertasks:
			obj = {}
			obj['choice_1_message'] = ''
			obj['choice_2_message'] = ''
			obj['id'] = ut.id
			obj['message'] = ut.task.message
			obj['label_choice_1'] = ut.task.label_choice_1
			obj['label_choice_2'] = ut.task.label_choice_2
			if ut.task.choice_1_message is not None:
				obj['choice_1_message'] = ut.task.choice_1_message.feedback
			if ut.task.choice_2_message is not None:
				obj['choice_2_message'] = ut.task.choice_2_message.feedback
			obj['set_choice'] = ut.set_choice
			obj['weight'] = ut.weight
			obj['created_at'] = ut.created_at
			obj['updated_at'] = ut.updated_at
			obj['is_deleted'] = ut.is_deleted
			result.append(obj)

		fin = {}
		fin['count'] = len(result)
		fin['next'] = None
		fin['previous'] = None
		fin['results'] = result
		return Response(fin)

class UpdateSetChoiceTask(APIView):
	permission_classes = (permissions.IsAuthenticated,)

	def post(self, request, task_id, format=None):
		choice = request.DATA.get('set_choice',None)
		if task_id is None or choice is None or (choice != "1" and choice != "2" ):
			result = {}
			result['non_field_error'] = 'Provide a valid task id and set_choice'
			return Response(result,status=status.HTTP_400_BAD_REQUEST)
		else:
			try:
				userTask = UserTask.objects.get(user=request.user, id=task_id)
				userTask.set_choice = choice
				userTask.updated_by = request.user
				userTask.save()
				return Response(status=status.HTTP_204_NO_CONTENT)
			except UserTask.DoesNotExist:
				raise Http404

class UpdateBloodPressureTask(APIView):
	permission_classes = (permissions.IsAuthenticated,)

	def post(self, request, task_id, format=None):
		systolic_pressure = request.DATA.get('systolic_pressure',None)
		diastolic_pressure = request.DATA.get('diastolic_pressure',None)
		pulse_rate = request.DATA.get('pulse_rate',None)
		user = request.DATA.get('user',None)
		
		if task_id is None:
			result = {}
			result['non_field_error'] = 'Provide a valid task id'
			return Response(result,status=status.HTTP_400_BAD_REQUEST)

		if user is None:
			result = {}
			result['non_field_error'] = 'Provide a valid user'
			return Response(result,status=status.HTTP_400_BAD_REQUEST)
		try:
			user = User.objects.get(pk=user)
		except User.DoesNotExist:
			result = {}
			result['non_field_error'] = 'Provide a valid user'
			return Response(result,status=status.HTTP_400_BAD_REQUEST)
		
		if systolic_pressure is None or diastolic_pressure is None or pulse_rate is None:
			result = {}
			result['non_field_error'] = 'Provide a valid Blood pressure data'
			return Response(result,status=status.HTTP_400_BAD_REQUEST)

		reading_date = datetime.datetime.today()

		try:
			userTask = UserTask.objects.get(user=request.user, id=task_id)
			
			userBloodPressureReading = UserBloodPressureReading(
				user=user,
				systolic_pressure=systolic_pressure,
				diastolic_pressure=diastolic_pressure,
				pulse_rate=pulse_rate,
				reading_date=reading_date,
				updated_by=request.user)
			userBloodPressureReading.save()

			userTask.blood_pressure_reading = userBloodPressureReading
			userTask.updated_by = request.user
			userTask.save()
			return Response(status=status.HTTP_204_NO_CONTENT)
		except UserTask.DoesNotExist:
			raise Http404


