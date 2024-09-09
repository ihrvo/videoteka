LARAVEL instalacija:

1. Prvo sam instalirao PHP 8.3

## Save existing php package list to packages.txt file
sudo dpkg -l | grep php | tee packages.txt

# Add Ondrej's PPA
sudo add-apt-repository ppa:ondrej/php # Press enter when prompted.
sudo apt update

# Install new PHP 8.3 packages
sudo apt install php8.3 php8.3-cli php8.3-{bz2,curl,mbstring,intl}

# Install FPM OR Apache module
sudo apt install php8.3-fpm
# OR
# sudo apt install libapache2-mod-php8.2

# On Apache: Enable PHP 8.3 FPM
sudo a2enconf php8.3-fpm
# When upgrading from an older PHP version:
sudo a2disconf php8.2-fpm

## Remove old packages
sudo apt purge php8.2*

2. Onda mi je javljalo:

# Failed to download laravel/laravel from dist: The zip extension and unzip/7z commands are both missing, skipping.
# Your command-line PHP is using multiple ini files. Run php --ini to show them.
#     Now trying to download from source

# Riješeno sa 

sudo apt-get install php-zip
sudo apt-get install unzip

3. onda je javljalo:

# Your requirements could not be resolved to an installable set of packages.

#   Problem 1
#     - Root composer.json requires laravel/pint ^1.13 -> satisfiable by laravel/pint[v1.13.0, ..., v1.17.3].
#     - laravel/pint[v1.13.0, ..., v1.17.3] require ext-xml * -> it is missing from your system. Install or enable PHP's xml extension.
#   Problem 2
#     - phpunit/phpunit[11.0.1, ..., 11.3.4] require ext-dom * -> it is missing from your system. Install or enable PHP's dom extension.
#     - Root composer.json requires phpunit/phpunit ^11.0.1 -> satisfiable by phpunit/phpunit[11.0.1, ..., 11.3.4].

# To enable extensions, verify that they are enabled in your .ini files:
#     - /etc/php/8.3/cli/php.ini
#     ...itd

Riješeno sa:

sudo apt-get install php-xml
sudo service apache2 restart


Zatim je instalacija prošla i pokrenuo:

php artisan serve

Onda sam probao spojiti na MySql bazu, ovo u biti još ne želimo PA ZASAD PRESKOČITI, ali za to je potrebno:

sudo apt-get install php-mysql
sudo service apache2 restart

Inače se konekcija podešava u .env datoteci u rootularavel projekta (/var/www/html/algebra/ihrvo/BackendDeveloper/laravel-videoteka) ili kako je kome već path

tu sam stavio 

DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
DB_DATABASE=/var/www/html/algebra/ihrvo/BackendDeveloper/laravel-videoteka/database/database.sqlite
# DB_USERNAME=root
# DB_PASSWORD=

i dalje nije radilo dok nisam:

sudo apt-get install php-sqlite3
sudo service apache2 restart


onda je javljalo da nema tablica pa sam

php artisan migrate


i može se ako i dalje zeza:

php artisan config:clear
php artisan cache:clear
php artisan optimize:clear



Live preview http://hmdev.eu/videoteka/

u: test@test.com
p: 11111
