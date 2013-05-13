<?php
  $this->addScript('<style type="text/css">body {font-family: \'Arial\',sans-serif;font-size: 14px; } img {margin-bottom: 1em;} ol ol {clear:left; list-style-type: upper-alpha;}</style>');
?>
<ol>
<?php foreach ($questions as $i => $question): ?>
<li>
<p><?php echo $this->HtmLawed->display($question['Question']['stimulus']); ?></p>
<?php
  switch($question['Question']['question_format_id']):
    case QuestionFormat::TRUE_FALSE:
?>
<ol>
  <?php
    if(!empty($question['QuestionAnswer'])):
      foreach($question['QuestionAnswer'] as $questionAnswer):
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
    if(!empty($question['QuestionAnswer'])):
      foreach($question['QuestionAnswer'] as $questionAnswer):
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
<?php //if($showCorrectAnswerAtTheEnding): ?>
<p><strong><?php echo __('Correct answers'); ?></strong></p>
  <ol>
    <?php foreach ($questions as $question): ?>
    <li>
    <?php
      //$correctAnswers = Set::extract('/QuestionAnswer[is_correct=1]/@', $question);
      $correctAnswers = array();
      if(!empty($question['QuestionAnswer'])):
        foreach($question['QuestionAnswer'] as $i => $questionAnswer):
          if($questionAnswer['is_correct']):
            $correctAnswers[] = QuestionAnswer::printIndex($i);
          endif;
        endforeach;
      endif;
      if($question['Question']['question_format_id'] == QuestionFormat::OPEN_ANSWER):
        $correctAnswers[] = $this->HtmLawed->display($question['Question']['answer']);
      endif;
      /*
      $correctAnswers = array();
      switch($question['Question']['question_format_id']):
        case QuestionFormat::TRUE_FALSE:
        
          if($question['Question']['a_correct']) $correctAnswers[] = __('a');
          if($question['Question']['b_correct']) $correctAnswers[] = __('b');
          break;
        case QuestionFormat::MULTIPLE_CHOICE:
        case QuestionFormat::MULTIPLE_RESPONSE:
          if($question['Question']['a_correct']) $correctAnswers[] = __('a');
          if($question['Question']['b_correct']) $correctAnswers[] = __('b');
          if($question['Question']['c_correct']) $correctAnswers[] = __('c');
          if($question['Question']['d_correct']) $correctAnswers[] = __('d');
          if($question['Question']['e_correct']) $correctAnswers[] = __('e');
          break;
        case QuestionFormat::OPEN_ANSWER:
          $correctAnswers[] = $this->HtmLawed->display($question['Question']['answer']);
          break;
      endswitch;
      */
      echo implode(', ', $correctAnswers);
    ?>
    </li>
    <?php endforeach; ?>
  </ol>
<?php //endif; ?>