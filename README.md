# Simple fossil catalog for collectors

# Requirements
You need to have a webserver with PHP 8.1 or higher and a MySQL database.
also make sure you have the following PHP extensions installed:
- pdo_mysql
- ext-ctype
- ext-gd
- ext-iconv
- ext-yaml
- ext-zip

## Installation
1. Upload zip file to your server
2. Unzip the file in the desired location of your webserver
3. Open your browser and navigate to the location of the unzipped files
4. Make sure you have the information about your database connection:
    - Database name / or you let create a new database by the installer, but you need to provide a database name
    - Database user
    - Database password
    - Database host
    - Database port
5. Follow the installation instructions

## For developers
### Command line interface
1. Installation : `bin/console app:install` Create also a User with the credentials:
    - Email: test@example.com
    - Password: test1234
2. Demo data: `bin/console app:demo-data:create`
3. When you create a new snippet which is used with the Js Translator, you need to update the translations with `bin/console app:translate`
