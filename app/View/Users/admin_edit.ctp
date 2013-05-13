<div class="users form">
  <h2><?php echo __('Edit User'); ?></h2>
  <div class="actions">
  	<ul>
  		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('User.id')), null, __('Are you sure you want to delete # %s "%s"?', $this->Form->value('User.id'), $this->Form->value('User.username'))); ?></li>
  	</ul>
  </div>
  <?php
  	echo $this->Form->create('User');
  	echo $this->Form->input('id');
  	echo $this->Form->input('username', array('label' => __('Username')));
  	echo $this->Form->input('name', array('label' => __('Name')));
  	echo $this->Form->input('password', array('label' => __('Password'), 'value' => '', 'required' => 'false'));
  	echo $this->Form->input('role_id', array('label' => __('Role'), 'empty' => true));
  ?>
	<div class="form-actions">
    <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-primary', 'div' => false)); ?>
    <?php echo $this->Html->link(__('Cancel'), array('admin' => false, 'action' => 'index'), array('class' => 'btn')); ?>
  </div>
  <?php echo $this->Form->end(); ?>
</div>