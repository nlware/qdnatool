<?php if(Configure::read('debug') && Configure::read('debug')==0 && Configure::read('Config.googleAnalytics')): ?>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo Config::read('Config.googleAnalytics'); ?>']);
  _gaq.push(['_trackPageview']);
  _gaq.push(['_setCustomVar',1,'UserId','<?php echo AuthComponent::user('id'); ?>',1]);  
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<?php endif; ?>