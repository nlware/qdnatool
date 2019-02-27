<?php
/**
 * @copyright     Copyright (c) NLWare B.V. (http://www.nlware.com)
 * @link          http://docs.qdnatool.org qDNAtool(tm) Project
 * @package       app.View.Layouts
 * @license       http://creativecommons.org/licenses/by-nc-sa/3.0/deed.en_GB CC BY-NC-SA 3.0 License
 */
?>
<!DOCTYPE html>
<html>
<head>
<?php echo $this->Html->charset(); ?>
<title><?php echo $pageTitle; ?></title>

<?php
if (Configure::read('debug') == 0):
	echo sprintf('<meta http-equiv="Refresh" content="%s;url=%s" />', $pause, $url);
endif;
?>
<style><!--
P { text-align:center; font:bold 1.1em sans-serif }
A { color:#444; text-decoration:none }
A:HOVER { text-decoration: underline; color:#44E }
--></style>
</head>
<body>
<p>
	<?php echo $this->Html->link($message, $url); ?>
</p>
</body>
</html>
