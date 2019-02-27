<?php
/**
 * @copyright     Copyright (c) NLWare B.V. (http://www.nlware.com)
 * @link          http://docs.qdnatool.org qDNAtool(tm) Project
 * @package       app.View.Pages
 * @license       http://creativecommons.org/licenses/by-nc-sa/3.0/deed.en_GB CC BY-NC-SA 3.0 License
 */

if (!Configure::read('debug')):
	throw new NotFoundException();
endif;

App::uses('Debugger', 'Utility');
?>
<h2><?php echo __d('cake_dev', 'Release Notes for CakePHP %s.', Configure::version()); ?></h2>
<p>
	<?php echo $this->Html->link(__d('cake_dev', 'Read the changelog'), 'https://cakephp.org/changelogs/' . Configure::version()); ?>
</p>
<?php
if (Configure::read('debug') > 0):
	Debugger::checkSecurityKeys();
endif;
?>
<?php if (file_exists(WWW_ROOT . 'css' . DS . 'cake.generic.css')): ?>
	<div class="alert alert-error">
		<?php echo __d('cake_dev', 'URL rewriting is not properly configured on your server.'); ?>
		<ol>
			<li><a target="_blank" href="https://book.cakephp.org/2.0/en/installation/url-rewriting.html" style="color:#fff;">Help me configure it</a></li>
			<li><a target="_blank" href="https://book.cakephp.org/2.0/en/development/configuration.html#cakephp-core-configuration" style="color:#fff;">I don't / can't use URL rewriting</a></li>
		</ol>
	</div>
<?php endif; ?>
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
		echo __d('cake_dev', 'The %s is being used for core caching. To change the config edit %s', '<em>' . $settings['engine'] . 'Engine</em>', CONFIG . 'core.php');
		echo '</div>';
	else:
		echo '<div class="alert alert-error">';
		echo __d('cake_dev', 'Your cache is NOT working. Please check the settings in %s', CONFIG . 'core.php');
		echo '</div>';
	endif;
	?>
</p>
<p>
	<?php
	$filePresent = null;
	if (file_exists(CONFIG . 'database.php')):
		echo '<div class="alert alert-success">';
		echo __d('cake_dev', 'Your database configuration file is present.');
		$filePresent = true;
		echo '</div>';
	else:
		echo '<div class="alert alert-error">';
		echo __d('cake_dev', 'Your database configuration file is NOT present.');
		echo '<br/>';
		echo __d('cake_dev', 'Rename %s to %s', CONFIG . 'database.php.default', CONFIG . 'database.php');
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
			if (isset($attributes['message'])):
				$errorMsg .= '<br />' . $attributes['message'];
			endif;
		endif;
	}
	?>
	<p>
		<?php
		if ($connected && $connected->isConnected()):
			echo '<div class="alert alert-success">';
			echo __d('cake_dev', 'CakePHP is able to connect to the database.');
			echo '</div>';
		else:
			echo '<div class="alert alert-error">';
			echo __d('cake_dev', 'CakePHP is NOT able to connect to the database.');
			echo '<br /><br />';
			echo $errorMsg;
			echo '</div>';
		endif;
		?>
	</p>
<?php
endif;

App::uses('Validation', 'Utility');
if (!Validation::alphaNumeric('cakephp')):
	echo '<p><div class="alert alert-error">';
	echo __d('cake_dev', 'PCRE has not been compiled with Unicode support.');
	echo '<br/>';
	echo __d('cake_dev', 'Recompile PCRE with Unicode support by adding <code>--enable-unicode-properties</code> when configuring');
	echo '</div></p>';
endif;
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
		echo __d('cake_dev', 'You can install it from %s', $this->Html->link('GitHub', 'https://github.com/cakephp/debug_kit/tree/2.2'));
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
	<?php
endif;
