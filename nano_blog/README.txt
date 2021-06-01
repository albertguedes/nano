# nano Blog

nano Blog is a very simple and "handmade" blog, coded with the rawest and purest php.
I did this to try out some ideas and concepts, such as:

- a standalone script for each page, that is, the only relationship between one page to another is a link and session variables such as GET and POST.
- a css theme without classes or id's
- a kind of "vertical MVC", that is, each script has an MVC step from top to bottom, separated only by logical order and not by files.

Yep, simple, without lots of security or SEO resources, a genuine blog when the internet was naive an innocent.

## Prerequisites

This is the configuration that i used on development:

- PHP 7.4
- PostgreSQL 11.12
- Apache 2.4.38
- Devuan Linux 3 Beowulf ( similar to Debian 10 Buster )

## INSTALL 

First, create a database on your PostgreSQL server for this project.
After this, clone the project 

'''$ git clone <link>'''

Go to the 'nano_blog/database' folder and open the script 'nano_blog.pgsql', change whtever you want, save and close the script.
After this, create the tables on the database:

'''$ psql -U <your database user> <your database> < nano_blog.pgsql'''

Now, you must configure your apache or webserver to run the project and open on url that you configured.
