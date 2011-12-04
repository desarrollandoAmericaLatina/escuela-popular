<?php defined('SYSPATH') or die('No direct script access.');

// see /system/mesages/validation.php for the defaults for each rule. These can be overridden on a per-field basis.
return array(
      'username' => array(
         'not_empty' => 'Имя пользователя не может быть пустым.',
         'invalid' => 'Пароль или Имя пользователя неверны.',
       ),
      'password' => array(
         'not_empty'      => 'Пароль не может быть пустым.',
         'invalid' => 'Пароль или Имя пользователя неверны.',
      ),
);

