<div class="exams view">
<?php echo $this->Html->link(__('List Exams'), array('action' => 'index'), array('class' => 'btn btn-primary pull-right')); ?>
	<h4><?php  echo __('Exam'); ?></h4>
	<dl class="dl-horizontal">
		<dt><?php echo __('Id'); ?></dt>
		<dd><?php echo h($exam['Exam']['id']); ?>&nbsp;</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd><?php echo h($exam['Exam']['name']); ?>&nbsp;</dd>
		<dt><?php echo __('Average Score'); ?></dt>
		<dd><?php echo h($exam['Exam']['average_score']); ?>&nbsp;</dd>
		<dt><?php echo __('Standard Deviation'); ?></dt>
		<dd><?php echo h($exam['Exam']['standard_deviation']); ?>&nbsp;</dd>
		<dt><?php echo __('Cronbachs Alpha'); ?></dt>
		<dd><?php echo h($exam['Exam']['cronbachs_alpha']); ?>&nbsp;</dd>
		<dt><?php echo __('Default Answer Option Count'); ?></dt>
		<dd><?php echo h($exam['Exam']['answer_option_count']); ?>&nbsp;</dd>
		<dt><?php echo __('Maximum Answer Option Count'); ?></dt>
		<dd><?php echo h($exam['Exam']['max_answer_option_count']); ?>&nbsp;</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd><?php echo h($exam['User']['name']); ?>&nbsp;</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd><?php echo h($exam['Exam']['created']); ?>&nbsp;</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd><?php echo h($exam['Exam']['modified']); ?>&nbsp;</dd>
		<dt><?php echo __('Uploaded'); ?></dt>
		<dd><?php echo h($exam['Exam']['uploaded']); ?>&nbsp;</dd>
		<dt><?php echo __('Imported'); ?></dt>
		<dd><?php echo h($exam['Exam']['imported']); ?>&nbsp;</dd>
		<dt><?php echo __('Analysed'); ?></dt>
		<dd><?php echo h($exam['Exam']['analysed']); ?>&nbsp;</dd>
		<dt><?php echo __('Report Generated'); ?></dt>
		<dd><?php echo h($exam['Exam']['report_generated']); ?>&nbsp;</dd>
	</dl>
	<table class="table">
		<thead>
			<tr>
				<th><?php echo __('Item'); ?></th>
				<th><?php echo __('# Correct Answers'); ?></th>
				<th><?php echo __('% Correct Answers'); ?></th>
				<th><?php echo __('Rit Correct Answers'); ?></th>
				<th><?php echo __('# Missing Answers'); ?></th>
				<th><?php echo __('% Missing Answers'); ?></th>
				<?php for ($i = 0; !empty($exam['Exam']['max_answer_option_count']) && $i < $exam['Exam']['max_answer_option_count']; $i++): ?>
				<th><?php echo __('# Answer %s', AnswerOption::printIndex($i)); ?></th>
				<th><?php echo __('%% Answer %s', AnswerOption::printIndex($i)); ?></th>
				<th><?php echo __('Rit Answer %s', AnswerOption::printIndex($i)); ?></th>
				<?php endfor; ?>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($exam['Item'] as $item): ?>
			<tr>
				<td><?php echo h($item['value']); ?>&nbsp;</td>
				<td><?php echo h($item['correct_answer_count']); ?>&nbsp;</td>
				<td><?php echo $this->Output->decimal($item['correct_answer_percentage']); ?>&nbsp;</td>
				<td><?php echo $this->Output->decimal($item['correct_answer_irc'], 3); ?>&nbsp;</td>
				<td><?php echo h($item['missing_answer_count']); ?>&nbsp;</td>
				<td><?php echo $this->Output->decimal($item['missing_answer_percentage']); ?>&nbsp;</td>
			<?php for ($i = 0; !empty($exam['Exam']['max_answer_option_count']) && $i < $exam['Exam']['max_answer_option_count']; $i++): ?>
				<td><?php echo isset($item['AnswerOption'][$i]['given_answer_count'])?h($item['AnswerOption'][$i]['given_answer_count']):'-'; ?>&nbsp;</td>
				<td><?php echo isset($item['AnswerOption'][$i]['given_answer_percentage'])?$this->Output->decimal($item['AnswerOption'][$i]['given_answer_percentage']):'-'; ?>&nbsp;</td>
				<td><?php echo isset($item['AnswerOption'][$i]['given_answer_irc'])?$this->Output->decimal($item['AnswerOption'][$i]['given_answer_irc'], 3):'-'; ?>&nbsp;</td>
			<?php endfor; ?>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>