<?php 
	if(!isset($funcNum)) $funcNum = '';
	if(empty($url)) $url = '';
	if(empty($message)) $message = '';
	$script = sprintf('window.parent.CKEDITOR.tools.callFunction(%s, \'%s\', \'%s\');', $funcNum, $url, $message);
	echo $this->Html->scriptBlock($script);
?>
