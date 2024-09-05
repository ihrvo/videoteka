sudo mkdir napredni_php
sudo chown $USER:$USER /var/www/napredni_php/

ls -al /etc/apache2/sites-enabled/
sudo nano /etc/apache2/sites-available/napredni_php.conf

# Sadrzaj 'napredni_php.conf' datoteke
<VirtualHost *:80>
    DocumentRoot /var/www/napredni_php/public

    <Directory /var/www/napredni_php/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    Alias /phpmyadmin /usr/share/phpmyadmin
    <Directory /usr/share/phpmyadmin>
        Options FollowSymLinks
        DirectoryIndex index.php
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/backend_developer_0.error.log
    CustomLog ${APACHE_LOG_DIR}/backend_developer_0.access.log combined
</VirtualHost>

sudo a2ensite napredni_php.conf
sudo service apache2 restart