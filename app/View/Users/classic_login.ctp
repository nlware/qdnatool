<?php echo $this->Form->create('User', array('class' => 'form-horizontal')); ?>
<div class="modal">
  <div class="modal-header">
    <h4><?php echo __('Sign in'); ?></h4>
  </div>
  <div class="modal-body">
    <?php
      echo $this->Form->input('username', array('label' => __('Username')));
      echo $this->Form->input('password', array('label' => __('Password'), 'value' => ''));
    ?>
  </div>
  <div class="modal-footer">
    <?php
      if(Configure::read('debug')==0):
        echo $this->Html->link(__('Sign in via SURFconext'), 'https://www.qdnatool.org/simplesamlphp/module.php/core/as_login.php?AuthId=SURFconext&ReturnTo=https%3A%2F%2Fwww.qdnatool.org%2Fusers%2Fsaml_login', array('class' => 'btn'));
      endif;
    ?>
    <?php echo $this->Form->submit(__('Sign in'), array('class' => 'btn btn-primary', 'div' => false)); ?>
  </div>
</div>
<?php
  echo $this->Form->end();