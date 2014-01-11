from api.views_helper import *
from api.email_helper import *

from rest_framework import filters
from .models import *
from .serializers import *
import datetime
from rest_framework import permissions



class ReminderViewSet(ViamModelViewSet):
    filter_fields = ('type',)
    model = Reminder
    serializer_class = ReminderSerializer

    def destroy(self,request,pk=None):
        o = self.get_object()
        rr = ReminderReadings.objects.filter(reminder=o,is_deleted=False)
        for reading in rr:
            reading.soft_delete()
        return super(ReminderViewSet, self).destroy(pk)


    def end_from_today(self, request, pk=None):
        o = self.get_object()
        rr = ReminderReadings.objects.filter(reminder=o,is_deleted=False).exclude(reading_date__lte=datetime.date.today())
        for reading in rr:
            reading.soft_delete()
        try:
            rr = ReminderReadings.objects.get(reminder=o,reading_date=datetime.date.today(),is_deleted=False)
            if rr.morning_check or rr.afternoon_check or rr.evening_check or rr.night_check or rr.complete_check:
                pass
            else:
                rr.soft_delete()
        except ReminderReadings.DoesNotExist:
            pass
        return Response(status=status.HTTP_204_NO_CONTENT)

class ReminderReadingsViewSet(ViamModelViewSet):
    filter_fields = ('reading_date',)
    model = ReminderReadings
    serializer_class = ReminderReadingsSerializer

    def get_queryset(self):
        from django.db.models import F

        queryset = self.model.objects.filter(is_deleted=False)
        if self.request.method not in permissions.SAFE_METHODS:
            return queryset

        type = self.request.QUERY_PARAMS.get('type', 'ALL')

        if type == '1' or type == '2' or type == '3' or type == '4' :
            queryset = queryset.filter(reminder__type=type, reminder_id = F('reminder__id'))

        user = self.get_user_object()
        queryset = queryset.filter(user=user)

        reading_date_value = self.request.QUERY_PARAMS.get('reading_date', None)

        if reading_date_value:
            reading_date_list = reading_date_value.split(',')
            queryset = queryset.filter(reading_date__in=reading_date_list)
        
        return queryset