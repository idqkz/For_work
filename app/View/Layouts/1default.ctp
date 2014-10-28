<!DOCTYPE html>
<html lang='ru-RU'>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css(array('init', 'theme'));

		echo $this->Html->script(array('jquery-2.0.3.min', 'TweenLite.min', 'CSSPlugin.min', 'site_functions'));

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
	<?php
		// echo $this->element('block-items_out');

		echo $this->Session->flash();

		echo $this->fetch('content'); 

		// echo $this->element('sql_dump');

	?>

	<div class='popup-bg'></div>
</body>
</html>
