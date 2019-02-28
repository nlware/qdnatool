<div class="hero-unit">
	<h1><?php echo __('Welcome at the qDNA tool'); ?></h1>
	<p><?php echo __('A site to support you to think up test questions and analyze tests and exams.'); ?></p>
</div>
<div class="row">
	<div class="span6">
		<h2><?php echo __('What does the program do?'); ?></h2>
		<h3><?php echo __('Design'); ?></h3>
		<ul>
			<li><?php echo __('Getting ideas to conceive ideas for test questions'); ?></li>
			<li><?php echo __('Supporting the constructing of clear, unambiguous and non-leading questions'); ?></li>
			<li><?php echo __('Examples of test questions, do’s and dont’s'); ?></li>
		</ul>
		<h3><?php echo __('Analyze'); ?></h3>
		<ul>
			<li><?php echo __('Performing a psychometric analyses of tests delivered via Blackboard or Questionmark Perception'); ?></li>
			<li><?php echo __('Getting support to interpret these psychometric results'); ?></li>
		</ul>
	</div>
	<div class="span6">
		<h2><?php echo __('More information'); ?></h2>
		<p>
			<?php
			echo __(
				'Use the support site %s for tips, tricks, examples and more',
				$this->Html->link('docs.qdnatool.org', 'https://docs.qdnatool.org/', array('target' => '_blank'))
			);
			?>
		</p>
		<p>
			<?php
			echo __('Log-in automatically via SURFconext if your institution has licensed the qDNA tool.');
			?>
		</p>
		<p>
			<?php
			echo __(
				'Read more information about access via SURFconext: %s',
				$this->Html->link('www.surfconext.nl', 'https://www.surfconext.nl/', array('target' => '_blank'))
			);
			?>
		</p>
	</div>
</div>
