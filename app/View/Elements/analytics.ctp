<?php
if (Configure::read('debug') == 0 && Configure::read('Config.googleAnalytics')):
	$userId = AuthComponent::user('id');
	$userEmail = AuthComponent::user('username');
	$userEmailDomain = substr(strrchr($userEmail, '@'), 1);

	$script = array();
	$script[] = 'var _gaq = _gaq || [];';
	$script[] = '_gaq.push([\'_setAccount\', \'' . Configure::read('Config.googleAnalytics') . '\']);';
	$script[] = '_gaq.push([\'_trackPageview\']);';
	$script[] = '_gaq.push([\'_setCustomVar\',1,\'UserId\',\'' . $userId . '\',1]);';
	$script[] = '_gaq.push([\'_setCustomVar\',2,\'UserDomain\',\'' . $userEmailDomain . '\',1]);';
	$script[] = '(function() {';
	$script[] = '	var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;';
	$script[] = '	ga.src = (\'https:\' == document.location.protocol ? \'https://\' : \'http://\') + \'stats.g.doubleclick.net/dc.js\';';
	$script[] = '	var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);';
	$script[] = '})();';

	$script = implode("\n", $script);
	echo $this->Html->scriptBlock($script);
endif;
