<?php

App::uses('AppController', 'Controller');

class AdminController extends AppController {
	public $uses = array('Text', 'Image', 'User');

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
			'Настройки'						=>	array('controller' => 'admin', 'action' => 'settings'),
			'Пользователи'					=>	array('controller' => 'admin', 'action' => 'users'),
			'Адрес'							=>	array('controller' => 'admin', 'action' => 'address'),
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
	            return $this->redirect(array('controller' => 'admin', 'action' => 'settings'));
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
		if (!empty($this->data)){
			$this->request->data['Email']['Text']['type'] = 'email_feedback';
			$this->request->data['Text']['type'] = 'site_settings';
			$this->Text->save($this->data['Text']);
			$this->Text->save($this->data['Email']['Text']);
			unset($this->data);
			$this->Session->setFlash('Изменения сохранены');
		}
		$this->request->data = $this->Text->find('first', 
			array('conditions' => array('Text.type' => 'site_settings'),
				'contain' => false));
		$this->request->data['Email'] = $this->Text->find('first', 
			array('conditions' => array('Text.type' => 'email_feedback'),
				'contain' => false));
	}

	public function users($id = null){
		if (!empty($this->data)){
			$this->request->data['User']['register_date'] = date('Y-m-d H:i:s');
			$this->User->save($this->data['User']);
			$this->Session->setFlash('"' . $this->data['User']['name'] . '" изменён');
			unset($this->data);
			$this->redirect(array('controller' => 'admin', 'action' => 'users'));
		}

		if($id != null){
			$this->request->data = $this->User->findById($id);
		}

		$users = $this->User->find('all');
		$this->set(compact('id', 'users'));
	}	

	public function address(){
		if (!empty($this->data)){
			$this->request->data['Text']['type'] = 'address';
			$this->Text->save($this->data['Text']);
			$this->Session->setFlash('Адрес изменён');
			unset($this->data);
		}

		$this->request->data = $this->Text->find('first', 
			array('conditions' => array('Text.type' => 'address')));
	}

	function save_and_find_function($type = null, $id = null){
		if (!empty($this->data)){
			$this->request->data['Text']['type'] = $type;
			$this->Text->save($this->data['Text']);
			if(!empty($this->data['Text']['id'])){
				$this->Session->setFlash('"' . $this->data['Text']['name'] . '" изменён');
			} else {
				$this->Session->setFlash('Сохранено успешно');
			}
			unset($this->data);
			$this->redirect(array('controller' => 'admin', 'action' => $type));
		}

		if($id != null){
			$this->request->data = $this->Text->find('first', 
				array('conditions' => array(
					'Text.type' => $type,
					'Text.id' => $id
			)));
		}

		$items = $this->Text->find('all', array('conditions' => array('Text.type' => $type)));
		$this->set(compact('id', 'items'));
	}

	public function update_item_order(){
		$this->autoRender = false;

		// Сохранение слиянием
		$list = $_POST['list'];
		$list = Hash::combine($list, '{n}.0', '{n}.1');
		$item = $this->Text->findById($_POST['item-id']);
		$items = $this->Text->find('all', array('contain' => false,
				'fields' => array('Text.id', 'Text.order'), 
				'conditions' => array(
					'Text.type' => $item['Text']['type'],
					/*'Text.order BETWEEN ? AND ?' => array($operand[1], $operand[2]),*/
					)
				)
			);

		$save_order = null;

		foreach ($items as $item_data) {
			if($item_data['Text']['order'] != $list[$item_data['Text']['id']]){
				$save_order[] = array(
					'id' 	=> $item_data['Text']['id'],
					'order' => $list[$item_data['Text']['id']],
					);
			}
		}
		$this->Text->saveMany($save_order);
		return true;
	}
	
// Функции удаления 
	// Из БД Text
	public function delete_text($id = null){
		if($id != null){
			$type = $this->Text->findById($id);
			$type = $type['Text']['type'];
			$this->Text->delete($id);
			$this->Session->setFlash('Удаление прошло успешно');
		} else{
			$this->Session->setFlash('Произошла ошибка');
		}
		$this->redirect(array('controller' => 'admin', 'action' => $type));
	}

	// Из БД Image
	public function image_delete($action = null, $parent_id = null, $id = null){
		if($id != null){
			$this->Image->delete($id);
			$this->Session->setFlash('Удаление прошло успешно');
		}
		$this->redirect(array('controller' => 'admin', 'action' => $action, $parent_id));
	}

	// Из БД User
	public function delete_users($id) {
		if($id != null){
			$this->User->delete($id);
			$this->Session->setFlash('Удаление прошло успешно');
		}
		$this->redirect(array('controller' => 'admin', 'action' => 'users'));
	}
// Конец функций удаления

}
