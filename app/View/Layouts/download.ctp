<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo $this->Html->charset(); ?>
	<title>
	<?php echo $title_for_layout; ?>
	</title>
<?php
echo $this->Html->meta('icon');
echo $this->fetch('script');
?>
</head>
<body>
	<div id="container">
		<div id="header">
		</div>
		<div id="content">
		<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer"></div>
	</div>
</body>
</html>