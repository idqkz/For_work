<?php
App::uses('Model', 'Model');

class User extends AppModel {

	public function beforeSave($options = array()) {

		if (!empty($this->data['User']['password'])) {
			$this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
		} else {
			unset($this->data['User']['password']);
		}

		return true;
	}

	public function update_last_login($user_id) {
		$this->id = $user_id;
		$this->saveField('last_login', date('YmdHis'));
		return true;
	}
}

?>