<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
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
			echo $this->Session->flash();
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
