<?php
$script = "$('a[rel=popover]').popover();";
$script = '$(document).ready(function() {' . $script . '});';
echo $this->Html->scriptBlock($script, array('inline' => false))
?>
<div class="exams form">
<?php echo $this->Form->create('Exam', array('class' => 'form-horizontal', 'type' => 'file')); ?>
	<fieldset>
		<legend><?php echo __('Add exam'); ?></legend>
	<?php
	echo $this->Form->input('name', array('label' => __('Exam name'), 'required' => false));
	echo $this->Form->input(
		'exam_format_id', array(
			'label' => __('Format'),
			'empty' => true,
			'after' => sprintf('<a href="#" rel="popover" data-original-title="%s" data-content="%s"><i class="icon-question-sign"></i></a>', __('Format.'), __('Format')),
			'onchange' => 'if($(this).val()==' . (ExamFormat::TELEFORM) . ') { $("#ExamMappingFile").parent().parent().show();} else {$("#ExamMappingFile").parent().parent().hide();}'
		)
	);
	echo $this->Form->input(
		'answer_option_count', array(
			'label' => __('# Answer options'),
			'after' => sprintf('<a href="#" rel="popover" data-original-title="%s" data-content="%s"><i class="icon-question-sign"></i></a>', __('# Answer options'), __('How many answers are available for the multiple choice questions? The total amount of answers should include the correct answer.')),
			'required' => false
		)
	);
	echo $this->Form->input(
		'data_file', array(
			'type' => 'file',
			'label' => __('Data file'),
			'after' => sprintf('<a href="#" rel="popover" data-original-title="%s" data-content="%s"><i class="icon-question-sign"></i></a>', __('Data file.'), __('This file contains test result data from Blackboard, Teleform or Questionark Perception.'))
		)
	);
	$options = array(
		'type' => 'file',
		'label' => __('Mapping file'),
		'after' => sprintf('<a href="#" rel="popover" data-original-title="%s" data-content="%s"><i class="icon-question-sign"></i></a>', __('Mapping file (Teleform only).'), __('The mapping file contains data in case there are two (or more) versions of an exam being used. It ‘maps’ one version of the exam to the other.'))
	);
	if ($this->Form->value('Exam.exam_format_id') != ExamFormat::TELEFORM):
		$options['div'] = array('class' => 'control-group', 'style' => 'display:none;');
	endif;
	echo $this->Form->input('mapping_file', $options);
	?>
	</fieldset>
	<div class="form-actions">
	<?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-primary', 'div' => false)); ?>
	<?php echo $this->Html->link(__('Cancel'), array('action' => 'index'), array('class' => 'btn')); ?>
	</div>
<?php echo $this->Form->end(); ?>
</div>