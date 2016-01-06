<?php
App::uses('AppModel', 'Model');
/**
 * AnswerOption Model
 *
 * @property Item $Item
 */
class AnswerOption extends AppModel {

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
		)
	);

/**
 * printIndex method
 *
 * @param int $index Index
 * @return string
 */
	public static function printIndex($index) {
		return chr(65 + $index);
	}

/**
 * printValue method
 *
 * @param int $value Value
 * @return string
 */
	public static function printValue($value) {
		return chr(65 + $value - 1);
	}

/**
 * Duplicate all answer options of given item ids and optionally update correct answer options
 *
 * @param array $itemIds A hash with original item ids as key and corresponding duplicated item ids as value
 * @param array[optional] $correctAnswerOptions Hash with original item ids as key and indices of related correct answer options as value
 * @return array|bool A hash with original answer option ids as key and corresponding duplicated answer option ids as value, false on failure
 */
	public function duplicate($itemIds, $correctAnswerOptions = null) {
		$mapping = array();

		$conditions = array('AnswerOption.item_id' => array_keys($itemIds));
		$answerOptions = $this->find('all', compact('conditions'));

		foreach ($answerOptions as $answerOption) {
			if ($correctAnswerOptions !== null) {
				$answerOption['AnswerOption']['is_correct'] = in_array($answerOption['AnswerOption']['order'], $correctAnswerOptions[$answerOption['AnswerOption']['item_id']]);
			}

			$oldId = $answerOption['AnswerOption']['id'];
			unset($answerOption['AnswerOption']['id']);
			$answerOption['AnswerOption']['item_id'] = $itemIds[$answerOption['AnswerOption']['item_id']];

			$this->create();
			if (!$this->save($answerOption)) {
				return false;
			}
			$mapping[$oldId] = $this->getInsertID();
		}
		return $mapping;
	}

}
