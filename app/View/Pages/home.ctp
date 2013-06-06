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
 * @package       app.View.Pages
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
if (!Configure::read('debug')):
	throw new NotFoundException();
endif;
App::uses('Debugger', 'Utility');
?>
<h2><?php echo __d('cake_dev', 'Release Notes for CakePHP %s.', Configure::version()); ?></h2>
<p>
	<a href="http://cakephp.org/changelogs/<?php echo Configure::version(); ?>"><?php echo __d('cake_dev', 'Read the changelog'); ?> </a>
</p>
<?php
if (Configure::read('debug') > 0):
	Debugger::checkSecurityKeys();
endif;
?>
<div class="alert alert-error">
	<?php echo __d('cake_dev', 'URL rewriting is not properly configured on your server.'); ?>
	<ol>
		<li><a target="_blank" href="http://book.cakephp.org/2.0/en/installation/url-rewriting.html" style="color:#fff;">Help me configure it</a></li>
		<li><a target="_blank" href="http://book.cakephp.org/2.0/en/development/configuration.html#cakephp-core-configuration" style="color:#fff;">I don't / can't use URL rewriting</a></li>
	</ol>
</div>
<p>
<?php
	if (version_compare(PHP_VERSION, '5.2.8', '>=')):
		echo '<div class="alert alert-success">';
			echo __d('cake_dev', 'Your version of PHP is 5.2.8 or higher.');
		echo '</div>';
	else:
		echo '<div class="alert alert-error">';
			echo __d('cake_dev', 'Your version of PHP is too low. You need PHP 5.2.8 or higher to use CakePHP.');
		echo '</div>';
	endif;
?>
</p>
<p>
	<?php
		if (is_writable(TMP)):
			echo '<div class="alert alert-success">';
				echo __d('cake_dev', 'Your tmp directory is writable.');
			echo '</div>';
		else:
			echo '<div class="alert alert-error">';
				echo __d('cake_dev', 'Your tmp directory is NOT writable.');
			echo '</div>';
		endif;
	?>
</p>
<p>
	<?php
		$settings = Cache::settings();
		if (!empty($settings)):
			echo '<div class="alert alert-success">';
				echo __d('cake_dev', 'The %s is being used for core caching. To change the config edit APP/Config/core.php ', '<em>' . $settings['engine'] . 'Engine</em>');
			echo '</div>';
		else:
			echo '<div class="alert alert-error">';
				echo __d('cake_dev', 'Your cache is NOT working. Please check the settings in APP/Config/core.php');
			echo '</div>';
		endif;
	?>
</p>
<p>
	<?php
		$filePresent = null;
		if (file_exists(APP . 'Config' . DS . 'database.php')):
			echo '<div class="alert alert-success">';
				echo __d('cake_dev', 'Your database configuration file is present.');
				$filePresent = true;
			echo '</div>';
		else:
			echo '<div class="alert alert-error">';
				echo __d('cake_dev', 'Your database configuration file is NOT present.');
				echo '<br/>';
				echo __d('cake_dev', 'Rename APP/Config/database.php.default to APP/Config/database.php');
			echo '</div>';
		endif;
	?>
</p>
<?php
if (isset($filePresent)):
	App::uses('ConnectionManager', 'Model');
	try {
		$connected = ConnectionManager::getDataSource('default');
	} catch (Exception $connectionError) {
		$connected = false;
		$errorMsg = $connectionError->getMessage();
		if (method_exists($connectionError, 'getAttributes')):
			$attributes = $connectionError->getAttributes();
			if (isset($errorMsg['message'])):
				$errorMsg .= '<br />' . $attributes['message'];
			endif;
		endif;
	}
?>
<p>
	<?php
		if ($connected && $connected->isConnected()):
			echo '<div class="alert alert-success">';
				echo __d('cake_dev', 'Cake is able to connect to the database.');
			echo '</div>';
		else:
			echo '<div class="alert alert-error">';
				echo __d('cake_dev', 'Cake is NOT able to connect to the database.');
				echo '<br /><br />';
				echo $errorMsg;
			echo '</div>';
		endif;
	?>
</p>
<?php endif; ?>
<?php
	App::uses('Validation', 'Utility');
	if (!Validation::alphaNumeric('cakephp')) {
		echo '<p><div class="alert alert-error">';
			echo __d('cake_dev', 'PCRE has not been compiled with Unicode support.');
			echo '<br/>';
			echo __d('cake_dev', 'Recompile PCRE with Unicode support by adding <code>--enable-unicode-properties</code> when configuring');
		echo '</div></p>';
	}
?>

<p>
	<?php
		if (CakePlugin::loaded('DebugKit')):
			echo '<div class="alert alert-success">';
				echo __d('cake_dev', 'DebugKit plugin is present');
			echo '</div>';
		else:
			echo '<div class="alert alert-error">';
				echo __d('cake_dev', 'DebugKit is not installed. It will help you inspect and debug different aspects of your application.');
				echo '<br/>';
				echo __d('cake_dev', 'You can install it from %s', $this->Html->link('github', 'https://github.com/cakephp/debug_kit'));
			echo '</div>';
		endif;
	?>
</p>

<p>
	<?php
		$filePresent = null;
		if (file_exists(APP . 'Config' . DS . 'rserve.php')):
			echo '<div class="alert alert-success">';
				echo __d('cake_dev', 'Your Rserve configuration file is present.');
				$filePresent = true;
			echo '</div>';
		else:
			echo '<div class="alert alert-error">';
				echo __d('cake_dev', 'Your Rserve configuration file is NOT present.');
				echo '<br/>';
				echo __d('cake_dev', 'Rename APP/Config/rserve.php.default to APP/Config/rserve.php');
			echo '</div>';
		endif;
	?>
</p>
<?php
if (isset($filePresent)):
	App::uses('Rserve', 'Lib');
	if ($connection = Rserve::connect()):
		$connected = true;
		$connection->close();
	else:
		$connected = false;
	endif;
?>
<p>
	<?php
		if ($connected):
			echo '<div class="alert alert-success">';
				echo __d('cake_dev', 'Cake is able to connect to Rserve.');
			echo '</div>';
		else:
			echo '<div class="alert alert-error">';
				echo __d('cake_dev', 'Cake is NOT able to connect to Rserve.');
			echo '</div>';
		endif;
	?>
</p>
<?php endif; ?>
<?php
if (file_exists('/usr/share/simplesamlphp/lib/_autoload.php')):
	echo '<div class="alert alert-success">';
		echo __d('cake_dev', 'SimpleSAMLphp is present');
	echo '</div>';
else:
	echo '<div class="alert alert-error">';
		echo __d(
			'cake_dev',
			'%s is not installed. It is needed to log in with %s.',
			$this->Html->link('SimpleSAMLphp', 'http://simplesamlphp.org/'),
			$this->Html->link('SURFconext', 'http://www.surfnet.nl/nl/Thema/coin')
		);
	echo '</div>';
endif;
