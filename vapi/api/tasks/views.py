from rest_framework.views import APIView
from rest_framework.response import Response
from rest_framework import authentication, permissions
from .models import *

class ListUserTasks(APIView):
	permission_classes = (permissions.IsAuthenticated,)
	def get(self, request, format=None):
		result = []
		usertasks = UserTask.objects.filter(user=request.user).order_by('-weight','updated_at')[:5]
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