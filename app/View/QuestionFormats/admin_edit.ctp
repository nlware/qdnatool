<div class="questionFormats form">
  <h2><?php echo __('Question Formats'); ?></h2>
  <?php
  	echo $this->Form->create('QuestionFormat');
  	echo $this->Form->input('id');
  	echo $this->Form->input('name');
  	echo $this->Form->input('question_info_diverge_url');
  	echo $this->Form->input('question_info_converge_url');
  ?>
	<div class="form-actions">
    <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-primary', 'div' => false)); ?>
    <?php echo $this->Html->link(__('Cancel'), array('action' => 'index'), array('class' => 'btn')); ?>
  </div>
  <?php echo $this->Form->end(); ?>
</div>