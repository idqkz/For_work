	<?php 
		echo $this->Form->create('User', 		array('inputDefaults' => array('class' => 'input-text', 'label' => false)));
		echo $this->Form->hidden('id');
		echo $this->Html->tag('h2', 'Пользователи');
		echo $this->Form->input('name', 		array('placeholder' => 'Имя '));
		echo $this->Form->input('email', 		array('placeholder' => 'адрес email'));
		echo $this->Form->input('password', array('placeholder'=>'Пароль', 'value' => ''));
		echo $this->Form->hidden('status', array('value' => '1'));
		echo $this->Form->hidden('register_date', array('value' => 'null'));
		echo $this->element('admin-form-buttons');
		echo $this->Form->end();

?>
<div class='users list'>
	<?php
		$html_items = $this->Html->tag('h2', 'Список пользователей');
		foreach ($users as $user) {
			$name = $this->html->div('col-8', $user['User']['name']);
			$edit_btn = $this->Html->div('col' , $this->Html->link('изменить', 
				array('controller' => 'admin', 'action' => $this->action, $user['User']['id']),
				array('class' => 'btn btn-success')));
			$html_items .= $this->Html->div('col-10', $name . $edit_btn);
		}
		echo $this->Html->div('col-10', $html_items);
	?>
</div>


<?php
	echo $this->Html->script('view-pass');
?>