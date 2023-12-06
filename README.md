# BLOG Serizawatch

Salut, vous trouverez ci-dessous la documentation nécessaire pour mettre votre appli symfony en production.


# Installation d'apache

Apache sera notre serveur pour mettre notre application symfony en prod.

On commence par mettre la machine à jour avec la commande :
 ```
$ sudo  apt update
 ```

On installe ensuite apache et on lance le serveur :
 ```
$ sudo  apt  install apache2 –y

$ sudo  systemctl start apache2
 ```


# Installation de PHP
Avant d’installer PHP 8, on va avoir besoin des d’installer les paquets suivants :
 ```
$ apt install -y lsb-release ca-certificates apt-transport-https software-properties-common gnupg2
 ```

Ensuite, on ajoute un nouveau dépôts de paquets :
 ```
$ echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/sury-php.list
 ```

Ajout de la clé du dépot :
 ```
$ wget -qO - https://packages.sury.org/php/apt.gpg | apt-key add -
 ```
Mise à jour des paquets :
 ```
$ apt update && apt upgrade -y
 ```

Installation de PHP 8 :
 ```
$ apt install php
 ```

## Installation de PHP-FPM

Pour la suite il nous faudra installer PHP-FPM :
 ```
$ sudo apt install php-fpm php-mysql -y 
 ```

On peut vérifier si le service est bien en marche :
 ```
$ sudo systemctl status php8.2-fpm 
 ```

Ensuite, on configure apache afin qu'il utilise PHP-FPM :
 ```
$ sudo a2dismod php8.2 

$ sudo a2enmod proxy_fcgi setenvif

$ sudo a2enconf php8.2-fpm
 ```


On redémarre ensuite le serveur pour enregistrer les changements :
 ```
$ sudo systemctl restart apache2
 ```


# Installation de Composer :

Pour fonctionner, Symfony à besoin de Composer, et Composer à besoin de PHP pour l’installer :
 ```
$ apt install wget php-cli php-xml php-zip php-mbstring unzip -y
 ```

Ensuite on télécharge le programme d’installation de Composer :
  ```
$ wget -O composer-setup.php https://getcomposer.org/installer
 ```

Puis on exécute le programme d’installation de Composer
 ```
$ php composer-setup.php --install-dir=/usr/local/bin --filename=composer
 ```

Vérification que composer est bien installé
 ```
$ composer --version
 ```

**Attention, pour le bon fonctionnement de la page il sera nécessaire de le réinstaller après dans un fichier qui sera créer au cour de la mise en prod. Un rappel vous sera fait pour ne pas oublier l'étape.


# Installation de Symfony


D’abord on télécharge et on exécute le programme d’installation fournis par Symfony :
 ```
$ curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | sudo -E bash
 ```

**Si la commande curl ne fonctionne pas, vous pouvez l'installer avec la commande suivante :
 ```
$ apt install curl
 ```


Puis on Installe la CLI (Interface en ligne de commande) Symfony.
 ```
$ apt install symfony-cli
 ```

Enfin on vérifie que Symfony à bien installé.
 ```
$ symfony -V
 ```


# Installation de GIT

Pour pouvoir utiliser le code du Blog, on va cloner le code.

On procède d'abord à l'installation de git :
 ```

$ sudo apt install git -y
 ```

On clonera ensuite le code source dans le dossier var/www/html :
 ```

$ git clone https://github.com/Luxly28/Blog1.git
 ```


# Modification des fichiers

On va se rendre dans le fichier etc/apache2/sites-enabled puis on modifie le dossier 000-default.conf en y ajoutant ces lignes de code:
 ```
$ DocumentRoot /var/www/html/Blog1


DocumentRoot /var/www/html/Blog1/public
        <Directory /var/www/html/Blog1/public>
        AllowOverride None
        Require all granted
        FallbackResource /index.php
    </Directory>
 ```

**Composer doit être installée dans le fichier var/www/html/Blog1:
 ```
$ install composer
 ```


# BDD mariadb

Pour que le tout soit bien sécuriser on va héberger notre base de données dans une autre vm Donc crée une autre vm debian et installe mariadb:

 ```

$ apt install mariadb-server mariadb-client -y
$ mysql
$ CREATE USER 'metacloud'@'%' IDENTIFIED BY 'Blog';
$ GRANT ALL PRIVILEGES ON * . * TO 'metacloud'@'%';
$ FLUSH PRIVILEGES
$ CREATE DATABSE blog;
$ exit
 ```



Dans le fichier blog que tu as cloné /var/www/html/Blog1
 ```
$ composer require
$ php bin/console make:migration
$ php bin/console doctrine:fixtures:load
 ```



change également la blind adress dans /etc/mysql/mariadb.conf.d/50-server.cnf

 ```
$ cd  /etc/mysql/mariadb.conf.d/
$ nano 50-server.cnf
$ bind-address = 0.0.0.0
 ```



# Changer le .env

Il faut aller encore dans /var/www/html/blog et changer le .env pour accéder a notre bdd

 ```
 $ cd /var/www/html/Blog1
$ sudo nano .env
 ```


Il faut changer cette ligne :

 ```
$ DATABASE_URL="mysql://lux:luxly@172.16.119.16:3306/blog?serverVersion=10.11.2-MariaDB&charset=utf8mb4"MariaDB&charset=utf8m
 ```

** Attention l'adresse ip de cette ligne sera a changer en fonction de l'ip de la vagrant hébergeant la base de données.

Une fois que tout est fait il suffira de restart le serveur apache:
```
 $ systemctl restart apache2
 ```
 
# Bravo tu as fini la mise en prod
appelle moi s'il y a un soucis. 






