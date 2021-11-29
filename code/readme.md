# Blogify

###### Blogify is the 5th project of OpenClassrooms back-end course.
#
#
## Features _(for anonymous visitors)_
- Read articles
- Contact me
#
## Features _(for a user)_
- Comment an article
#
## Features _(for an admin)_
- Write article
- Edit article
- Delete article
- Manage comments

### Tech

Blogify is developed under this technologies :

- PHP & MySQL
- jQuery

## Installation

Blogify requires [PHP](https://php.net) 8.0.1 to run.

Install the bundles

```sh
cd Blogify
composer install
```

Then you need to import the blogify.sql file into your SQL database managment system.

If you run _Blogify_ under MAMP or WAMP, run on your browser http://localhost:[port]/Blogify

Or run into terminal
```
$ php -S localhost:8000
```

###### Then, you could connect to admin profile with this following credentials :
- admin@blogify.fr
- admin123

### Add .env at the root of your project
```
MAIL_HOST='your@smtp.provider'
MAIL_USERNAME='username'
MAIL_PASSWORD='password'

DB_HOST = dbhost
DB_NAME = dbname
DB_USER = dbuser
DB_PASSWORD = dbpassword
```
You will need to specify :
- Your SMTP provider information to be able sending mails
- Your local database information to let the website working