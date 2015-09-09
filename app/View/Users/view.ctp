<h2><?php  echo __('User');?></h2>
<div class="actions">
	<ul>
	<?php if (AuthComponent::user('role_id') == Role::ADMIN): ?>
		<li><?php echo $this->Html->link(__('Edit User'), array('admin' => true, 'action' => 'edit', $user['User']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete User'), array('admin' => true, 'action' => 'delete', $user['User']['id']), array('confirm' => __('Are you sure you want to delete # %s "%s"?', $user['User']['id'], $user['User']['username']))); ?> </li>
	<?php endif; ?>
		<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?></li>
	<?php if (AuthComponent::user('role_id') == Role::ADMIN): ?>
		<li><?php echo $this->Html->link(__('New User'), array('admin' => true, 'action' => 'add')); ?></li>
	<?php endif; ?>
	</ul>
</div>
<div class="users view">
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd><?php echo h($user['User']['id']); ?>&nbsp;</dd>
		<dt><?php echo __('Username'); ?></dt>
		<dd><?php echo h($user['User']['username']); ?>&nbsp;</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd><?php echo h($user['User']['name']); ?>&nbsp;</dd>
		<dt><?php echo __('Role'); ?></dt>
		<dd><?php echo h($user['Role']['name']); ?>&nbsp;</dd>
	</dl>
</div>