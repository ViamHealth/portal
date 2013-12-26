from django.db.models.signals import post_syncdb
from django.contrib.auth import models as auth_models

def munge_username(sender, **kwargs):
    from django.db import connection, transaction
    cursor = connection.cursor()
    
    # Data modifying operation - commit required
    cursor.execute("ALTER TABLE auth_user " 
        "MODIFY username VARCHAR(255) NOT NULL")
    cursor.execute("ALTER TABLE auth_user "
        "MODIFY email VARCHAR(255) NULL")
    cursor.execute("ALTER TABLE auth_user ADD UNIQUE INDEX (email)")

    cursor.execute("alter table tbl_physical_activities add index (label(255))")

    cursor.execute("alter table tbl_food_items add index (name(255))")

    cursor.execute("CREATE TABLE `ci_sessions` ( `session_id` varchar(40) NOT NULL DEFAULT '0',`ip_address` varchar(45) NOT NULL DEFAULT '0',`user_agent` varchar(120) NOT NULL,`last_activity` int(10) unsigned NOT NULL DEFAULT '0',`user_data` text NOT NULL, PRIMARY KEY (`session_id`), KEY `last_activity_idx` (`last_activity`))")
    
    transaction.commit_unless_managed()

#post_syncdb.connect(munge_username, sender=auth_models)