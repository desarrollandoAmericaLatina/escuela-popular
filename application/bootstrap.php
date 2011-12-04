<?php defined('SYSPATH') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------
// Load the core Kohana class
require SYSPATH . 'classes/kohana/core' . EXT;

if (is_file(APPPATH . 'classes/kohana' . EXT))
{
    // Application extends the core
    require APPPATH . 'classes/kohana' . EXT;
}
else
{
    // Load empty core extension
    require SYSPATH . 'classes/kohana' . EXT;
}

/**
 * Set the default time zone.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/timezones
 */
date_default_timezone_set('America/Santiago');

/**
 * Set the default locale.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/setlocale
 */
setlocale(LC_ALL, 'es_CL.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @see  http://kohanaframework.org/guide/using.autoloading
 * @see  http://php.net/spl_autoload_register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @see  http://php.net/spl_autoload_call
 * @see  http://php.net/manual/var.configuration.php#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
I18n::lang('es-cl');

// Set the cookie salt
Cookie::$salt = '1qazxsw23edc';

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
if (isset($_SERVER['KOHANA_ENV']))
{
    Kohana::$environment = constant('Kohana::' . strtoupper($_SERVER['KOHANA_ENV']));
}
// Set to production for disable any public error reporting
else
{
    Kohana::$environment = Kohana::PRODUCTION;
}

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 */
Kohana::init(array(
    'base_url' => '/',
    'index_file' => false,
    'profile' => Kohana::$environment === Kohana::DEVELOPMENT,
    'caching' => Kohana::$environment === Kohana::PRODUCTION,
));

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH . 'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array(
    'useradmin' => MODPATH . 'useradmin', // useradmin
    'auth' => MODPATH . 'auth', // Basic authentication
    'cache' => MODPATH . 'cache', // Caching with multiple backends
    'database' => MODPATH . 'database', // Database access
    'image' => MODPATH . 'image', // Image manipulation
    'orm' => MODPATH . 'orm', // Object Relationship Mapping
    'acl' => MODPATH . 'acl', // ACL
    'twig' => MODPATH . 'twig', // Twig templating for Kohana
    'oauth' => MODPATH . 'oauth', // OAuth,
    'ipinfo' => MODPATH . 'ipinfo', // ipinfodb.com API access module,
    'email' => MODPATH . 'email', // Swiftmailer kohana module
));

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */
// al ajax requests will go here
Route::set('ajax', '<directory>/<controller>/<action>(.<format>)', array('directory' => 'ajax', 'format' => '(json|jsonp|html|xml)+'))
    ->defaults(array(
        'format' => 'json'
    ));

// user related routes
Route::set('user/associate', 'user/associate/<provider>', array('provider' => '[a-z]+'))
    ->defaults(array(
        'controller' => 'user',
        'action' => 'associate',
        'provider' => null
    ));
Route::set('user/associate_return', 'user/associate_return/<provider>', array('provider' => '[a-z]+'))
    ->defaults(array(
        'controller' => 'user',
        'action' => 'associate_return',
        'provider' => null
    ));
Route::set('user/provider', 'user/provider/<provider>', array('provider' => '[a-z]+'))
    ->defaults(array(
        'controller' => 'user',
        'action' => 'provider',
        'provider' => NULL,
    ));
Route::set('user/provider_return', 'user/provider_return/<provider>', array('provider' => '[a-z]+'))
    ->defaults(array(
        'controller' => 'user',
        'action' => 'provider_return',
        'provider' => NULL,
    ));
Route::set('user/provider_complete', 'user/provider_complete', array('provider' => '[a-z]+'))
    ->defaults(array(
        'controller' => 'user',
        'action' => 'provider_complete',
    ));
Route::set('user', 'user/<action>', array('action' => '[-_a-z]+'))
    ->defaults(array(
        'controller' => 'user',
        'action' => 'profile',
    ));

// School related
Route::set('school/details', 'school/<id>', array('id' => '\d+'))
    ->defaults(array(
        'controller' => 'school',
        'action' => 'details',
    ));
Route::set('schools', 'schools(/<city>)', array('city' => '\d+'))
    ->defaults(array(
        'controller' => 'school',
        'action' => 'index',
    ));


// Any other request will go here
Route::set('default', '(<controller>(/<action>(/<id>)))')
    ->defaults(array(
        'controller' => 'home',
        'action' => 'index',
    ));
