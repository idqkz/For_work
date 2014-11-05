<?php
App::uses('Model', 'Model');

class Text extends AppModel {

	public $actsAs = array('Containable');
	public $order = 'Text.order ASC';

	public $hasOne = array(
		'Image' => array(
			'className' => 'Image',	
			'foreignKey' => 'parent_id',
			'dependent' => true,
			'conditions' => array('NOT' => array('Image.type' => 'price')),
			)
		);

	public function beforeSave($options = array()){
		// Сохранение порядка
		if(empty($this->data['Text']['order'])){
			$order = $this->find('all', array(
				'fields' => 'MAX(Text.order) as max',
				'conditions' => array('Text.type' => $this->data['Text']['type']),
				));
			$this->data['Text']['order'] = $order[0][0]['max'] + 1;
		}
	}

	public function beforeDelete($cascade = true) {
		// Изменение порядка при удалении
		$item = $this->findById($this->id);
		$items = $this->find('all', array(
			'conditions' => array(
				'Text.order >' => $item['Text']['order'],
				'Text.type' => $item['Text']['type'],
				),
			'contain' => false,
			));
		foreach ($items as &$value) {
			$value['Text']['order']--;
			$value = $value['Text'];
		}
		$this->saveMany($items);
	}

	public function afterSave($created, $options = array()){
		// Сохранение изображения
		if(!empty($this->data['Text']['image']['name'])){
			$Image_model = ClassRegistry::init('Image');
			$Image_model->new_image($this->data['Text']['image'], $this->data['Text']['type'], $this->data['Text']['id']);
		} else {
			unset($this->data['Text']['image']);
		}
	}
}

?>