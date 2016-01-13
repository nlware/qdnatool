<?php
App::uses('AppModel', 'Model');
/**
 * Subject Model
 *
 * @property Exam $Exam
 * @property GivenAnswer $GivenAnswer
 */
class Subject extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'value';

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Exam' => array(
			'className' => 'Exam',
			'foreignKey' => 'exam_id'
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'GivenAnswer' => array(
			'className' => 'GivenAnswer',
			'foreignKey' => 'subject_id',
			'dependent' => false
		)
	);

/**
 * Duplicate all subjects of given exam ids
 *
 * @param array $examIds A hash with original exam ids as key and corresponding duplicated exam ids as value
 * @return array|bool A hash with original subject ids as key and corresponding duplicated subject ids as value, false on failure
 */
	public function duplicate($examIds) {
		$mapping = array();

		$conditions = array('Subject.exam_id' => array_keys($examIds));
		$subjects = $this->find('all', compact('conditions'));

		foreach ($subjects as $subject) {
			$oldId = $subject['Subject']['id'];
			unset($subject['Subject']['id']);
			$subject['Subject']['exam_id'] = $examIds[$subject['Subject']['exam_id']];

			$this->create();
			if (!$this->save($subject)) {
				return false;
			}
			$mapping[$oldId] = $this->getInsertID();
		}
		return $mapping;
	}

}
