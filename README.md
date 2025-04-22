# funko
An application to keep track of my Funko Pop! Keychains

There are a few bugs, but I wrote this for my use. So... your use may vary. Use at your own risk.

This is layed out to be a sub folder. There is a folder that is one level above the public_html(www) and all other folders go within public_html.

You will also need to update the passwords in 
* funko/db_manager_conn.php
* funko/db_page_conn.php
to match the passwords in the the mysql database scripts

There are two mysql scripts
* create_users.sql
* create_table.sql

You will also need to create a .htaccess file in funko_management. It is a simply way of doing a login challange to keep others away from the management page.
