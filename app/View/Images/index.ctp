<div class="images index">
	<h3><?php echo __('Images');?></h3>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('question_id');?></th>
			<th><?php echo $this->Paginator->sort('filename');?></th>
			<th><?php echo $this->Paginator->sort('filetype');?></th>
			<th><?php echo $this->Paginator->sort('file');?></th>
			<th><?php echo $this->Paginator->sort('filesize');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
		</tr>
	<?php
	$i = 0;
	foreach ($images as $image):
	?>
		<tr>
			<td><?php echo h($image['Image']['id']); ?>&nbsp;</td>
			<td>
			<?php echo $this->Html->link($image['Question']['name'], array('controller' => 'questions', 'action' => 'view', $image['Question']['id'])); ?>
			</td>
			<td><?php echo h($image['Image']['filename']); ?>&nbsp;</td>
			<td><?php echo h($image['Image']['filetype']); ?>&nbsp;</td>
			<td><?php echo h($image['Image']['file']); ?>&nbsp;</td>
			<td><?php echo h($image['Image']['filesize']); ?>&nbsp;</td>
			<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $image['Image']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $image['Image']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $image['Image']['id']), null, __('Are you sure you want to delete # %s?', $image['Image']['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
		'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>
	</p>
	<div class="paging">
	<?php
	echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
	echo $this->Paginator->numbers(array('separator' => ''));
	echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Image'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Questions'), array('controller' => 'questions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Question'), array('controller' => 'questions', 'action' => 'add')); ?> </li>
	</ul>
</div>
