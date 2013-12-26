portal
======

ViamHealths Portal Code Base


CENTOS Only
===========
rpm --import https://fedoraproject.org/static/0608B895.txt 

rpm -ivh http://dl.fedoraproject.org/pub/epel/6/x86_64/epel-release-6-8.noarch.rpm

rpm --import http://rpms.famillecollet.com/RPM-GPG-KEY-remi

rpm -ivh http://rpms.famillecollet.com/enterprise/remi-release-6.rpm

yum install yum-priorities

vi /etc/yum.repos.d/epel.repo add line priority=10 to the [epel]

vi /etc/yum.repos.d/remi.repo add line and change enabled = 1

yum install nginx

###chkconfig --levels 235 nginx on

/etc/init.d/nginx start

yum install php-fpm php-cli php-mysql php-gd curl

####chkconfig --levels 235 php-fpm on

/etc/init.d/php-fpm start


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

cd GIT.ROOT/vapi

pip install -r requirements.txt

If  MySQL-python installation fails , you will need mysql dev and mysql-python packages (installed via apt/yum etc )

eg. on ubuntu , packages libmysqlclient-dev, python-dev and python-mysqldb are required. Find appropiate packages for your distro.

*eg.: Centos :- *

yum install mysql-devel python-devel


**ignore**
python manage.py schemamigration api.users --initial

python manage.py schemamigration api.activity --initial

python manage.py schemamigration api.diet --initial

python manage.py schemamigration api.goals --initial

python manage.py schemamigration api.healthfiles --initial

python manage.py schemamigration api.reminders --initial

python manage.py schemamigration api.util  --initial

python manage.py schemamigration api --initial

python manage.py schemamigration rest_framework --initial

python manage.py schemamigration rest_framework.authtoken --initial

python manage.py schemamigration django_ses --initial

python manage.py schemamigration djcelery --initial

python manage.py schemamigration storages --initial

python manage.py schemamigration seacucumber --initial

python manage.py schemamigration kombu.transport.django --initial

python manage.py schemamigration rest_framework_swagger --initial


python manage.py migrate api.users 

python manage.py migrate api.activity 

python manage.py migrate api.diet 

python manage.py migrate api.goals 

python manage.py migrate api.healthfiles 

python manage.py migrate api.reminders 


python manage.py migrate api 

python manage.py migrate rest_framework 

python manage.py migrate rest_framework.authtoken 

python manage.py migrate django_ses 

python manage.py migrate djcelery 

python manage.py migrate storages 

python manage.py migrate seacucumber 

python manage.py migrate kombu.transport.django 

python manage.py migrate rest_framework_swagger 

INSERT INTO viam_v0.tbl_food_items ( name,display_image, quantity, quantity_unit, calories , total_fat, saturated_fat ,polyunsaturated_fat, monounsaturated_fat, trans_fat, cholesterol, sodium, potassium, total_carbohydrates, dietary_fiber, sugars ,protein, vitamin_a, vitamin_c, calcium, iron, calories_unit, total_fat_unit, saturated_fat_unit, polyunsaturated_fat_unit, monounsaturated_fat_unit, trans_fat_unit, cholesterol_unit, sodium_unit, potassium_unit ,total_carbohydrates_unit, dietary_fiber_unit, sugars_unit, protein_unit, vitamin_a_unit, vitamin_c_unit, calcium_unit, iron_unit )  select name,display_image, quantity, quantity_unit, calories , total_fat, saturated_fat ,polyunsaturated_fat, monounsaturated_fat, trans_fat, cholesterol, sodium, potassium, total_carbohydrates, dietary_fiber, sugars ,protein, vitamin_a, vitamin_c, calcium, iron, calories_unit, total_fat_unit, saturated_fat_unit, polyunsaturated_fat_unit, monounsaturated_fat_unit, trans_fat_unit, cholesterol_unit, sodium_unit, potassium_unit ,total_carbohydrates_unit, dietary_fiber_unit, sugars_unit, protein_unit, vitamin_a_unit, vitamin_c_unit, calcium_unit, iron_unit from viam.tbl_food_items ;

update viam_v0.tbl_food_items  set created_at = NOW(), updated_at = NOW();

INSERT INTO viam_v0.tbl_physical_activities ( label, `value` )  select label, `value` from viam.tbl_physical_activities ;

