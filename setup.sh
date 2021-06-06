#!/bin/bash

var_path=`pwd`
echo $var_path

echo -e  "==== INSTALLATION AQUAMONITOR ====\n"
echo -n  "--> Installation module 1-wire pour capteur de température"
modprobe w1-gpio
modprobe w1-therm
echo " : OK"

# Commande de vérifiaction de l'installation du module 1-wire
#lsmod |grep w1

echo -n "--> Ecriture dans le fichier de démarrage "
#echo "dtoverlay=w1-gpio" >> /boot/config.txt
echo " : OK"

#sudo apt install apache2
#sudo chown -R pi:www-data /var/www/html/
#sudo chmod -R 770 /var/www/html/

#sudo apt install php php-mbstring

#sudo apt install mariadb-server php-mysql
#sudo rm /var/www/html/index.html
#sudo cp -r html /var/www/
#sudo chown -R pi:www-data /var/www/html/
#sudo chmod -R 770 /var/www/html/

#sudo phpenmod mysqli
#sudo /etc/init.d/apache2 restart
sudo mysql -u root -e "create database aquarium;"

sudo apt install phpmyadmin

echo -e  "\n"
