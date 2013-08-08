portal
======

ViamHealths Portal Code Base


Instructions to install Website
===============================

edit /etc/php5/fpm/pool.d/www.conf and change 'listen *** sock' to 'listen 9000'

Create database viam2 in mysql

change nginx settings to point to webapp instead of htdocs ( for document root)

restart php-fpm and nginx

mkdir webapp/assets

chmod 777 -R webapp/assets

chmod 777 protected/runtime

Website will work after API installation is complete

Instructions to install API Framework
======================================

make sure you have database named viam2 in mysql

Install Virtualenv (optional , recommended)

cd GIT.ROOT/softwares/virtualenv-1.9/

sudo python setup.py install

cd ~ 

virtualenv VIRTUALENV.DIR.NAME

source ~/VIRTUALENV.DIR.NAME/bin/activate

pip install yolk

pip install Django

pip install MySQL-python 

pip install djangorestframework 

pip install django-filter 

If MySQL-python installation fails , you will need mysql dev and mysql-python packages (installed via apt/yum etc )


eg. on ubuntu , packages libmysqlclient-dev and python-mysqldb are required. Find appropiate packages for your distro.

pip install django-storages boto 

pip install pillow


eg.: Centos :- 
===============
yum install mysql-devel python-devel

pip install yolk Django MySQL-python djangorestframework django-filter django-storages boto pillow


cd GIT.ROOT/vapi

python manage.py syncdb

This command will also create an admin user

python manage.py collectstatic

python manage.py runserver 127.0.0.1:8080

To test server hit http://127.0.0.1:8080/ or 127.0.0.1:8080/admin

To get your token make a POST call to http://127.0.0.1:8080/api-token-auth/ with username and password are POST data

Eg. API calls for users

**User Signup**
curl -X POST http://127.0.0.1:8080/signup/ -d "username=curluse1qp&first_name=fnam"




**Get Current user**
curl -X GET http://127.0.0.1:8080/users/me/ -H 'Authorization: Token d444ff73068d26e420a0a873ca9804790612b757'

**Retrieve user**
curl -X GET http://127.0.0.1:8080/users/1/ -H 'Authorization: Token d444ff73068d26e420a0a873ca9804790612b757';

**List Users**
curl -X GET http://127.0.0.1:8080/users/ -H 'Authorization: Token d444ff73068d26e420a0a873ca9804790612b757';

**Create family user**
curl -H 'Authorization: Token bbdee5dd1849adb65d7e35d08ac942e6aa3e1dc5' -X POST http://127.0.0.1:8080/users/ -d "username=username5588qp&first_name=fname&email=email@gmail.com"

**Update user Profile Picture**
curl -H 'Authorization: Token bbdee5dd1849adb65d7e35d08ac942e6aa3e1dc5' -X PUT http://127.0.0.1:8080/users/22/profile-picture/ -F "profile_picture=@/home/kunal/Downloads/600249_1002029915098_1903163647_n.jpg"

**Update user Profile**
curl -H 'Authorization: Token bbdee5dd1849adb65d7e35d08ac942e6aa3e1dc5' -X PUT http://127.0.0.1:8080/users/22/profile/ -d "location=delhi&gender=male&date_of_birth=2013-10-09"


**Reminders**
curl -H 'Authorization: Token d444ff73068d26e420a0a873ca9804790612b757' -X GET http://127.0.0.1:8080/reminders/

curl -H 'Authorization: Token d444ff73068d26e420a0a873ca9804790612b757' -X GET http://127.0.0.1:8080/reminders/?user_id=9


curl -H 'Authorization: Token d444ff73068d26e420a0a873ca9804790612b757' -X POST http://127.0.0.1:8080/goals/weight/ -d "user=http://127.0.0.1:8080/users/1/&weight=20&interval_num=10&interval_unit=DAY&updated_by=http://127.0.0.1:8080/users/1/&target_date=2013-07-22&created_at=2013-07-22 00:48:04&updated_at=2013-07-22 00:48:04"


**HealthFiles**



2. Setup of Web Servers
-----------------------

The setup is ready for Nginx + PHP-FPM based webserver installation.

The ops folder has the nginx/nginx.conf and php/php.ini which if you replace in your setup should work absolutely fine.

Make sure you have dev.viam.com as the virtual host on your local. The whole setup assumes that you have dev.viam.com.

3. Requirements
------------------

All requirements will be dumped in PM folder.

4. UX Designs
-----------------

All user experience either the HTML-js-css way or pdf way will be kept in frontend folder.

