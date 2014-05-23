from rest_framework.views import APIView
from rest_framework.response import Response
from rest_framework import authentication, permissions, status
from django.http import Http404
from .models import *

class InsertUserConditions(APIView):
	permission_classes = (permissions.IsAuthenticated,)

	def post(self, request, format=None):
		list_condistions = request.DATA.get('list_condistions',None)
		fuser = self.request.QUERY_PARAMS.get('user', None)
		if list_condistions is None:
			result = {}
			result['non_field_error'] = 'Provide list_condistions'
			return Response(result,status=status.HTTP_400_BAD_REQUEST)
		else:
			try:
				user = User.objects.get(pk=fuser)
				conditions = list_condistions.split(",")
				for condition in conditions:
					userConditionTemp = UserConditionTemp(user=user, condition=condition)
					userConditionTemp.save()
				return Response(status=status.HTTP_204_NO_CONTENT)
			except User.DoesNotExist:
				raise Http404
