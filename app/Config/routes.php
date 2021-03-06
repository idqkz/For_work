<?php

	Router::connect('/', array('controller' => 'pages', 'action' => 'home'));
	Router::connect('/logout', array('controller' => 'admin', 'action' => 'logout'));
	Router::connect('/admin', array('controller' => 'admin', 'action' => 'settings'));

/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
