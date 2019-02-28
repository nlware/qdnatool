<?php
/**
 * @copyright     Copyright (c) NLWare B.V. (https://www.nlware.com)
 * @link          https://docs.qdnatool.org qDNAtool(tm) Project
 * @package       app.View.Layouts
 * @license       https://creativecommons.org/licenses/by-nc-sa/3.0/deed.en_GB CC BY-NC-SA 3.0 License
 */
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $this->fetch('title'); ?>
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php
	echo $this->Html->meta('icon');
	echo $this->Html->css('bootstrap.min');
	echo $this->Html->css('application');
	?>
	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<?php
	echo $this->fetch('meta');
	echo $this->fetch('css');
	?>
</head>
<body>
	<?php
	echo $this->element('analytics');
	echo $this->element('header');
	?>
	<div class="container">
		<?php
		echo $this->Flash->render();
		echo $this->fetch('content');
		?>
		<div id="footer"></div>
	</div>
	<?php
	echo $this->Html->script('//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
	echo $this->Html->script('bootstrap.min');
	echo $this->fetch('script');
	echo $this->Js->writeBuffer();
	?>
</body>
</html>
