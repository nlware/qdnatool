<div class="exams missings">
	<h4><?php echo __('Exam: %s', h($exam['Exam']['name'])); ?></h4>
	<table class="table">
	  <thead>
    	<tr>
				<th><?php echo __('Subject'); ?></th>
				<th><?php echo __('Version'); ?></th>
				<th><?php echo __('Item'); ?></th>
	    </tr>
	  </thead>
	  <tbody>
    <?php foreach ($missings as $missing): ?>
    	<tr>
    		<td><?php echo h($missing['Subject']['value']); ?>&nbsp;</td>
    		<td><?php echo ($missing['Subject']['is_second_version']?__('2'):__('1')); ?>&nbsp;</td>
    		<td><?php echo h($missing['Subject']['is_second_version']?$missing['Item']['second_version_order']:$missing['Item']['order']); ?>&nbsp;</td>
    	</tr>
    <?php endforeach; ?>
	  </tbody>
	</table>
</div>