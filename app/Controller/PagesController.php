<?php

App::uses('AppController', 'Controller');

class PagesController extends AppController {
	public $components = array('Auth');

	public $uses = array('Model');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow();
	}

	public function beforeRender() {
		parent::beforeRender();
		$title_for_layout = 'Чистый CakePHP для работы';
	}

	public function home() {

	}

	public function index() {
		$this->redirect(array('controller' => 'pages', 'action' => 'home'));	
	}

public function Pdf($id = null){	
    
    if(!$id){
        $this->Session->setFlash('Sorry, there was no property ID submitted.');
        // $this->redirect(array('action'=>'index'), null, true);
    } 
    // Configure::write('debug',0); // Otherwise we cannot use this method while developing

    $id = intval($id);

    $property = 'ЭТО ТЕКСТ ЁПТ!!'; // here the data is pulled from the database and set for the view

    if (empty($property))
    {
        $this->Session->setFlash('Sorry, there is no property with the submitted ID.');
        // $this->redirect(array('action'=>'index'), null, true);
    }
    $content_for_layout = $property;
    $this->set(compact('content_for_layout'));
    $this->layout = 'pdf'; //this will use the pdf.ctp layout
    $this->render();
}

}
