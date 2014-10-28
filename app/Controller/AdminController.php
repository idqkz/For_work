<?php

App::uses('AppController', 'Controller');

class AdminController extends AppController {

	public $uses = array('User', 'Text');

	public $components = array(
		'Auth' => array(
			'all' => array('userModel' => 'User'),
			'authenticate' => array(
	             'Form' => array(
	                 'fields' => array('username' => 'email', 'password' => 'password')
	             )
	         ),
		    'loginAction' => array(
		        'controller' => 'admin',
		        'action' => 'login'
		    ),
		    'authError' => 'Неверный логин или пароль',
		),
		'Paginator'
	);

	public function beforeRender() {
		parent::beforeRender();

		$main_menu_items = array(
			'Настройки'				=>	array('controller' => 'admin', 'action' => 'settings'),
			// 'под меню'					=>	array('controller' => 'admin', 'action' => 'blocksall'
				// ,'sub_menu' => $menu_of_blocks
				// ),
			'Пользователи'			=>	array('controller' => 'admin', 'action' => 'users')
		);

		// if (!$this->Auth->user()) {
		// 	$main_menu_items = null;
		// }

		$title_for_layout = 'Страницы управления';
		$this->set(compact('title_for_layout', 'main_menu_items'));
	}

	public function beforeFilter() {
		parent::beforeFilter();
		$this->layout = 'admin';
		$this->Auth->allow(array('login'));
		$this->Auth->allow();
	}

	public function login() {
		if ($this->request->is('post')) {
	        if ($this->Auth->login()) {
	        	$this->User->update_last_login($this->Auth->user('id'));

	        	$this->Session->setFlash($this->Auth->user('name') .' добро пожаловать ');
	            return $this->redirect(array('controller' => 'admin', 'action' => null));
	        } else {
	        	$this->Session->setFlash('Войти не удалось');
	        }
	    }
	}

	public function logout() {
		$this->Auth->logout();
		$this->redirect('/');
	}

	public function settings(){
		foreach ($this->data as $type => $data_save) {
			$data_save['Text']['name'] = $type;
			$this->Text->save($data_save);
		}

		$this->request->data['Email'] = $this->Text->findByName('Email');
		$this->request->data['Title'] = $this->Text->findByName('Title');
		$this->request->data['Meta'] = $this->Text->findByName('Meta');
	}

	public function users($id = null){
		if(!empty($this->data)){
			$this->User->save($this->data);
		}

		if($id != null){
			$this->request->data = $this->User->findById($id);
		}

		$users = $this->User->find('all');
		$this->set(compact('users'));
	}

	public function index(){

	}

}
