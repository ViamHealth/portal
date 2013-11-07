
from rest_framework import serializers
from datetime import date


def StringIsNotNull(strc, value_considered_null=None):
    if value_considered_null is None :
        return strc is not None and len(strc) > 0
    else:    
        return strc is not None and len(strc) > 0 and strc != value_considered_null

def FloatIsNull(strc, value_considered_null=None):
    if value_considered_null is None :
        return strc is None or strc == 0.0
    else:    
        return strc is None or strc == 0.0 or strc == value_considered_null

def StringIsNull(strc, value_considered_null=None):
    if value_considered_null is None :
        return strc is None or len(strc) == 0
    else:    
        return strc is None or len(strc) == 0 or strc == value_considered_null


def StringKeyIsNull(arr, key, value_considered_null=None):
    if value_considered_null is None :
        return arr.get(key) is None or len(arr[key]) == 0
    else:    
        return arr.get(key) is None or len(arr[key]) == 0 or arr[key] == value_considered_null

def StringKeyIsNotNull(arr, key, value_considered_null=None):
    if value_considered_null is None :
        return arr.get(key) is not None
    else:    
        return arr.get(key) is not None and len(arr[key]) == 0 and arr[key] == value_considered_null


def goals_date_validate(self, attrs):
    #if (not attrs['target_date'] ) and ( not attrs['interval_unit'] or not attrs['interval_unit'].len or not attrs['interval_num'] or not attrs['interval_num'].len or attrs['interval_num'] == 0) :
    #    raise serializers.ValidationError("Provide either target_date or both  interval_num & interval_unit ")
    return attrs

def calculate_age(born):
    today = date.today()
    try: 
        birthday = born.replace(year=today.year)
    except ValueError: # raised when birth date is February 29 and the current year is not a leap year
        birthday = born.replace(year=today.year, day=born.day-1)
    if birthday > today:
        return today.year - born.year - 1
    else:
        return today.year - born.year