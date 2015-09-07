<?php
$itemsWithMessagesCount = 0;
if (!empty($exam['Item'])):
	foreach ($exam['Item'] as $i => $item):
		if (!empty($item['Messages'])):
			$itemsWithMessagesCount++;
		endif;
	endforeach;
endif;
?>
<div class="modal">
	<div class="modal-header">
	<?php echo $this->Form->postLink('×', array('action' => 'index'), array('class' => 'close', 'div' => false, 'confirm' => __('Are you sure you want to close this wizard?'))); ?>
		<h4><?php echo __('Interpretation of exam "%s"', h($exam['Exam']['name'])); ?></h4>
	</div>
	<div class="modal-body" style="height:400px;">
		<ul class="nav nav-tabs">
			<li<?php echo ($offset == 'overview'?' class="active"':''); ?>>
			<?php echo $this->Html->link(__('Exam'), array('action' => 'stevie', $exam['Exam']['id'])); ?>
			</li>
			<?php
			if (!empty($exam['Item'])):
				$itemsWithMessagesIndex = 0;
				foreach ($exam['Item'] as $i => $item):
					if (!empty($item['Messages'])):
						if ((is_numeric($offset) && ($i + 1) < $offset) || $offset == 'conclusion'):
							$previousOffset = $i + 1;
						endif;
						if ((is_numeric($offset) && empty($nextOffset) && ($i + 1) > $offset) || ($offset == 'overview' && empty($nextOffset))):
							$nextOffset = $i + 1;
						endif;
						if ($itemsWithMessagesCount > 6 && $itemsWithMessagesIndex == 5):
			?>
			<li class= "dropdown<?php echo (($offset > $i || $offset == 'conclusion')?' active':''); ?>">
				<a data-toggle="dropdown" class="dropdown-toggle" href="#"><?php echo __('More'); ?> <b class="caret"></b></a>
				<ul class="dropdown-menu">
				<?php endif; ?>
					<li<?php echo (($offset == ($i + 1))?' class="active"':''); ?>>
					<?php echo $this->Html->link(__('Item %s', ($i + 1)), array('action' => 'stevie', $exam['Exam']['id'], ($i + 1))); ?>
					</li>
			<?php
						$itemsWithMessagesIndex++;
					endif;
				endforeach;
			endif;
			if (empty($nextOffset)):
				$nextOffset = 'conclusion';
			endif;
			?>
					<li<?php echo (!empty($offset) && ($offset == 'conclusion')?' class="active"':''); ?>>
						<?php echo $this->Html->link(__('Conclusion'), array('action' => 'stevie', $exam['Exam']['id'], 'conclusion')); ?>
					</li>
					<?php if ($itemsWithMessagesCount > 6): ?>
				</ul>
			</li>
			<?php endif; ?>
		</ul>
		<?php if ($offset == 'conclusion'): ?>
		<h5><?php echo __('Conclusion'); ?></h5>
		<?php if ($exam['Exam']['cronbachs_alpha'] < 0.7): ?>
		<h6><?php echo __('Reliability of the exam: low'); ?></h6>
		<p><?php echo __('The reliability of the test is too low to give a decisive result: there are too many incorrect fail or pass decisions being made.'); ?></p>
		<p><?php echo __('The reliability of the test can be increased by executing an item analysis and removing or editing incorrect items from the test results.'); ?></p>
		<p><?php echo __('Press on ‘next’ to start the item analysis. Only items which can increase reliability will be shown. Reliable questions will not be shown.'); ?></p>
		<?php elseif ($exam['Exam']['cronbachs_alpha'] < 0.8): ?>
		<h6><?php echo __('Reliability of the exam: doubtful'); ?></h6>
		<p><?php echo __('The reliability of this test is debatable. An item analysis should be executed to increase the reliability of the test: there are too many incorrect fail or pass decisions being made.In case this is a mid term test, the reliability is sufficient.'); ?></p>
		<p><?php echo __('Press on ‘next’ to start the item analysis. Only items which can increase reliability will be shown. Reliable questions will not be shown.'); ?></p>
		<?php else: ?>
		<h6><?php echo __('Reliability of the exam: adequate'); ?></h6>
		<p><?php echo __('Reliability of the test is sufficient. An item analysis can increase the reliability but is not strictly necessary.'); ?></p>
		<p><?php echo __('Press on ‘next’ to start the item analysis. Only items which can increase reliability will be shown. Reliable questions will not be shown.'); ?></p>
		<?php
		endif;
		if (!empty($exam['Item'])):
			foreach ($exam['Item'] as $i => $item):
				if (!empty($item['Messages'])):
		?>
		<h6><?php echo __('Item %s', ($i + 1)); ?></h6>
		<?php foreach($item['Messages'] as $message): ?>
		<p><?php echo h($message); ?></p>
		<?php
						endforeach;
					endif;
				endforeach;
			endif;
		elseif ($offset > 0):
		?>
		<h5><?php echo __('Item %s / %s', $offset, count($exam['Item'])); ?></h5>
		<?php
		if (!empty($exam['Item'][$offset - 1]['Messages'])):
			foreach($exam['Item'][$offset - 1]['Messages'] as $message):
		?>
		<p><?php echo h($message); ?></p>
		<?php
			endforeach;
		endif;
		?>
		<dl class="dl-horizontal">
			<?php $correctAnswers = Set::extract('/AnswerOption[is_correct=1]/order', $exam['Item'][$offset - 1]); ?>
			<dt><?php echo __n('Correct answer', 'Correct answers', count($correctAnswers)); ?></dt>
			<dd><?php echo $this->Text->toList($correctAnswers); ?>&nbsp;</dd>
			<dt><?php echo __('Rit correct answer'); ?></dt>
			<dd><?php echo $this->Output->decimal($exam['Item'][$offset - 1]['correct_answer_irc'], 3); ?></dd>
			<dt><?php echo __('Correct answer'); ?></dt>
			<dd><?php echo __('%s %%', $this->Output->decimal($exam['Item'][$offset - 1]['correct_answer_percentage'])); ?></dd>
			<?php
			foreach ($exam['Item'][$offset - 1]['AnswerOption'] as $i => $answerOption):
				if ($answerOption['is_correct']):
					continue;
				endif;
			?>
			<dt><?php echo __('Incorrect answer %s', AnswerOption::printIndex($i)); ?></dt>
			<dd><?php echo __('%s %%', $this->Output->decimal($answerOption['given_answer_percentage'])); ?>&nbsp;</dd>
			<?php endforeach; ?>
		</dl>
		<?php
		else:
			if ($exam['Exam']['cronbachs_alpha'] < 0.7):
		?>
		<h5><?php echo __('Reliability of the exam: low'); ?></h5>
		<p><?php echo __('The reliability of the test is too low to give a decisive result: there are too many incorrect fail or pass decisions being made.'); ?></p>
		<p><?php echo __('The reliability of the test can be increased by executing an item analysis and removing or editing incorrect items from the test results.'); ?></p>
		<p><?php echo __('Press on ‘next’ to start the item analysis. Only items which can increase reliability will be shown. Reliable questions will not be shown.'); ?></p>
		<?php elseif ($exam['Exam']['cronbachs_alpha'] < 0.8): ?>
		<h5><?php echo __('Reliability of the exam: doubtful'); ?></h5>
		<p><?php echo __('The reliability of this test is debatable. An item analysis should be executed to increase the reliability of the test: there are too many incorrect fail or pass decisions being made.In case this is a mid term test, the reliability is sufficient.'); ?></p>
		<p><?php echo __('Press on ‘next’ to start the item analysis. Only items which can increase reliability will be shown. Reliable questions will not be shown.'); ?></p>
		<?php else: ?>
		<h5><?php echo __('Reliability of the exam: adequate'); ?></h5>
		<p><?php echo __('Reliability of the test is sufficient. An item analysis can increase the reliability but is not strictly necessary.'); ?></p>
		<p><?php echo __('Press on ‘next’ to start the item analysis. Only items which can increase reliability will be shown. Reliable questions will not be shown.'); ?></p>
		<?php
			endif;
		endif;
		?>
	</div>
	<div class="modal-footer">
		<?php
		if (!empty($previousOffset)):
			echo $this->Html->link(__('< Previous'), array('action' => 'stevie', $exam['Exam']['id'], $previousOffset), array('class' => 'btn'));
		else:
			echo $this->Html->link(__('< Previous'), '#', array('class' => 'btn disabled'));
		endif;
		if ($offset == 'conclusion'):
			echo $this->Html->link(__('Finish'), array('action' => 'index'), array('class' => 'btn btn-primary'));
		else:
			echo $this->Html->link(__('Next >'), array('action' => 'stevie', $exam['Exam']['id'], $nextOffset), array('class' => 'btn btn-primary'));
		endif;
		?>
	</div>
</div>