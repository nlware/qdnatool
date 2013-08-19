<?php
if (!isset($funcNum)):
	$funcNum = '';
endif;
if (empty($url)):
	$url = '';
endif;
if (empty($message)):
	$message = '';
endif;
$script = sprintf('window.parent.CKEDITOR.tools.callFunction(%s, \'%s\', \'%s\');', $funcNum, $url, $message);
echo $this->Html->scriptBlock($script);
