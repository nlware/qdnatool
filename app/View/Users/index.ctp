<div class="users index">
  <?php
    if(AuthComponent::user('role_id') == Role::ADMIN):
      echo $this->Html->link('<i class="icon-plus icon-white"></i> '.__('Add User'), array(	'admin' => true, 'action' => 'add'), array('class' => 'btn btn-primary pull-right', 'escape' => false));
    endif;
  ?>
  <h2><?php echo __('Users');?></h2>
	<table class="table">
	  <thead>
    	<tr>
  			<th><?php echo $this->Paginator->sort('id');?></th>
  			<th><?php echo $this->Paginator->sort('username');?></th>
  			<th><?php echo $this->Paginator->sort('name');?></th>
  			<th><?php echo $this->Paginator->sort('role_id');?></th>
  			<th class="actions"><?php echo __('Actions');?></th>
    	</tr>
    </thead>
    <tbody>
	  <?php	foreach ($users as $user): ?>
    	<tr>
    		<td><?php echo h($user['User']['id']); ?>&nbsp;</td>
    		<td><?php echo h($user['User']['username']); ?>&nbsp;</td>
    		<td><?php echo h($user['User']['name']); ?>&nbsp;</td>
    		<td><?php echo h($user['Role']['name']); ?>&nbsp;</td>
    		<td class="actions">
    			<?php
    				echo $this->Html->link
    				(	$this->Html->image
    					(	'view.png', array
    						(	'alt' => __('View')
    						)
    					), array
    					(	'action' => 'view',
    						$user['User']['id']
    					), array
    					(	'title' => __('View'),
    						'class' => 'actionIcon',
    						'escape' => false
    					)
    				);
    				if(AuthComponent::user('role_id') == Role::ADMIN):
    					echo $this->Html->link
    					(	$this->Html->image
    						(	'edit.png', array
    							(	'alt' => __('Edit')
    							)
    						), array
    						(	'admin' => true,
    							'action' => 'edit',
    							$user['User']['id']
    						), array
    						(	'title' => __('Edit'),
    							'class' => 'actionIcon',
    							'escape' => false
    						)
    					);
    					echo $this->Form->postLink
    					(	$this->Html->image
    						(	'delete.png', array
    							(	'alt' => __('Delete')
    							)
    						), array
    						(	'admin' => true,
    							'action' => 'delete',
    							$user['User']['id']
    						), array
    						(	'class' => 'actionIcon',
    							'title' => __('Delete'),
    							'escape' => false
    						),
    						__('Are you sure you want to delete # %s "%s"?', $user['User']['id'], $user['User']['username'])
    					);
    				endif;
    				if($user['User']['id'] == AuthComponent::user('id')):
    					echo $this->Html->link
    					(	$this->Html->image
    						(	'settings.png', array
    							(	'alt' => __('Change password')
    							)
    						), array
    						(	'action' => 'change_password'
    						), array
    						(	'title' => __('Change password'),
    							'class' => 'actionIcon',
    							'escape' => false
    						)
    					);
    				endif;
    			?>
    		</td>
    	</tr>
    <?php endforeach; ?>
    </tbody>
	</table>
	<?php echo $this->Paginator->pagination(); ?>
</div>