<?php
$script = '
$(document).ready(function() {
	$(".tab-content").each(function() {
		$(this).height($(window).height()-$(this).offset().top);
	});
});
';
echo $this->Html->scriptBlock($script, array('inline' => false));
if (!empty($instruction)):
?>
	<h4><?php echo h($instruction['Instruction']['name']); ?></h4>
<?php
	echo str_replace('target="_self"', 'target="_blank"', $instruction['Instruction']['content']);
endif;