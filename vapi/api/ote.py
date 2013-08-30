#one time executes
from rest_framework.decorators import api_view, link, action
from data_importer import XLSImporter
import pprint
from api.models import *
class FoodItemXlsImporterModel(XLSImporter):
    class Meta:
        model = FoodItem
        ignore_first_line = True
        raise_errors = True
        exclude = ['display_image','quantity','calories_unit', 'total_fat_unit', 'saturated_fat_unit', 'polyunsaturated_fat_unit', 'monounsaturated_fat_unit', 'trans_fat_unit', 'cholesterol_unit', 'sodium_unit', 'potassium_unit', 'total_carbohydrates_unit', 'dietary_fiber_unit', 'sugars_unit', 'protein_unit', 'vitamin_a_unit', 'vitamin_c_unit', 'calcium_unit', 'iron_unit',
        'created_at','updated_at','updated_by',
        ]

def upload_fdb_files(filename):
    LOCAL_DIR = os.path.dirname(__file__)

    xls_file = os.path.join(LOCAL_DIR, filename)
    pprint.pprint(xls_file)
    my_csv_list = FoodItemXlsImporterModel(source=xls_file)
    i = 0
    try:
        for k,m in my_csv_list.cleaned_data:
            i = i + 1
            import string
            m['quantity_unit'] = filter(lambda x: x in string.printable, m['quantity_unit'])
            m['name'] = filter(lambda x: x in string.printable, m['name'])

            qstr = m['quantity_unit']
            m['quantity'],abc,m['quantity_unit'] = qstr.partition(" ")
            m['quantity'] = float(m['quantity'])
            m['updated_by'] = request.user
            m['status'] = 'ACTIVE'
            
            fi = FoodItem(**m)
            fi.save(force_insert=True,)
    except:
        pprint.pprint('failed for ')
        pprint.pprint(i)

    

@api_view(['GET',])
def upload_food_items(request, format=None):
    #upload_fdb_files('FDB1.xlsx')
    #upload_fdb_files('FDB2.xlsx')
    upload_fdb_files('FDB3.xlsx')