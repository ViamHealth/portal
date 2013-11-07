from api.views_helper import *

from rest_framework import viewsets, filters
from .models import *
from .serializers import *

from rest_framework import permissions
from rest_framework.response import Response
from django.http import Http404

#from rest_framework.decorators import  link
#from rest_framework.pagination import PaginationSerializer
#from django.core.paginator import Paginator, PageNotAnInteger


class FoodItemViewSet(viewsets.ModelViewSet):
    model = FoodItem
    serializer_class = FoodItemSerializer
    permission_classes = (permissions.IsAuthenticated,)
    filter_fields = ('id','name',)
    filter_backends = (filters.SearchFilter,)
    search_fields = ('name',)

    #Over riding viewset functions
    def get_queryset(self):
        queryset = self.model.objects.filter(status='ACTIVE')
        id_value = self.request.QUERY_PARAMS.get('id', None)
        if id_value:
            id_list = id_value.split(',')
            queryset = queryset.filter(id__in=id_list)

        return queryset
        
    def get_object(self, pk=None):
        try:
            return self.model.objects.get(pk=pk,status='ACTIVE')
        except self.model.DoesNotExist:
            raise Http404

    def pre_save(self, obj):
        obj.updated_by = self.request.user

    def retrieve(self, request, pk=None):
        m = self.get_object(pk)
        serializer = self.get_serializer(m)
        return Response(serializer.data)

    """"
    @link()
    def search(self, request, search_string=None):
        if search_string is not None:
            queryset = self.model.objects.filter(name__icontains=search_string)
            serializer = self.get_serializer(queryset, many=True)
            page_size = request.QUERY_PARAMS.get('page_size',5)
            paginator = Paginator(serializer.data, page_size)
            page = request.QUERY_PARAMS.get('page')
            try:
                fooditems = paginator.page(page)
            except PageNotAnInteger:
                fooditems = paginator.page(1)
            serializer = PaginationSerializer(instance=fooditems,context={'request': request})
            return Response(serializer.data)
    """
class DietTrackerViewSet(ViamModelViewSetClean):
    model = DietTracker
    serializer_class = DietTrackerSerializer
    filter_fields = ('meal_type','user','diet_date')

     #Over riding viewset functions
    def get_queryset(self):
        queryset = super(ViamModelViewSetClean, self).get_queryset()
        
        diet_date_value = self.request.QUERY_PARAMS.get('diet_date', None)
        if diet_date_value:
            diet_date_list = diet_date_value.split(',')
            queryset = queryset.filter(diet_date__in=diet_date_list)

        return queryset


