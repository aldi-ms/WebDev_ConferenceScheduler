#!/usr/bin/env bash
projectName='WebDev_ConferenceScheduler'

# install Composer
curl -s https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# go to project folder, load Composer packages
cd "/opt/bitnami/apache2/htdocs/${projectName}"
composer install --dev