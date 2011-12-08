# Project developed for an event [desarrollandoamerica/](http://desarrollandoamerica.org/)

## Note:

This project currently is frozen (not for a long time). The data still collecting and will be reformated. The goal is to adapt the data for an all LatAm or at least for the countries where this data is opened, has similar structure and geo position.

Part of a code will be rewritten from scratch to make some improvements, e.g. Google Latitude instead of ipinfodb.

## Requirements

* PHP 5.2.3 or higher with GD, PDO or mysql, curl, pcre, spl modules enabled
* MySQL 5.x
* Apache with mod_rewrite enabled or any other web server with similar capabilities, e.g. Nginx

## Installation

* Clone the repository from [github](https://github.com/desarrollandoAmericaLatina/escuela-popular)
* Add required modules by running 

    git submodule update --init --recursive

* Project uses version of oauth module not from `master` repo. Update it to `3.2/develop`.

    cd modules/oauth/
    git checkout 3.2/develop

* If you uses Apache rename example.htaccess to .htaccess
* Be sure that `/application/cache` and `/application/logs` writeable for the web server user
* Copy all files in `/application/config/` with extension `.sample` to `.php` with the same name
* Configure every module in `/application/config/*.php`. For the ipinfo (IP to location) you must obtain the key from [ipinfodb.com](http://ipinfodb.com/account.php). For the oauth and facebook you must provide keys and secrets from [twitter](https://dev.twitter.com/apps), [facebok](https://developers.facebook.com/apps) or disable this capabilities in `/application/config/useradmin.php`
* Restore the database from `/schema.sql`
* Project contain more than 300Mb of images of chilean schools. Its located at http://demos.atmaworks.com/escuela_photos.zip so download it and unpack to `/media/images/`
* At this moment there are data for the 1-4 and 13 regions only
