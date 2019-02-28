<?php
$this->addScript('<style type="text/css">body {font-family: \'Arial\',sans-serif;font-size: 14px; } img {margin-bottom: 1em;} ol ol {clear:left; list-style-type: upper-alpha;}</style>');
?>
<ol>
	<?php foreach ($questions as $i => $question): ?>
	<li>
		<p><?php echo $this->HtmLawed->display($question['Question']['stimulus']); ?></p>
		<?php
		switch ($question['Question']['question_format_id']):
			case QuestionFormat::TRUE_FALSE:
		?>
		<ol>
			<?php
			if (!empty($question['QuestionAnswer'])):
				foreach ($question['QuestionAnswer'] as $questionAnswer):
				?>
				<li><?php echo $this->HtmLawed->display($questionAnswer['name']); ?></li>
				<?php
				endforeach;
			endif;
			?>
		</ol>
		<?php
				break;
			case QuestionFormat::MULTIPLE_CHOICE:
			case QuestionFormat::MULTIPLE_RESPONSE:
		?>
		<ol>
			<?php
			if (!empty($question['QuestionAnswer'])):
				foreach ($question['QuestionAnswer'] as $questionAnswer):
				?>
				<li><?php echo $this->HtmLawed->display($questionAnswer['name']); ?></li>
				<?php
				endforeach;
			endif;
			?>
		</ol>
		<?php
				break;
			case QuestionFormat::OPEN_ANSWER:
		?>
		<p><?php echo $this->HtmLawed->display($question['Question']['answer']); ?></p>
		<?php
			break;
		endswitch;
		?>
	</li>
	<?php endforeach; ?>
</ol>
<p><strong><?php echo __('Correct answers'); ?></strong></p>
<ol>
	<?php foreach ($questions as $question): ?>
	<li>
		<?php
		$correctAnswers = array();
		if (!empty($question['QuestionAnswer'])):
			foreach ($question['QuestionAnswer'] as $i => $questionAnswer):
				if ($questionAnswer['is_correct']):
					$correctAnswers[] = $this->Output->optionIndex($i);
				endif;
			endforeach;
		endif;
		if ($question['Question']['question_format_id'] == QuestionFormat::OPEN_ANSWER):
			$correctAnswers[] = $this->HtmLawed->display($question['Question']['answer']);
		endif;
		echo implode(', ', $correctAnswers);
		?>
	</li>
	<?php endforeach; ?>
</ol>
