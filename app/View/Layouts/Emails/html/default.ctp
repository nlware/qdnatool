<?php
/**
 * @copyright     Copyright (c) NLWare B.V. (https://www.nlware.com)
 * @link          https://docs.qdnatool.org qDNAtool(tm) Project
 * @package       app.View.Layouts.Email.html
 * @license       https://creativecommons.org/licenses/by-nc-sa/3.0/deed.en_GB CC BY-NC-SA 3.0 License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
	<title><?php echo $this->fetch('title'); ?></title>
</head>
<body>
	<?php echo $this->fetch('content'); ?>
</body>
</html>