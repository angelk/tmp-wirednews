set -e

apt-get update

# update to php 5.6, cuz 5.5 is not supported
apt-get install -y software-properties-common
add-apt-repository ppa:ondrej/php
apt-get update

apt-key adv --keyserver keyserver.ubuntu.com --recv-key 4F4EA0AAE5267A6C

apt-get install -y --force-yes apache2 wget unzip php5.6 php5.6-xml vim cron ntp

wget https://getcomposer.org/composer.phar -O /usr/local/bin/composer
chmod +x /usr/local/bin/composer

mkdir /var/www/latestnews
mkdir /var/www/latestnews/latest

a2enmod rewrite
a2dissite 000-default

APP_PATH="/var/www/latestnews/latest"

echo "<VirtualHost *:80>
  ServerName latestnews.com
  ServerAdmin webmaster@localhost

  DocumentRoot ${APP_PATH}/web

  RewriteEngine On

  <Directory ${APP_PATH}/web>
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ app.php [QSA,L]
  </Directory>
    
  ErrorLog \${APACHE_LOG_DIR}/latestnews-error.log

  LogLevel warn

  CustomLog \${APACHE_LOG_DIR}/latestnews-access.log combined
</VirtualHost>
" > /etc/apache2/sites-available/latestnews.conf

a2ensite latestnews

/etc/init.d/apache2 restart
