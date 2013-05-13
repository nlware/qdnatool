<div class="row">
  <div class="span6">
    <div class="hero-unit" style="height: 550px">
      <h1><?php echo __('Design'); ?></h1>
      <p><?php echo __('Through this part of the qDNAtool gives you access to the powerful demand development. The qDNA tool allows you to create true-false questions (two-choice questions), multiple-choice questions, multiple-response questions and open questions. The qDNA tool provides help to question ideas and to get questions as clearly and precisely as possible. You can ask brands, select and export based on tags. Everything for optimal test to compile.'); ?></p>
      <p><?php echo $this->Html->link(__('Design'), array('controller' => 'questions'), array('class' => 'btn btn-primary btn-large')); ?></p>
    </div>
  </div>
  <div class="span6">
    <div class="hero-unit" style="height: 550px">
      <h1><?php echo __('Analyse'); ?></h1>
      <p><?php echo __('The QDNA analyse tool allows various “raw” test results to be uploaded, for example those provided by Teleform, Blackboard or Questionmark Perception. This tool analyses the reliability of the whole test and of individual questions. An interpretation wizard will guide novice users through this process and the results are also offered in PDF format.'); ?></p>
      <p><?php echo $this->Html->link(__('Analyse'), array('controller' => 'exams'), array('class' => 'btn btn-primary btn-large')); ?></p>
    </div>
  </div>
</div>