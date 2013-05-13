<?php
$script = '
$(document).ready(function() {
$(".tab-content").each(function()
{ $(this).height($(window).height()-$(this).offset().top+$(window).scrollTop());
});
});
';
echo $this->Html->scriptBlock($script, array('inline' => false));
if(empty($analyses)):
?>
<div class="alert alert-success"><?php echo __('The question doesn\'t seem to include common structural errors.'); ?></div>
<?php else: ?>
<?php foreach ($analyses as $analyse): ?>

<div class="alert alert-error"><?php echo $this->HtmLawed->display($analyse, array('elements' => 'a, i, p, u')); ?></div>
<?php
  endforeach;
endif;