update viam_v0.tbl_physical_activities  set created_at = NOW(), updated_at = NOW();

**ignore ends**



**Uncomment last line in management.py**

python manage.py syncdb

Comment back the last line in management.py

This command will also create an admin user

**Change template directory path in vapi/settings.py**

python manage.py collectstatic

python manage.py runserver 127.0.0.1:8080

To test server hit http://127.0.0.1:8080/ or 127.0.0.1:8080/admin

** for facebook **

Login at 127.0.0.1:8080/admin

go to http://localhost:8080/admin/sites/site/  and change example.com to viamhealth.com or localhost:8080

go to http://localhost:8080/admin/socialaccount/socialapp/ and add FB app settings

**for facebook ends**

To get your token make a POST call to http://127.0.0.1:8080/api-token-auth/ with username and password are POST data

Eg. API calls for users

**User Signup**

curl -X POST http://127.0.0.1:8080/signup/ -d "username=curluse1qp&first_name=fnam"

**Get token**

curl -X POST http://127.0.0.1:8080/api-token-auth/ -d"username=uname@email.com&password=pword"

**Get Current user**

curl -H 'Authorization: Token d444ff73068d26e420a0a873ca9804790612b757' -X GET http://127.0.0.1:8080/users/me/ 

**Add token to all requests below this as mentioned above**

**Retrieve user**

-X GET http://127.0.0.1:8080/users/1/

**List Users**

-X GET http://127.0.0.1:8080/users/

**Create family user**

-X POST http://127.0.0.1:8080/users/ -d "first_name=kunal&last_name=admin&username=kunal2@email.com"

{"id": 3, "url": "http://127.0.0.1:8080/users/3/", "username": "kunal2@email.com", "email": "", "first_name": "kunal", "last_name": "admin", "profile": {"location": "", "gender": "", "date_of_birth": null, "profile_picture_url": "http://viamhealth-docsbucket.s3.amazonaws.com/static/api/default_profile_picture_n.jpg"}}

** Update family user**

-X PUT http://127.0.0.1:8080/users/1/ -d "first_name=kunal&last_name=admin"

{"id": 3, "url": "http://127.0.0.1:8080/users/3/", "username": "kunal2@email.com", "email": "", "first_name": "kunal", "last_name": "admin", "profile": {"location": "", "gender": "", "date_of_birth": null, "profile_picture_url": "http://viamhealth-docsbucket.s3.amazonaws.com/static/api/default_profile_picture_n.jpg"}}

**Update user Profile**

-X PUT http://127.0.0.1:8080/users/1/profile/ -d "location=delhi&gender=MALE&date_of_birth=2013-10-09"

{"location": "delhi", "gender": "MALE", "date_of_birth": "2013-10-09", "profile_picture_url": "http://viamhealth-docsbucket.s3.amazonaws.com/static/api/default_profile_picture_n.jpg"}

**Update user Profile Picture**

-X PUT http://127.0.0.1:8080/users/22/profile-picture/ -F "profile_picture=@/home/kunal/Downloads/600249_1002029915098_1903163647_n.jpg"

{"location": "delhi", "gender": "MALE", "date_of_birth": "2013-10-09", "profile_picture_url": "http://viamhealth-docsbucket.s3.amazonaws.com/media/users/profile_picture_e25388fde8290dc286a6164fa2d97e551b53498dcbf7bc378eb1f178.jpg"}


**Reminders**

*filters supported = user*
*editale fields = 'details','start_timestamp' ,'repeat_mode','repeat_day','repeat_hour','repeat_min','repeat_weekday','repeat_day_interval'*
*required fields = 'details','start_timestamp' *
*usual REST methods*

-X GET http://127.0.0.1:8080/reminders/

-X GET http://127.0.0.1:8080/reminders/?user=9

**HealthFiles**

*usual REST methods*

*filters supported = healthfile*

-X GET http://127.0.0.1:8080/healthfiletags/?healthfile=1

**Goal**

*filters supported = user*

-X POST http://127.0.0.1:8080/goals/weight/ -d "user=http://127.0.0.1:8080/users/1/&weight=20&interval_num=10&interval_unit=DAY&updated_by=http://127.0.0.1:8080/users/1/&target_date=2013-07-22&created_at=2013-07-22 00:48:04&updated_at=2013-07-22 00:48:04"





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

