from api.views_helper import *
from api.email_helper import *


from rest_framework import viewsets, filters
from .models import *
from .serializers import *

from rest_framework import permissions, status
#from django.views.decorators.csrf import csrf_exempt


class HealthfileViewSet(ViamModelViewSet):

    """
    Manage all healthfiles for a user ( authenticated or family member)
    * Requires token authentication.
    * PUT /healthfiles/<pk>/ - upload file . Require multipart/form-data
    * PUT /healthfiles/<pk>/ - updates description of halthfile with id <pk> . Without multpart/form-data
    * DELETE /users/<pk> - Delete healthfile with id <pk>

    """

    #filter_fields = ('user')
    model = Healthfile
    filter_backends = (filters.SearchFilter,)
    search_fields = ('name','description',)
    serializer_class = HealthfileSerializer


    def pre_save(self, obj):
        file = self.request.FILES.get('file',None)
        if file is not None:
            obj.file = self.request.FILES['file']
            obj.uploading_file = True
        obj.user = self.get_user_object()
        obj.updated_by = self.request.user
    
    def get_serializer_class(self):
        if self.request.method in permissions.SAFE_METHODS:
            return HealthfileSerializer
        else:
            file = self.request.FILES.get('file',None)
            if file is not None:
                return HealthfileUploadSerializer
            else:
                return HealthfileEditSerializer

    def create(self, request, format=None):
        serializer = self.get_serializer(data=request.DATA,)
        if serializer.is_valid():
            file = self.request.FILES.get('file',None)
            if file is not None:
                serializer.object.file = request.FILES['file']
                serializer.object.uploading_file = True
            serializer.object.user = self.get_user_object()
            serializer.object.updated_by = request.user
            serializer.save()
            f=Healthfile.objects.get(pk=serializer.data.get('id'))
            fserializer = HealthfileSerializer(f, context={'request': request})
            return Response(fserializer.data, status=status.HTTP_201_CREATED)
        else:
            return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    def update(self, request, pk=None):
        tags_sent = False
        m = self.get_object()
        serializer = self.get_serializer(m, data=request.DATA)
        if serializer.is_valid():
            serializer.save()
            #TODO: improve tags creation/deletion
            data = request.DATA.copy()
            tags = []
            tag_objs_up = []
            tag_objs_create = []
            for k,v in data.iteritems():
                if k[:5] == 'tags[':
                    tags.append(v)
                    tags_sent = True

            if tags_sent:
                id_arr = []
                for v in tags:
                    try:
                        t = HealthfileTag.objects.get(healthfile=m,tag=v,is_deleted=False)
                    except HealthfileTag.DoesNotExist:
                        t = HealthfileTag(tag=v,healthfile=m)
                    tdata = {}
                    tdata['tag'] = str(t.tag)
                    tdata['healthfile'] = m.id
                    if t.id is not None:
                        tdata['id'] = t.id
                        tag_objs_up.append(tdata)
                        id_arr.append(t.id)
                    else:
                        tag_objs_create.append(tdata)

                #qt = HealthfileTag.objects.filter(id__in=id_arr,is_deleted=False)

                HealthfileTag.objects.filter(healthfile=m).exclude(id__in=id_arr).soft_delete()

                tagserializer = HealthfileTagAddSerializer(data=tag_objs_create , many=True)
                if tagserializer.is_valid():
                    tagserializer.save()
                else:
                    return Response(tagserializer.errors, status=status.HTTP_400_BAD_REQUEST)
            
            return Response(serializer.data)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

