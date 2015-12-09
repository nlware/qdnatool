<h2><?php echo __('Question Formats');?></h2>
<div class="actions">
</div>
<div class="questionFormats index">
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
		</tr>
	<?php foreach ($questionFormats as $questionFormat): ?>
		<tr>
			<td><?php echo h($questionFormat['QuestionFormat']['id']); ?>&nbsp;</td>
			<td><?php echo h($questionFormat['QuestionFormat']['name']); ?>&nbsp;</td>
			<td class="actions">
			<?php
			echo $this->Html->link(
				$this->Html->image(
					'view.png', array(
						'alt' => __('View')
					)
				), array(
					'action' => 'view',
					$questionFormat['QuestionFormat']['id']
				), array(
					'title' => __('View'),
					'class' => 'actionIcon',
					'escapeTitle' => false
				)
			);
			echo $this->Html->link(
				$this->Html->image(
					'edit.png', array(
						'alt' => __('Edit')
					)
				), array(
					'admin' => true,
					'action' => 'edit',
					$questionFormat['QuestionFormat']['id']
				), array(
					'title' => __('Edit'),
					'class' => 'actionIcon',
					'escapeTitle' => false
				)
			);
			?>
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