<?php
/**
 * @copyright     Copyright (c) NLWare B.V. (http://www.nlware.com)
 * @link          http://docs.qdnatool.org qDNAtool(tm) Project
 * @license       http://creativecommons.org/licenses/by-nc-sa/3.0/deed.en_GB CC BY-NC-SA 3.0 License
 */
?>
<!DOCTYPE html>
<html>
<head>
<?php echo $this->Html->charset(); ?>
<title><?php echo $page_title; ?></title>

<?php if (Configure::read('debug') == 0): ?>
<meta http-equiv="Refresh" content="<?php echo $pause; ?>;url=<?php echo $url; ?>"/>
<?php endif; ?>
<style><!--
P { text-align:center; font:bold 1.1em sans-serif }
A { color:#444; text-decoration:none }
A:HOVER { text-decoration: underline; color:#44E }
--></style>
</head>
<body>
<p><a href="<?php echo $url; ?>"><?php echo $message; ?></a></p>
</body>
</html>