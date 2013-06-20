<?php
$script = '
$(document).ready(function() {
  $(".tab-content").each(function()
  { $(this).height($(window).height()-$(this).offset().top);
  });

  $(window).resize(function(){
    $(".tab-content").each(function()
    { $(this).height($(window).height()-$(this).offset().top);
    });
  });
});
';
echo $this->Html->scriptBlock($script, array('inline' => false));
?>
<div class="row">
	<div class="span9">
	<?php
	echo $this->Form->create('Question');
	$script =
		' function analyse()
      { $.ajax
        ({  async:false,
          data:$("#QuestionAddForm").find("input, select[class~=analyse], textarea[class~=analyse]").serialize(),
    			dataType:"html",
          success:function (data, textStatus)
          { $("#analyses").html(data);
            $(".nav-tabs a[href=\'#analyses\']").tab("show");
          },
          type:"post",
          url:"'.addcslashes($this->Html->url(array('action' => 'analyse', $this->Form->value('Question.id'))), '/').'"
        });
      }

      function showHelp()
      { $.ajax
        ({  async:false,
          data:$("#QuestionAddForm").find("select[class~=show_help]").serialize(),
          dataType:"html",
          success:function (data, textStatus)
          { $("#tabHelp").html(data);
            $(".nav-tabs a[href=\'#help\']").tab("show");
          },
          type:"post",
          url:"'.addcslashes($this->Html->url(array('action' => 'instruction', $this->Form->value('Question.id'))), '/').'"
        });
      }
      $(document).ready(function() {

      $("#QuestionStartSentencesId").change(function()
      {
        CKEDITOR.instances.QuestionStimulus.setData("<p>" + $(this).val() + "</p>" + CKEDITOR.instances.QuestionStimulus.getData());
      });

      $(".typeahead").typeahead({
        source: function (query, process) {
          $.get(\''.$this->Html->url(array('controller' => 'tags', 'action' => 'autocomplete', 'ext' => 'json')).'\', {
            query: query }, function (data) {
              process(data)
          })
        }
      });

      $("#QuestionQuestionFormatId").change
      ( function()
        {
    			if($(this).val() == '.QuestionFormat::OPEN_ANSWER.')
          {
            $(\'#btnAddAnswer\').hide();
            $(\'#QuestionAnswer\').parent().parent().show();

            fieldsets = $(\'fieldset\');
            fieldsets.hide();
            fieldsets.find(\'input[type=hidden]:first\').val(\'1\');
          }
          else if($(this).val() == '.QuestionFormat::TRUE_FALSE.')
          {
            $(\'#btnAddAnswer\').hide();
            $(\'#QuestionAnswer\').parent().parent().hide();

            fieldsets = $(\'fieldset:gt(1)\');
            fieldsets.hide();
            fieldsets.find(\'input[type=hidden]:first\').val(\'1\');

            fieldsets = $(\'fieldset:lt(2)\');
            fieldsets.show();
            fieldsets.find(\'input[type=hidden]:first\').val(\'0\');
          }
          else if(($(this).val() == '.QuestionFormat::MULTIPLE_CHOICE.') ||
          	     ($(this).val() == '.QuestionFormat::MULTIPLE_RESPONSE.'))
          {
            $(\'#btnAddAnswer\').show();
            $(\'#QuestionAnswer\').parent().parent().hide();

            fieldsets = $(\'fieldset:gt(2)\');
            fieldsets.hide();
            fieldsets.find(\'input[type=hidden]:first\').val(\'1\');

            fieldsets = $(\'fieldset:lt(3)\');
            fieldsets.show();
            fieldsets.find(\'input[type=hidden]:first\').val(\'0\');
          }
          else
          {
            $(\'#btnAddAnswer\').hide();
            $(\'#QuestionAnswer\').parent().parent().hide();

            fieldsets = $(\'fieldset\');
            fieldsets.hide();
            fieldsets.find(\'input[type=hidden]:first\').val(\'1\');
          }
        }
      );

      $("select[class~=show_help]").change
      (  function(){showHelp();}
      );

      $("select[class~=analyse]").change
      (  function(){analyse();}
      );

      $("input[class~=analyse], , textarea[class~=analyse]").blur
      (  function(){analyse();}
      );
      });
    ';
	echo $this->Html->scriptBlock($script, array('inline' => false));
	echo $this->Form->input('id');
	echo $this->Form->submit(__('Save'), array('class' => 'btn btn-primary pull-right', 'div' => false));
	?>
	<?php echo $this->Html->link('<i class="icon-chevron-left"></i>', $referer, array('class' => 'btn pull-right', 'escape' => false, 'title' => __('Go back one page'))); ?>
		<h4><?php echo __('Add Question'); ?></h4>
		<div class="row">
			<div class="span6">
				<div class="row">
					<div class="span3">
					<?php echo $this->Form->input('question_format_id', array('empty' => true, 'class' => 'span3 analyse show_help')); ?>
					</div>
					<div class="span3">
					<?php echo $this->Form->input('development_phase_id', array('class' => 'span3 analyse show_help', 'default' => DevelopmentPhase::DIVERGE)); ?>
					</div>
				</div>
			<?php
			echo $this->Form->input('code', array('class' => 'span3'));
			echo $this->Form->input(
				'start_sentences_id', array(
					'label' => __('Start sentence'),
					'empty' => true,
					'class' => 'span6',
					'onclick' => ''
				)
			);
			echo $this->CkSource->ckeditor('stimulus', array('class' => 'analyse', 'id' => 'QuestionStimulus', 'events' => array('blur' => 'function(){this.updateElement();analyse();}')));

			$options = array();
			echo $this->CkSource->ckeditor(
				'answer', array(
					'label' => __('Answer'),
					'class' => 'analyse',
					'id' => 'QuestionAnswer',
					'events' => array(
						'blur' => 'function(){this.updateElement();analyse();}'
					),
					'div' => array(
						'style' => (($this->Form->value('Question.question_format_id')==QuestionFormat::OPEN_ANSWER)?'':'display:none;')
					)
				)
			);

			switch ($this->Form->value('Question.question_format_id')):
				case QuestionFormat::TRUE_FALSE:
					$minAnswerOptionCount = 2;
					$maxAnswerOptionCount = 2;
					break;
				case QuestionFormat::MULTIPLE_CHOICE:
				case QuestionFormat::MULTIPLE_RESPONSE:
					$minAnswerOptionCount = 3;
					$maxAnswerOptionCount = 8;
					break;
				case QuestionFormat::OPEN_ANSWER:
				default:
					$minAnswerOptionCount = 0;
					$maxAnswerOptionCount = 0;
					break;
			endswitch;
			for ($i = 0; $i < 8; $i++):
			?>
				<fieldset<?php echo (($i < $minAnswerOptionCount) || ($i < $maxAnswerOptionCount && $this->Form->value('QuestionAnswer.'.$i.'.destroy')=='0'))?'':' style="display:none;"'; ?>>
					<legend>
					<?php
					if ($i > 2):
						echo $this->Html->link(
							'<i class="icon-trash"></i>',
							'#', array(
								'onclick' => '
									if(confirm(\''.__('Are you sure you wish to delete this answer?').'\'))
									{ $(\'#QuestionAnswer'.$i.'Destroy\').val(1);
										$(this).parent().parent().hide();
									}
									event.returnValue = false;
									return false;
								',
								'escape' => false
							)
						);
					endif;
					echo __('Answer %s', QuestionAnswer::printIndex($i));
					?>
					</legend>
				<?php
				echo $this->Form->input('QuestionAnswer.'.$i.'.destroy', array('type' => 'hidden', 'default' => 1));
				$this->Form->unlockField('QuestionAnswer.'.$i.'.destroy');
				echo $this->Form->input('QuestionAnswer.'.$i.'.id', array('type' => 'hidden'));

				echo $this->Form->input('QuestionAnswer.'.$i.'.is_correct', array('label' => __('Correct')));
				echo $this->CkSource->ckeditor('QuestionAnswer.'.$i.'.name', array('label' => __('Answer'), 'class' => 'analyse', 'id' => 'QuestionAnswer'.$i.'Name', 'events' => array('blur' => 'function(){this.updateElement();analyse();}')));
				echo $this->CkSource->ckeditor('QuestionAnswer.'.$i.'.feedback', array('label' => __('Feedback'), 'id' => 'QuestionAnswer'.$i.'Feedback', 'events' => array('blur' => 'function(){this.updateElement();analyse();}')));
				?>
				</fieldset>
			<?php
			endfor;
			echo $this->Html->link(
				'<i class="icon-plus icon-white"></i> '.__('Add Answer'),
				'#', array(
					'class' => 'btn',
					'onclick' => '
						firstHiddenFieldset = $(\'fieldset:hidden:first\');
						if(firstHiddenFieldset.length > 0) {
							firstHiddenFieldset.find(\'input[type=hidden]:first\').val(\'0\');
							firstHiddenFieldset.show();
							if($(\'fieldset:hidden:first\').length==0) $(this).hide();
						} else {
							$(this).hide();
						}
						return false;',
					'style' => (($this->Form->value('Question.question_format_id') == QuestionFormat::MULTIPLE_CHOICE || $this->Form->value('Question.question_format_id') == QuestionFormat::MULTIPLE_RESPONSE)?'':'display:none'),
					'id' => 'btnAddAnswer',
					'escape' => false
				)
			);
			echo $this->CkSource->ckeditor(
				'feedback_when_wrong', array(
					'id' => 'QuestionFeedbackWhenWrong',
					'label' => __('Feedback when wrong answer'),
					'events' => array(
						'blur' => 'function(){this.updateElement();analyse();}'
					)
				)
			);
			echo $this->CkSource->ckeditor(
				'feedback_when_correct', array(
					'id' => 'QuestionFeedbackWhenCorrect',
					'label' => __('Feedback when correct answer'),
					'events' => array(
						'blur' => 'function(){this.updateElement();analyse();}'
					)
				)
			);
			?>
			</div>
			<div class="span3">
				<table class="table table-condensed">
			<thead>
				<tr>
					<th></th>
					<th><?php echo __('Tags');?></th>
				</tr>
			</thead>
			<tbody>
			<?php for ($i = 0; $i < ((empty($this->request->data['QuestionsTag'])?0:count($this->request->data['QuestionsTag'])) + 10); $i++): ?>
				<tr<?php echo ($this->Form->value('QuestionsTag.'.$i.'.destroy')=='0')?'':' style="display:none;"'; ?>>
					<td>
					<?php
					echo $this->Html->link(
						'<i class="icon-trash"></i>',
						'#', array(
							'onclick' => '
								if (confirm(\''.__('Are you sure you wish to delete this tag?').'\')) {
									$(\'#QuestionsTag'.$i.'Destroy\').val(1);
									$(this).parent().parent().hide();
								}
								event.returnValue = false;
								return false;
							',
							'escape' => false
						)
					);
					echo $this->Form->input('QuestionsTag.'.$i.'.destroy', array('type' => 'hidden', 'default' => 1));
					$this->Form->unlockField('QuestionsTag.'.$i.'.destroy');
					echo $this->Form->input('QuestionsTag.'.$i.'.id', array('type' => 'hidden'));
					?>
					</td>
					<td>
					<?php
					if ($this->Form->value('QuestionsTag.'.$i.'.tag_id')):
						echo $this->Form->input('QuestionsTag.'.$i.'.tag_id', array('type' => 'hidden'));
						$this->Form->unlockField('QuestionsTag.'.$i.'.tag_id');
						echo h($tags[$this->Form->value('QuestionsTag.'.$i.'.tag_id')]);
					else:
						echo $this->Form->input(
							'QuestionsTag.'.$i.'.Tag.name', array(
								'label' => false,
								'autocomplete' => 'off',  // disable browser autocomplete
								'class' => 'typeahead'
							)
						);
					endif;
					?>
					</td>
						</tr>
					<?php endfor; ?>
						<tr>
							<td colspan="2"><a href="#" onclick="$(this).parent().parent().parent().children(':hidden:first').children(':first').find('input[type=hidden]:first').val('0');$(this).parent().parent().parent().children(':hidden:first').show();return false;" class="btn"><i class="icon-plus"></i> <?php echo __('Add Tag'); ?></a></td>
						</tr>
					</tbody>
				</table>
			<?php echo $this->Form->input('comment', array('label' => __('Comments'), 'rows' => 10)); ?>
			</div>
		</div>
		<div class="form-actions">
		<?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-primary', 'div' => false)); ?>
		<?php echo $this->Html->link(__('Cancel'), $referer, array('class' => 'btn')); ?>
		</div>
	<?php echo $this->Form->end(); ?>
	</div>
	<div class="offset9 span3 affix">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#help" data-toggle="tab"><?php echo __('Help'); ?></a></li>
			<li><a href="#analyses" data-toggle="tab"><?php echo __('Analyses'); ?></a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="help">
				<div id="tabHelp">
				<?php if (!empty($instruction)): ?>
					<h4><?php echo h($instruction['Instruction']['name']); ?></h4>
				<?php
					echo $this->Output->html($instruction['Instruction']['content']);
				endif;
				?>
				</div>
			</div>
			<div class="tab-pane" id="analyses">
				<ul>
				<?php
				if (!empty($analyses)):
					foreach ($analyses as $analyse):
				?>
					<li><?php echo $this->HtmLawed->display($analyse); ?></li>
				<?php
					endforeach;
				endif;
				?>
				</ul>
			</div>
		</div>
	</div>
</div>