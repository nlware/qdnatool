<div class="row">
	<div class="span9">
		<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $question['Question']['id']), array('class' => 'btn btn-primary pull-right')); ?>
		<?php echo $this->Html->link('<i class="icon-chevron-left"></i>', $referer, array('class' => 'btn pull-right', 'escapeTitle' => false, 'title' => __('Go back one page'))); ?>
		<h4><?php echo h($question['Question']['code']); ?></h4>
		<div class="row">
			<div class="span6">
				<dl>
					<dt><?php echo __('Question Format'); ?></dt>
					<dd><?php echo h($question['QuestionFormat']['name']); ?>&nbsp;</dd>
					<dt><?php echo __('Development Phase'); ?></dt>
					<dd><?php echo h($question['DevelopmentPhase']['name']); ?>&nbsp;</dd>
				</dl>
				<div class="well well-large">
					<?php
					echo $this->HtmLawed->display($question['Question']['stimulus']);
					if (!empty($question['QuestionAnswer'])):
					?>
					<ol type="A">
						<?php foreach($question['QuestionAnswer'] as $i => $questionAnswer): ?>
						<li><?php echo $this->HtmLawed->display($questionAnswer['name']); ?>&nbsp;</li>
						<?php endforeach; ?>
					</ol>
					<?php
					endif;
					?>
				</div>
				<dl>
					<dt><?php echo __('Correct Answer(s)'); ?></dt>
					<dd>
						<?php
						if (!empty($question['QuestionAnswer'])):
							$correctAnswers = array();
							foreach ($question['QuestionAnswer'] as $i => $questionAnswer):
								if ($questionAnswer['is_correct']):
									$correctAnswers[] = $this->Output->optionIndex($i);
								endif;
							endforeach;
							echo $this->Text->toList($correctAnswers, __('and'));
						elseif ($question['Question']['answer']):
							echo $this->HtmLawed->display($question['Question']['answer']);
						endif;
						?>
					</dd>
					<?php
					if (!empty($question['Question']['feedback_when_correct'])):
					?>
					<dt><?php echo __('Feedback when correct answer'); ?></dt>
					<dd><?php echo $this->HtmLawed->display($question['Question']['feedback_when_correct']); ?>&nbsp;</dd>
					<?php
					endif;
					if (!empty($question['Question']['feedback_when_wrong'])):
					?>
					<dt><?php echo __('Feedback when wrong answer'); ?></dt>
					<dd><?php echo $this->HtmLawed->display($question['Question']['feedback_when_wrong']); ?>&nbsp;</dd>
					<?php
					endif;
					if ($question['Question']['question_format_id'] == QuestionFormat::OPEN_ANSWER):
					?>
					<dt><?php echo __('Answer'); ?></dt>
					<dd><?php echo $this->HtmLawed->display($question['Question']['answer']); ?>&nbsp;</dd>
					<?php
					else:
						if (!empty($question['QuestionAnswer'])):
							foreach ($question['QuestionAnswer'] as $i => $questionAnswer):
								if (!empty($questionAnswer['feedback'])):
					?>
					<dt><?php echo __('Feedback Option %s', $this->Output->optionIndex($i)); ?></dt>
					<dd><?php echo $this->HtmLawed->display($questionAnswer['feedback']); ?>&nbsp;</dd>
    	  			<?php
								endif;
							endforeach;
						endif;
					endif;
					?>
				</dl>
			</div>
			<div class="span3">
				<h4><?php echo __('Tags'); ?></h4>
				<?php
				if (!empty($question['QuestionsTag'])):
					foreach ($question['QuestionsTag'] as $questionsTag):
				?>
				<span class="label label-info" title="<?php echo h($questionsTag['Tag']['name']); ?>"><?php echo h(CakeText::truncate($questionsTag['Tag']['name'], 20, array('ellipsis' => '...'))); ?></span>
				<?php
					endforeach;
				endif;
				if (!empty($question['Question']['comment'])):
				?>
				<h4><?php echo __('Comments'); ?></h4>
				<p><?php echo h($question['Question']['comment']); ?>&nbsp;</p>
				<?php
				endif;
				?>
				<dl>
					<dt><?php echo __('Created at'); ?></dt>
					<dd><?php echo h($question['Question']['created']); ?>&nbsp;</dd>
					<dt><?php echo __('Updated at'); ?></dt>
					<dd><?php echo h($question['Question']['updated']); ?>&nbsp;</dd>
				</dl>
			</div>
		</div>
	</div>
	<div class="offset9 span3 affix">
		<ul class="nav nav-tabs">
			<li class="active"><?php echo $this->Html->link(__('Analyses'), array($this->Form->value('Question.id'), '#' => 'analyses'), array('data-toggle' => 'tab'));	?></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="analyses">
			<?php echo $this->element('analyses', array('analyses' => $analyses)); ?>
			</div>
		</div>
	</div>
</div>