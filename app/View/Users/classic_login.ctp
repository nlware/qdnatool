<?php echo $this->Form->create('User', array('class' => 'form-horizontal')); ?>
<div class="modal">
	<div class="modal-header">
		<h4><?php echo __('Sign in'); ?></h4>
	</div>
	<div class="modal-body">
	<?php
	echo $this->Form->input('username', array('label' => __('Username')));
	echo $this->Form->input('password', array('label' => __('Password'), 'value' => ''));
	?>
	</div>
	<div class="modal-footer">
	<?php
	$returnToUrl = $this->Html->url(array('controller' => 'users', 'action' => 'saml_login'), true);
	$params = array(
		'AuthId' => 'SURFconext',
		'ReturnTo' => $returnToUrl
	);
	$url = '/simplesamlphp/module.php/core/as_login.php?' . http_build_query($params);
	echo $this->Html->link(__('Sign in via SURFconext'), $url, array('class' => 'btn'));
	echo $this->Form->submit(__('Sign in'), array('class' => 'btn btn-primary', 'div' => false));
	?>
	</div>
</div>
<?php
echo $this->Form->end();
