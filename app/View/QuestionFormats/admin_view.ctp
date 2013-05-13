<h2><?php  echo __('Question Format');?></h2>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $questionFormat['QuestionFormat']['id'])); ?> </li>
	</ul>
</div>
<div class="questionFormats view">
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd><?php echo h($questionFormat['QuestionFormat']['id']); ?>&nbsp;</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd><?php echo h($questionFormat['QuestionFormat']['name']); ?>&nbsp;</dd>
		<dt><?php echo __('Question Info Diverge Url'); ?></dt>
		<dd><?php echo h($questionFormat['QuestionFormat']['question_info_diverge_url']); ?>&nbsp;</dd>
		<dt><?php echo __('Question Info Converge Url'); ?></dt>
		<dd><?php echo h($questionFormat['QuestionFormat']['question_info_converge_url']); ?>&nbsp;</dd>
	</dl>
</div>