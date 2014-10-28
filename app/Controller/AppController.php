<?php

App::uses('Controller', 'Controller');

class AppController extends Controller {
	// Ссылка на плагин
	// https://github.com/BradCrumb/lesscompiler
	public $components = array(
	    'LessCompiler.less'     => array(
	    	'sourceFolder'      => 'webroot\less',
	    	)
    );

}
