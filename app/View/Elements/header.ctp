<?php
$designControllers = array('questions', 'question_formats', 'tags');
$analysisControllers = array('exams');
?>
<div id="modalAbout" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3><?php echo __('About qDNAtool'); ?></h3>
	</div>
	<div class="modal-body">
		<p><?php echo __('This project was made possible by the support of SURF, the higher education and research partnership organisation for Information and Communications Technology (ICT). For more information about SURF, please visit %s.', $this->Html->link('www.surf.nl', 'http://www.surf.nl', array('target' => '_blank'))); ?></p>
		<p>
		<?php
		echo $this->Html->link($this->Html->image('logo-vu.png', array('onmouseout' => 'this.src=\'' . $this->Html->url('/img/logo-vu.png') . '\';', 'onmouseover' => 'this.src=\'' . $this->Html->url('/img/logo-vu-cl.png') . '\';')), 'http://www.vu.nl', array('target' => '_blank', 'escape' => false));
		echo $this->Html->link($this->Html->image('logo-uva.png'), 'http://www.uva.nl', array('target' => '_blank', 'escape' => false));
		echo $this->Html->link($this->Html->image('logo-nlware.png', array('onmouseout' => 'this.src=\'' . $this->Html->url('/img/logo-nlware.png') . '\';', 'onmouseover' => 'this.src=\'' . $this->Html->url('/img/logo-nlware-cl.png') . '\';')), 'http://www.nlware.com', array('target' => '_blank', 'escape' => false));
		echo $this->Html->link($this->Html->image('logo-surf.png'), 'http://www.surf.nl', array('target' => '_blank', 'escape' => false));
		?>
		</p>
		<p>
		<?php
		echo __('CakePHP version %s', Configure::version());
		echo $this->Html->link(__('GitHub'), 'https://github.com/nlware/qdnatool', array('target' => '_blank'));
		?>
		</p>
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><?php echo __('Close'); ?></button>
	</div>
</div>
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<?php
			if (AuthComponent::user('id')):
			?>
			<ul class="nav pull-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo h(AuthComponent::user('username')); ?><b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><?php echo $this->Html->link(__('Settings'), array('admin' => false, 'controller' => 'users', 'action' => 'account')); ?></li>
						<li><?php echo $this->Html->link('<i class="icon-off"></i> ' . h(__('Sign Out')), array('admin' => false, 'controller' => 'users', 'action' => 'logout'), array('escape' => false)); ?></li>
					</ul>
				</li>
			</ul>
			<?php
			else:
				if (Configure::read('debug') > 0):
					echo $this->Html->link(__('Login'), array('admin' => false, 'controller' => 'users', 'action' => 'classic_login'), array('class' => 'btn btn-primary pull-right'));
				else:
					echo $this->Html->link(__('Login'), 'https://www.qdnatool.org/simplesamlphp/module.php/core/as_login.php?AuthId=SURFconext&ReturnTo=https%3A%2F%2Fwww.qdnatool.org%2Fusers%2Fsaml_login', array('class' => 'btn btn-primary pull-right'));
				endif;
			endif;
			echo $this->Html->link(__('qDNAtool'), '/', array('class' => 'brand'));
			?>
			<ul class="nav">
				<?php
				if (AuthComponent::user('id')):
					if (in_array($this->request->controller, $designControllers)):
				?>
				<li class="dropdown active">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo __('Design'); ?> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li class="active"><?php echo $this->Html->link(__('Design'), array('admin' => false, 'controller' => 'questions', 'action' => 'index')); ?></li>
						<li><?php echo $this->Html->link(__('Analyse'), array('admin' => false, 'controller' => 'exams', 'action' => 'index')); ?></li>
					</ul>
				</li>
				<?php elseif (in_array($this->request->controller, $analysisControllers)): ?>
				<li class="dropdown active">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo __('Analyse'); ?> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li class="active"><?php echo $this->Html->link(__('Analyse'), array('admin' => false, 'controller' => 'exams', 'action' => 'index')); ?></li>
						<li><?php echo $this->Html->link(__('Design'), array('admin' => false, 'controller' => 'questions', 'action' => 'index')); ?></li>
					</ul>
				</li>
				<?php
					endif;
				endif;
				?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo __('Help'); ?> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><?php echo $this->Html->link(__('qDNAtool Help'), 'http://docs.qdnatool.org/ontwerpen/about/', array('target' => '_blank')); ?></li>
						<li><?php echo $this->Html->link(__('About qDNAtool'), array('#' => 'modalAbout'), array('data-toggle' => 'modal')); ?></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>