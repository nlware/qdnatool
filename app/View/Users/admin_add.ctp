<div class="users form">
  <h2><?php echo __('Add User'); ?></h2>
  <?php
  	echo $this->Form->create('User');
  	echo $this->Form->input('username');
  	echo $this->Form->input('name');
  	echo $this->Form->input('password');
  	echo $this->Form->input('role_id', array('empty' => true));
  ?>
	<div class="form-actions">
    <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-primary', 'div' => false)); ?>
    <?php echo $this->Html->link(__('Cancel'), array('admin' => false, 'action' => 'index'), array('class' => 'btn')); ?>
  </div>
  <?php echo $this->Form->end(); ?>
</div>