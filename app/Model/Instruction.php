<?php
App::uses('AppModel', 'Model');

/**
 * Instruction Model
 *
 * @property DevelopmentPhase $DevelopmentPhase
 * @property QuestionFormat $QuestionFormat
 */
class Instruction extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'DevelopmentPhase' => array(
			'className' => 'DevelopmentPhase',
			'foreignKey' => 'development_phase_id'
		),
		'QuestionFormat' => array(
			'className' => 'QuestionFormat',
			'foreignKey' => 'question_format_id'
		)
	);

/**
 * get method
 *
 * @param int[optional] $developmentPhaseId Development phase id
 * @param int[optional] $questionFormatId Question format id
 * @return array
 */
	public function get($developmentPhaseId = null, $questionFormatId = null) {
		$conditions = array(
			'Instruction.development_phase_id' => $developmentPhaseId,
			'Instruction.question_format_id' => $questionFormatId
		);
		return $this->find('first', compact('conditions'));
	}

}
