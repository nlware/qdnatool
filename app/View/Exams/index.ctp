<?php $doAutoRefresh = false; ?>
<div class="exams index">
	<?php echo $this->Html->link('<i class="icon-plus icon-white"></i> ' . __('Add Exam'), array('action' => 'add'), array('class' => 'btn btn-primary pull-right', 'escape' => false)); ?>
	<h4><?php echo __('Exams'); ?></h4>
	<table class="table">
		<thead>
			<tr>
				<th><?php echo $this->Paginator->sort('name', __('Name')); ?></th>
				<th><?php echo $this->Paginator->sort('ExamState.name', __('State')); ?></th>
				<th><?php echo $this->Paginator->sort('created', __('Created')); ?></th>
				<th><?php echo $this->Paginator->sort('modified', __('Modified')); ?></th>
				<th class="actions"><?php echo __('Actions'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($exams as $exam): ?>
			<tr>
				<td><?php echo h($exam['Exam']['name']); ?>&nbsp;</td>
				<td>
				<?php
				if (in_array($exam['Exam']['exam_state_id'], array(ExamState::ANALYSING, ExamState::IMPORTING, ExamState::WAITING_TO_ANALYSE, ExamState::WAITING_TO_IMPORT, ExamState::WAITING_TO_GENERATE_REPORT, ExamState::GENERATING_REPORT, ExamState::WAITING_TO_REANALYSE))):
					$doAutoRefresh = true;
					echo $this->Html->link(
						'<i class="icon-time"></i>',
						'#', array(
							'escape' => false,
							'data-original-title' => $exam['ExamState']['name'],
							'data-content' => __('The status will update automatically.'),
							'data-placement' => 'top',
							'rel' => 'popover'
						)
					);
				endif;
				echo h($exam['ExamState']['name']);
				?>
				</td>
				<td><?php echo h($exam['Exam']['created']); ?>&nbsp;</td>
				<td><?php echo h($exam['Exam']['modified']); ?>&nbsp;</td>
				<td class="actions">
					<div class="btn-group">
					<?php
					if (!empty($exam['Exam']['analysed'])):
						echo $this->Html->link(__('Start interpretation'), array('action' => 'stevie', $exam['Exam']['id']), array('class' => 'btn'));
					elseif (empty($exam['Child'])):
						echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $exam['Exam']['id']), array('class' => 'btn', 'confirm' => __('Are you sure you want to delete exam "%s"?', $exam['Exam']['name'])));
					endif;
					?>
						<button class="btn dropdown-toggle" data-toggle="dropdown">
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<?php
							if (!empty($exam['Exam']['analysed'])):
							?>
							<li><?php echo $this->Html->link(__('Start interpretation'), array('action' => 'stevie', $exam['Exam']['id'])); ?></li>
							<?php
							endif;
							if ($exam['Exam']['exam_state_id'] == ExamState::REPORT_GENERATED):
							?>
							<li><?php echo $this->Html->link(__('Download report'), array('action' => 'report', $exam['Exam']['id'], 'ext' => 'pdf')); ?></li>
							<li><?php echo $this->Html->link(__('Download scores'), array('action' => 'scores', $exam['Exam']['id'], 'ext' => 'csv')); ?></li>
							<li><?php echo $this->Html->link(__('Download scores (Dutch format)'), array('action' => 'scores', $exam['Exam']['id'], 'nld', 'ext' => 'csv')); ?></li>
							<li><?php echo $this->Html->link(__('Show missings'), array('action' => 'missings', $exam['Exam']['id'])); ?></li>
							<?php
							endif;
							if (!empty($exam['Exam']['analysed'])):
							?>
							<li><?php echo $this->Html->link(__('Reanalyse'), array('action' => 'reanalyse', $exam['Exam']['id'])); ?></li>
							<?php
							endif;
							if (empty($exam['Child'])):
							?>
							<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $exam['Exam']['id']), array('confirm' => __('Are you sure you want to delete exam "%s"?', $exam['Exam']['name']))); ?></li>
							<?php
							endif;
							?>
						</ul>
					</div>
				</td>
			</tr>
			<?php
			if (!empty($exam['Child'])):
				foreach ($exam['Child'] as $child):
			?>
			<tr>
				<td> > <?php echo h($child['name']); ?>&nbsp;</td>
				<td>
					<?php
					if (in_array($child['exam_state_id'], array(ExamState::ANALYSING, ExamState::IMPORTING, ExamState::WAITING_TO_ANALYSE, ExamState::WAITING_TO_IMPORT, ExamState::WAITING_TO_GENERATE_REPORT, ExamState::GENERATING_REPORT))):
						$doAutoRefresh = true;
						echo $this->Html->link(
							'<i class="icon-time"></i>',
							'#', array(
								'escape' => false,
								'data-original-title' => $child['name'],
								'data-content' => __('The status will update automatically.'),
								'data-placement' => 'top',
								'rel' => 'popover'
							)
						);
					endif;
					echo h($child['ExamState']['name']);
					?>
				</td>
				<td><?php echo h($child['created']); ?>&nbsp;</td>
				<td><?php echo h($child['modified']); ?>&nbsp;</td>
				<td class="actions">
					<div class="btn-group">
						<?php
						if (!empty($child['analysed'])):
							echo $this->Html->link(__('Start interpretation'), array('action' => 'stevie', $child['id']), array('class' => 'btn'));
						else:
							echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $child['id']), array('class' => 'btn', 'confirm' => __('Are you sure you want to delete exam "%s"?', $child['name'])));
						endif;
						?>
						<button class="btn dropdown-toggle" data-toggle="dropdown">
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<?php
							if (!empty($child['analysed'])):
							?>
							<li><?php echo $this->Html->link(__('Start interpretation'), array('action' => 'stevie', $child['id'])); ?></li>
							<?php
							endif;
							if ($child['exam_state_id'] == ExamState::REPORT_GENERATED):
							?>
							<li><?php echo $this->Html->link(__('Download report'), array('action' => 'report', $child['id'], 'ext' => 'pdf')); ?></li>
							<li><?php echo $this->Html->link(__('Download scores'), array('action' => 'scores', $child['id'], 'ext' => 'csv')); ?></li>
							<li><?php echo $this->Html->link(__('Download scores (Dutch format)'), array('action' => 'scores', $child['id'], 'nld', 'ext' => 'csv')); ?></li>
							<li><?php echo $this->Html->link(__('Show missings'), array('action' => 'missings', $child['id'])); ?></li>
							<?php
							endif;
							?>
							<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $child['id']), array('confirm' => __('Are you sure you want to delete exam "%s"?', $child['name']))); ?></li>
						</ul>
					</div>
				</td>
			</tr>
			<?php
					endforeach;
				endif;
			endforeach;
			?>
		</tbody>
	</table>
	<?php echo $this->Paginator->pagination(); ?>
</div>
<?php
if ($doAutoRefresh):
	$script = "setTimeout(function(){ window.location.reload(1); }, 10000);";
	$script .= "$('a[rel=popover]').popover();";
	$script = '$(document).ready(function() {' . $script . '});';
	echo $this->Html->scriptBlock($script, array('inline' => false));
endif;