from api.views_helper import *
from api.email_helper import *

from rest_framework import filters
from .models import *
from .serializers import *

from rest_framework import permissions



class ReminderViewSet(ViamModelViewSetNoStatus):
    #filter_fields = ('type')
    filter_fields = ('type',)
    #filter_backends = (filters.SearchFilter,)
    #search_fields = ('name',)
    model = Reminder
    serializer_class = ReminderSerializer

class ReminderReadingsViewSet(ViamModelViewSetNoStatus):
    filter_fields = ('reading_date',)
    model = ReminderReadings
    serializer_class = ReminderReadingsSerializer

    def get_queryset(self):
        from django.db.models import F

        queryset = self.model.objects.all()
        if self.request.method not in permissions.SAFE_METHODS:
            return queryset

        type = self.request.QUERY_PARAMS.get('type', 'ALL')

        if type == '1' or type == '2' or type == '3' or type == '4' or type == '5' or type == '6' or type == '7' :
            queryset = self.model.objects.filter(reminder__type=type, reminder_id = F('reminder__id'))

        user = self.get_user_object()
        queryset = queryset.filter(user=user)

        reading_date_value = self.request.QUERY_PARAMS.get('reading_date', None)

        if reading_date_value:
            reading_date_list = reading_date_value.split(',')
            queryset = queryset.filter(reading_date__in=reading_date_list)
        
        return queryset