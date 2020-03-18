Pricing intelligence
(Please note : enter website names as "Industry Buying"(case sensitive) on website creation page, as current code only supports crawling for 'industry buying' e-commerce site)

======

## pricing_intelligence
* Symfony Framewrok v4.4.5 application demoing a pricing intelligence application

A pricing intelligence tool that uses a web crawler to fetch information from e-commerce websites

## System requirments / dependencies:
* Apache running on a linux server [512MB+ Ram] 
* PHP 7.3 
* Composer
* MySQL  

## Project setup 
* run the command "composer install" 
* run the command "php bin/console cache:clear"
* run the command "php bin/console doctrine:database:create"
* run the command "php bin/console doctrine:schema:update --force" 

## Project running
* run command "bin/console server:run" to run the application in your system
* access url provided from terminal or run locally
* please note : enter website names as "Industry Buying"(case sensitive) on website creation page, as current code only supports crawling for 'industry buying' e-commerce site

