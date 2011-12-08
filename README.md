# Proyecto desarrollado para el evento [desarrollandoamerica/](http://desarrollandoamerica.org/)

## Note:

This project currently is frozen (not for a long time). The data still collecting and will be reformated. The goal is to adapt the data for an all LatAm or at least for the countries where this data is opened, has similar structure and geo position.

Part of a code will be rewritten from scratch to make some improvements, e.g. Google Latitude instead of ipinfodb.

## Requerimientos

* PHP 5.2.3 o más con GD, PDO o mysql, curl, pcre, spl módulos habilitados
* MySQL 5.x
* Apache con mod_rewrite habilitado o cualquier otro servidor web con capacidades similares, por ejemplo Nginx

## Instalación

* Clonar el repositorio de [github](https://github.com/desarrollandoAmericaLatina/escuela-popular)
* Agregar módulos requeridos por running

    git submodule update --init --recursive

* El proyecto usa la versión del módulo oauth no del `master` repo. Actualícela a `3.2/develop`.

    cd modules/oauth/
    git checkout 3.2/develop

* Si usa Apache cambiar el nombre example.htaccess por .htaccess
* Asegúrese de que `/application/cache` y `/application/logs` tengan permisos de escritura para el usuario del servidor web
* Copie todos los archivos a `/application/config/` con la extensión `.sample` a `.php` con el mismo nombre
* Configure cada módulo en `/application/config/*.php`. Para la ipinfo (IP to location) tiene que obtener la clave de [ipinfodb.com](http://ipinfodb.com/account.php). Para soportar oauth y facebook auth tiene que proveer las claves y secrets de [twitter](https://dev.twitter.com/apps), [facebok](https://developers.facebook.com/apps) o deshabilitar estas opciones en  `/application/config/useradmin.php`
* Restaure la base de datos de `/schema.sql`
* El proyecto contiene más de 300Mb de imágenes de los colegios chilenos. Están ubicadas en http://demos.atmaworks.com/escuela_photos.zip entonces hay que descargarlas y desarchivarlas en `/media/images/`
* Actualmente sólo se dispone de los datos para las regiones I a IV y la XIII.
