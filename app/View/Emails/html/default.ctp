<?php
/**
 * @copyright     Copyright (c) NLWare B.V. (https://www.nlware.com)
 * @link          https://docs.qdnatool.org qDNAtool(tm) Project
 * @package       app.View.Emails.html
 * @license       https://creativecommons.org/licenses/by-nc-sa/3.0/deed.en_GB CC BY-NC-SA 3.0 License
 */
?>
<?php
$content = explode("\n", $content);

foreach ($content as $line):
	echo '<p> ' . $line . "</p>\n";
endforeach;
