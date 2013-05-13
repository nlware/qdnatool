<?php
$script = "$('a[rel=popover]').popover();";
$script = '$(document).ready(function() {' . $script . '});';
echo $this->Html->scriptBlock($script, array('inline' => false))
?>
<div class="exams form">
<?php echo $this->Form->create('Exam', array('class' => 'form-horizontal', 'type' => 'file')); ?>
	<fieldset>
		<legend><?php echo __('Reanalyse exam'); ?></legend>
	<?php
		echo $this->Form->input('parent_id', array('type' => 'hidden'));
		echo $this->Form->input('name', array('label' => __('Name'), 'required' => false));
		//echo $this->Form->input('Item', array('label' => __('Exclude items'), 'multiple' => 'checkbox'));
	?>
	<table class="table">
		<thead>
			<tr>
				<td><?php echo __('Include items'); ?></td>
				<td><?php echo __('Correct answer options'); ?></td>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($items as $i => $item): ?>
			<tr>
				<td>
				<?php
					echo $this->Form->input('Item.' . $i . '.id', array('default' => true, 'value' => $item['Item']['id']));
					echo $this->Form->input('Item.' . $i . '.include', array('type' => 'checkbox', 'default' => true, 'label' => $item['Item']['order']));
				?>
				</td>
				<td>
					<div class="control-group">
					<?php
						foreach ($item['AnswerOption'] as $j => $answerOption):
							echo $this->Form->checkbox(
								'Item.' . $i . '.AnswerOption.' . $j . '.is_correct', array(
									'default' => $answerOption['is_correct'],
									'label' => $answerOption['order']
								)
							);
						endforeach;
					?>
					</div>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	</fieldset>
	<div class="form-actions">
  <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-primary', 'div' => false)); ?>
  <?php echo $this->Html->link(__('Cancel'), array('action' => 'index'), array('class' => 'btn')); ?>
  </div>
<?php echo $this->Form->end(); ?>
</div>