<?php 
	echo $this->Form->create('Text');

	echo $this->Form->hidden('Email.Text.id');
	echo $this->Form->input('Email.Text.text', array('label' => 'E-mail для отзывов', 'type' => 'text'));

	echo $this->Form->hidden('Title.Text.id');
	echo $this->Form->input('Title.Text.text', array('label' => 'Заголовок сайта', 'type' => 'text'));

	echo $this->Form->hidden('Meta.Text.id');
	echo $this->Form->input('Meta.Text.text', array('label' => 'Описание сайта для поисковиков', 'type' => 'textarea'));

	echo $this->Form->submit('Сохранить', array('class' => 'btn btn-success'));
	echo $this->Form->end();
?>