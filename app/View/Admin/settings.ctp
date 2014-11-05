<?php 
	echo $this->Form->create('Text');

	echo $this->Form->hidden('Email.Text.id');
	echo $this->Form->input('Email.Text.name', array('label' => 'E-mail для отзывов'));

	echo $this->Form->hidden('id');
	echo $this->Form->input('name', array('label' => 'Заголовок сайта'));
	echo $this->Form->input('text', array('label' => 'Описание сайта для поисковиков'));

	echo $this->Form->submit('Сохранить', array('class' => 'btn btn-success'));
	echo $this->Form->end();
?>