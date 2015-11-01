#!/bin/bash

#Variables
DBPASSWD=12345


#first preparing
sudo mkdir /opt/project
sudo mkdir /opt/project/pma
sudo mkdir /opt/project/pma/logs
sudo mkdir /opt/project/logs

sudo apt-get update -q

#install mysql
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password $DBPASSWD"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $DBPASSWD"
sudo apt-get install -y mysql-server
echo "Start installing db data"
cd db
dirs=( $(ls) )
for dir in ${dirs[@]}; do
    echo $dir
    cd $dir
    files=( $(ls) )
    for file in ${files[@]}; do
        sudo mysql --password=$DBPASSWD < $file
        echo Dump db/$dir/$file applied
    done
    cd ..
done
cd ..
sudo apt-get install -y mc curl apache2 php5 libapache2-mod-php5 php5-mysql php5-intl php5-gd php5-imagick
echo "Loading phpmyadmin from http://downloads.sourceforge.net/project/phpmyadmin/phpMyAdmin/4.3.0/phpMyAdmin-4.3.0-all-languages.tar.gz"
echo "This may take a few minutes..."
wget -q http://files.phpmyadmin.net/phpMyAdmin/4.4.11/phpMyAdmin-4.4.11-all-languages.tar.gz
echo "Loading phpmyadmin completed"
sudo tar -xf phpMyAdmin-4.4.11-all-languages.tar.gz -C /opt/project
rm phpMyAdmin-4.4.11-all-languages.tar.gz
sudo mv /opt/project/phpMyAdmin-4.4.11-all-languages /opt/project/pma/www
sudo cp vhosts/pma.conf /etc/apache2/sites-available
sudo cp vhosts/project.conf /etc/apache2/sites-available
sudo rm /etc/apache2/sites-enabled/*
sudo ln -s /etc/apache2/sites-available/pma.conf /etc/apache2/sites-enabled
sudo ln -s /etc/apache2/sites-available/project.conf /etc/apache2/sites-enabled

sudo sed -i "s/www-data/vagrant/g" /etc/apache2/envvars

sudo a2enmod rewrite
sudo service apache2 restart

#installing composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# End
echo "Autospan server successfully installed!"
echo "Congratulations!"
