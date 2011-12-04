<?php

return array (
	'default' => array (
		'environment' => array (
			'debug' => (bool) Kohana::$environment === Kohana::DEVELOPMENT, // Allow the use of the debug block
			'trim_blocks' => FALSE, // The first newline after a template tag is removed
			'charset' => Kohana::$charset,
			'base_template_class' => 'Twig_Template', // Template name to use in the compiled classes
			'cache' => APPPATH.'cache/twig', // null|false|path
			'auto_reload' => TRUE, // Update the template when the source code changes
			'strict_variables' => (bool) Kohana::$environment === Kohana::DEVELOPMENT,
			'autoescape' => FALSE,
			'optimizations' => -1,
		),
		'loader' => array (
			'class' => 'View_Loader',
			'extension' => 'twig',
			'options' => array(),
		),
		'extensions' => array (
			'Twig_Extension_Escaper',
			'Twig_Extension_Optimizer',
			'View_Extensions',
            'View_Extension_Custom',
		),
	),
);
