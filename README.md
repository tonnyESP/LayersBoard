# Installing and preparing layers environment
In the *near future*, we will have an installation file for just run and start. At the moment, you should follow this little tutorial to make your LayersBoard start working.

This tutorial is intended to be used under an Ubuntu 16.04 clean installation, but i'm pretty sure you can make it run into your machine, even if it has already installed Layers toolkit.

I recommend you to create a [Google Cloud Account](https://cloud.google.com/), create a new Compute Engine instance and start this tutorial.

## Layers simple installation
> sudo apt-get update

> sudo apt-get install make

> sudo apt-get install g++

> sudo apt-get install flex

> sudo apt-get install bison

> git clone https://github.com/RParedesPalacios/Layers.git

> cd Layers/src

> sudo make

> sudo cp layers /usr/bin

> cd ../..

## Setting up LayersBoard workspace

> mkdir LayersBoard

> mkdir LayersBoard/Experiments

> mkdir LayersBoard/Datasets

> mkdir LayersBoard/Datasets/MNIST

> mkdir LayersBoard/Datasets/CIFAR-10

> mkdir LayersBoard/Datasets/CIFAR-100

> mkdir LayersBoard/Datasets/MIT-UrbanNatural

> cd LayersBoard/Datasets/MNIST

> wget http://users.dsic.upv.es/~rparedes/DeepLearning/data/MNIST/training

> wget http://users.dsic.upv.es/~rparedes/DeepLearning/data/MNIST/test

> cd ../CIFAR-10

> wget http://users.dsic.upv.es/~rparedes/DeepLearning/data/CIFAR/training

> wget http://users.dsic.upv.es/~rparedes/DeepLearning/data/CIFAR/test

> cd ../CIFAR-100

> wget http://users.dsic.upv.es/~rparedes/DeepLearning/data/CIFAR-100/training

> wget http://users.dsic.upv.es/~rparedes/DeepLearning/data/CIFAR-100/test

> cd ../MIT-UrbanNatural

> wget --output-document=training http://users.dsic.upv.es/~rparedes/DeepLearning/data/MIT/train64x64.bin

> wget --output-document=test http://users.dsic.upv.es/~rparedes/DeepLearning/data/MIT/test64x64.bin

> cd ../..

## Installing LAMP server
> sudo apt-get update

> sudo apt-get install apache2

> sudo apt-get install php7.0-zip

> sudo apt-get install mysql-server mysql-client

- You will be prompted to set up your root password

> sudo apt-get install phpmyadmin

- Choose *Yes* to DBconfig common

> sudo usermod -a -G www-data $$YOUR_USERNAME$$

> sudo chgrp www-data LayersBoard

> sudo chmod g+rwxs LayersBoard

### To be able to manage your SQL from phpmyadmin
- Edit */etc/apache2/apache2.conf* file and include phpmyadmin line at the end of it

> sudo nano /etc/apache2/apache2.conf

> Include /etc/phpmyadmin/apache.conf

- Save, exit and restart apache2 service

> sudo systemctl restart apache2

- __You can check if it is working by accessing with your browser to your external IP__

## Get lastest version of LayersBoard from github
> cd /var/www/html

> sudo git clone https://github.com/tonnyESP/LayersBoard.git

## Create and configure database
> mysql -u root -p

- Write your password

> CREATE USER 'layers'@'localhost' IDENTIFIED BY 'layers123';

> REVOKE ALL PRIVILEGES ON *.* FROM 'layers'@'localhost'; REVOKE GRANT OPTION ON *.* FROM 'layers'@'localhost'; GRANT SELECT, INSERT, UPDATE, DELETE ON *.* TO 'layers'@'localhost' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;

> CREATE DATABASE layers;

> USE layers;

> source /var/www/html/LayersBoard/install/bbdd_structure.sql

> source /var/www/html/LayersBoard/install/dataset_data.sql

> exit

## Edit include/database.inc.php to match your configuration

> sudo nano include/database.inc.php

> private $_username = "layers";

> private $_password = "layers123";

## Edit include/config.php to match your paths

> $serverPath = "/var/www/html/LayersBoard";

> $layersBoardPath = "/home/$$YOUR_USERNAME$$/LayersBoard";

## You should add a user (this will be easier in later versions using a registration form) ##
 - __Note: at the moment you will insert a new register into users table__
