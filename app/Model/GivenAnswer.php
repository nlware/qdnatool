<?php
App::uses('AppModel', 'Model');
/**
 * GivenAnswer Model
 *
 * @property Item $Item
 * @property Subject $Subject
 */
class GivenAnswer extends AppModel {

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
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'item_id'
		),
		'Subject' => array(
			'className' => 'Subject',
			'foreignKey' => 'subject_id'
		)
	);

/**
 * Duplicate all given answers of given item ids and optionally update correctness of given answers
 *
 * @param array $itemIds A hash with original item ids as key and corresponding duplicated item ids as value
 * @param array $subjectIds A hash with original subject ids as key and corresponding duplicated subject ids as value
 * @param array[optional] $correctAnswerOptions Hash with original item ids as key and indices of related correct answer options as value
 * @return array|bool A hash with original given answer ids as key and corresponding duplicated given answer ids as value, false on failure
 */
	public function duplicate($itemIds, $subjectIds, $correctAnswerOptions = null) {
		$mapping = array();

		$conditions = array('GivenAnswer.item_id' => array_keys($itemIds));
		$givenAnswers = $this->find('all', compact('conditions'));

		foreach ($givenAnswers as $givenAnswer) {
			if ($correctAnswerOptions !== null) {
				if ($givenAnswer['GivenAnswer']['value'] !== null) {
					$givenAnswer['GivenAnswer']['score'] = in_array(($givenAnswer['GivenAnswer']['value'] - 1), $correctAnswerOptions[$givenAnswer['GivenAnswer']['item_id']])?1:0;
				}
			}

			$oldId = $givenAnswer['GivenAnswer']['id'];
			unset($givenAnswer['GivenAnswer']['id']);
			$givenAnswer['GivenAnswer']['item_id'] = $itemIds[$givenAnswer['GivenAnswer']['item_id']];
			$givenAnswer['GivenAnswer']['subject_id'] = $subjectIds[$givenAnswer['GivenAnswer']['subject_id']];

			$this->create();
			if (!$this->save($givenAnswer)) {
				return false;
			}
			$mapping[$oldId] = $this->getInsertID();
		}
		return $mapping;
	}

}
