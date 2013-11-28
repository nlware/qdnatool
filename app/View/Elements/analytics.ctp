<?php
if (Configure::read('debug') == 0 && Configure::read('Config.googleAnalytics')):
	$userId = AuthComponent::user('id');
	$userEmail = AuthComponent::user('username');
	$userEmailDomain = substr(strrchr($userEmail, '@'), 1);
?>
<script type="text/javascript">
var _gaq = _gaq || [];
_gaq.push(['_setAccount', '<?php echo Configure::read('Config.googleAnalytics'); ?>']);
_gaq.push(['_trackPageview']);
_gaq.push(['_setCustomVar',1,'UserId','<?php echo $userId; ?>',1]);
_gaq.push(['_setCustomVar',2,'UserDomain','<?php echo $userEmailDomain; ?>',1]);
(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
</script>
<?php
endif;