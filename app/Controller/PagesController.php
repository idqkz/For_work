<?php

App::uses('AppController', 'Controller');

class PagesController extends AppController {
	public $components = array('Auth');

	// public $uses = array('Item','Order','ItemsOrder','User');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow();
	}

	public function beforeRender() {
		parent::beforeRender();
		$title_for_layout = 'suhi';
	}

	public function home() {
		// $items = $this->Item->find('all');
		// $this->set(compact('items'));
	}

	public function index() {
		$this->redirect(array('controller' => 'pages', 'action' => 'home'));	
	}
}
