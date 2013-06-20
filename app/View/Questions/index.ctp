<?php
$script = '
$(document).ready(function() {
	$(".tab-content").each(function() {
		$(this).height($(window).height()-$(this).offset().top);
	});
	$(window).resize(function() {
		$(".tab-content").each(function() {
			$(this).height($(window).height()-$(this).offset().top);
		});
	});
	$("a[rel=tooltip]").tooltip();
});
';
echo $this->Html->scriptBlock($script, array('inline' => false));
?>
<div class="row">
	<div class="span2">
		<h4><?php echo __('Tags'); ?></h4>
		<table class="table">
			<tbody>
				<tr>
					<td>
					<?php
					echo $this->Form->create('Question');
					echo $this->Form->input('Tag', array('label' => false, 'multiple' => 'checkbox', 'escape' => false));
					echo $this->Form->end(__('Filter'));
					?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="span7">
		<?php echo $this->Html->link('<i class="icon-plus icon-white"></i> ' . __('Add Question'), array('action' => 'add'), array('class' => 'btn btn-primary pull-right', 'escape' => false)); ?>
		<div class="btn-group pull-right">
			<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><?php echo __('Export'); ?> <span class="caret"></span></a>
			<ul class="dropdown-menu">
			<?php
			$params = array();
			if (!empty($this->request->named['tag_id'])) $params['tag_id'] = $this->request->named['tag_id'];
			?>
				<li><?php echo $this->Html->link(__('HTML'), array_merge(array('action' => 'download'), $params), array('target' => '_blank')); ?></li>
				<li><?php echo $this->Html->link(__('Respondus'), array_merge(array('action' => 'export_respondus'), $params)); ?></li>
				<li><?php echo $this->Html->link(__('QMP'), array_merge(array('action' => 'export_qmp', 'ext' => 'xml'), $params)); ?></li>
			</ul>
		</div>
		<h4><?php echo __('Questions'); ?></h4>
		<table class="table">
			<tbody>
			<?php foreach ($questions as $question): ?>
				<tr>
					<td>
						<p><?php echo h($question['Question']['code']); ?></p>
						<p><?php echo h($question['Question']['name']); ?></p>
						<ul class="answer-options">
						<?php
						if (!empty($question['QuestionAnswer'])):
							foreach ($question['QuestionAnswer'] as $questionAnswer):
						?>
							<li><a rel="tooltip" href="#" data-original-title="<?php echo ($questionAnswer['is_correct']?__('Correct answer'):__('Wrong answer')); ?>"><i class="<?php echo ($questionAnswer['is_correct']?'icon-thumbs-up':'icon-thumbs-down'); ?>"></i></a>
							<?php echo h(strip_tags($questionAnswer['name'])); ?>
							</li>
						<?php
							endforeach;
						endif;
						?>
						</ul>
						<?php
						if (!empty($question['Tag'])):
							foreach ($question['Tag'] as $tag):
						?>
						<span class="label label-info" title="<?php echo h($tag['name']); ?>"><?php echo h(String::truncate($tag['name'], 20, array('ellipsis' => '...'))); ?></span>
						<?php
							endforeach;
						endif;
						?>
					</td>
					<td class="actions">
						<div class="btn-group">
						<?php echo $this->Html->link(__('View'), array('action' => 'view', $question['Question']['id']), array('class' => 'btn')); ?>
							<button class="btn dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li><?php echo $this->Html->link(__('Edit')	, array('action' => 'edit',	$question['Question']['id'])); ?></li>
								<li><?php echo $this->Form->postLink( __('Delete'), array('action' => 'delete',	$question['Question']['id']), null, __('Are you sure you want to delete # %s "%s"?', $question['Question']['id'], $question['Question']['name'])); ?></li>
							</ul>
						</div>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php echo $this->Paginator->pagination(); ?>
	</div>
	<div class="offset9 span3 affix">
		<ul class="nav nav-tabs">
			<li class="active"><?php echo $this->Html->link(__('Tip of the Day'), array('#' => 'tip-of-the-day'), array('data-toggle' => 'tab')); ?></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="tip-of-the-day">
			<?php if (!empty($tip)): ?>
				<h4><?php echo h($tip['Tip']['name']); ?></h4>
			<?php
				echo $this->Output->html($tip['Tip']['content']);
			endif;
			?>
			</div>
		</div>
	</div>
</div>