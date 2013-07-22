portal
======

ViamHealths Portal Code Base

Instructions to install API Framework
======================================

Install Virtualenv (optional , recommended)

cd GIT.ROOT/softwares/virtualenv-1.9/

sudo python setup.py install

cd ~ 

python virtualenv.py VIRTUALENV.NAME

source ~/VIRTUALENV.NAME/bin/activate

pip install yolk

pip install Django

pip install MySQL-python

pip install djangorestframework

pip install django-filter

If MySQL-python installation fails , you will need mysql dev and mysql-python packages (installed via apt/yum etc )

eg. on ubuntu , packages libmysqlclient-dev and python-mysqldb are required. Find appropiate packages for your distro.

cd GIT.ROOT/vapi

python manage.py syncdb

This command will also create an admin user

python manage.py runserver 127.0.0.1:8080

To test server hit http://127.0.0.1:8080/ or 127.0.0.1:8080/admin

To get your token make a POST call to http://127.0.0.1:8080/api-token-auth/ with username and password are POST data

Eg. API calls for users

curl -X GET http://127.0.0.1:8080/users/1/ -H 'Authorization: Token d444ff73068d26e420a0a873ca9804790612b757';

curl -X GET http://127.0.0.1:8080/users/ -H 'Authorization: Token d444ff73068d26e420a0a873ca9804790612b757';

curl -X GET http://127.0.0.1:8080/users/me/ -H 'Authorization: Token d444ff73068d26e420a0a873ca9804790612b757'

curl -H 'Authorization: Token d444ff73068d26e420a0a873ca9804790612b757' -X PUT http://127.0.0.1:8080/users/1/profile/ -d "location=delhi&gender=m"

curl -H 'Authorization: Token d444ff73068d26e420a0a873ca9804790612b757' -X GET http://127.0.0.1:8080/reminders/

curl -H 'Authorization: Token d444ff73068d26e420a0a873ca9804790612b757' -X GET http://127.0.0.1:8080/reminders/?user_id=9

curl -H 'Authorization: Token d444ff73068d26e420a0a873ca9804790612b757' -X POST http://127.0.0.1:8080/goals/weight/ -d "user=http://127.0.0.1:8080/users/1/&weight=20&interval_num=10&interval_unit=DAY&updated_by=http://127.0.0.1:8080/users/1/&target_date=2013-07-22&created_at=2013-07-22 00:48:04&updated_at=2013-07-22 00:48:04"
