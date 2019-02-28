<?php
$class = !empty($params['class']) ? $params['class'] : 'message';
?>
<div
	id="<?php echo $key; ?>Message"
	class="<?php echo $class; ?>">
	<?php echo $message; ?>
</div>
