<div class="users form">
  <h2><?php echo __('Change Password'); ?></h2>
  <?php
  	echo $this->Form->create('User');
  	echo $this->Form->input('current_password', array('type' => 'password', 'label' => __('Current password', true), 'value' => ''));
  	echo $this->Form->input('password', array('type' => 'password', 'label' => __('New password', true), 'value' => ''));
  	echo $this->Form->input('confirm_password', array('type' => 'password', 'label' => __('Confirm new password', true), 'value' => ''));
  ?>
	<div class="form-actions">
    <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-primary', 'div' => false)); ?>
    <?php echo $this->Html->link(__('Cancel'), array('action' => 'account'), array('class' => 'btn')); ?>
  </div>
  <?php echo $this->Form->end(); ?>
</div>