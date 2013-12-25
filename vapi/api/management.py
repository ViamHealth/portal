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
    
    transaction.commit_unless_managed()

#post_syncdb.connect(munge_username, sender=auth_models)