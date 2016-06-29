<?php
App::uses('AppUtil', 'Lib');
App::uses('AppModel', 'Model');
/**
 * Item Model
 *
 * @property AnswerOption $AnswerOption
 * @property Category $Category
 * @property Exam $Exam
 */
class Item extends AppModel {

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
		'Category' => array(
			'className' => 'Category',
			'foreignKey' => 'exam_id'
		),
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
		'AnswerOption' => array(
			'className' => 'AnswerOption',
			'foreignKey' => 'item_id',
			'dependent' => true,
			'order' => 'AnswerOption.Order'
		),
		'GivenAnswer' => array(
			'className' => 'GivenAnswer',
			'foreignKey' => 'item_id',
			'dependent' => true
		)
	);

/**
 * Get most given incorrect answer option
 *
 * @param array $item Item data
 * @return array AnswerOption data
 */
	private function __getMostGivenIncorrectAnswerOption($item) {
		$mostIncorrectAnswerOption = false;
		foreach ($item['AnswerOption'] as $i => $answerOption) {
			if (!$answerOption['is_correct']) {
				if (empty($mostIncorrectAnswerOption)) {
					$mostIncorrectAnswerOption = $answerOption;
				} elseif ($answerOption['given_answer_percentage'] > $mostIncorrectAnswerOption['given_answer_percentage']) {
					$mostIncorrectAnswerOption = $answerOption;
				}
			}
		}
		return $mostIncorrectAnswerOption;
	}

/**
 * Add item to exam
 *
 * @param int $examId Exam id
 * @param int $defaultAnswerOptionCount Default number of answer options
 * @param string $question Question
 * @param int $givenAnswerOptionOrder Order index of given answer option
 * @param int $score Score
 * @param int $maximumScore Maximum score
 * @return int Item id
 */
	public function add($examId, $defaultAnswerOptionCount, $question, $givenAnswerOptionOrder, $score, $maximumScore) {
		$itemId = false;
		$isCorrect = ($score == $maximumScore && $maximumScore > 0);

		// lookup item
		$conditions = array(
			'Item.exam_id' => $examId,
			'Item.value' => $question
		);
		$contain = array('AnswerOption');
		$item = $this->find('first', compact('conditions', 'contain'));

		if (empty($item)) {
			$conditions = array('Item.exam_id' => $examId);
			$order = array('Item.order' => 'DESC');
			$lastItem = $this->find('first', compact('conditions', 'order'));
			$item = array(
				'Item' => array(
					'exam_id' => $examId,
					'order' => empty($lastItem['Item']['order'])?1:$lastItem['Item']['order'] + 1,
					'value' => $question,
					'answer_option_count' => (($givenAnswerOptionOrder != null) && ($givenAnswerOptionOrder > $defaultAnswerOptionCount))?$givenAnswerOptionOrder:$defaultAnswerOptionCount
				)
			);

			for ($k = 0; $k < $item['Item']['answer_option_count']; $k++) {
				$item['AnswerOption'][] = array(
					'order' => $k + 1,
					'is_correct' => ($isCorrect && $givenAnswerOptionOrder != null && ($givenAnswerOptionOrder == $k + 1))
				);
			}

			$this->create();
			if ($this->saveAll($item)) {
				$itemId = $this->id;
			}
		} else {
			$itemId = $item['Item']['id'];

			// update is_correct of answer_option if answer_option already exists
			if ($isCorrect && $givenAnswerOptionOrder != null && isset($item['AnswerOption'][$givenAnswerOptionOrder - 1]['is_correct']) && !$item['AnswerOption'][$givenAnswerOptionOrder - 1]['is_correct']) {
				$this->AnswerOption->id = $item['AnswerOption'][$givenAnswerOptionOrder - 1]['id'];
				$this->AnswerOption->saveField('is_correct', true);
			}

			// update answer_option_count and add answer_option(s)
			if ($givenAnswerOptionOrder != null && ($givenAnswerOptionOrder > $item['Item']['answer_option_count'])) {
				$this->id = $item['Item']['id'];
				$this->saveField('answer_option_count', $givenAnswerOptionOrder);

				$answerOptions = array();
				$answerOptionCount = count($item['AnswerOption']);
				for ($i = $answerOptionCount; $i < $givenAnswerOptionOrder; $i) {
					$answerOptions[] = array(
						'item_id' => $item['Item']['id'],
						'order' => $i + 1,
						'is_correct' => ($isCorrect && ($givenAnswerOptionOrder == $i + 1))
					);
				}
				if (!empty($answerOptions)) {
					$this->AnswerOption->create();
					$this->AnswerOption->saveAll($answerOptions);
				}
			}
		}
		return $itemId;
	}

/**
 * stevie method
 *
 * @param array $item Item data
 * @param int[optional] $answerOptionCount Number of answer options
 * @return array Enriched item data
 */
	public function stevie($item, $answerOptionCount = null) {
		$item['Messages'] = array();
		if ($item['correct_answer_irc'] < -0.1) {
			$mostGivenIncorrectAnswerOption = $this->__getMostGivenIncorrectAnswerOption($item);
			if (!empty($mostGivenIncorrectAnswerOption) && $mostGivenIncorrectAnswerOption['given_answer_percentage'] > 35) {
				$item['Messages'][] = __('Has the correct alternative been assessed as correct? Most students choose the alternative %s.', AppUtil::optionValue($mostGivenIncorrectAnswerOption['order']));
				$item['Messages'][] = __('In case the correct alternative has been assessed as correct, remove the question (it is probably a so called ‘trick question’: these type of questions do not measure the knowledge of the student).');
			} else {
				$item['Messages'][] = __('Good students generally answer this question incorrectly. The advice is to remove this question from the test.');
			}
		} elseif ($item['correct_answer_irc'] < 0.1) {
			if (!empty($item['answer_option_count'])) {
				$answerOptionCount = $item['answer_option_count'];
			}

			if (!empty($answerOptionCount)) {
				switch ($answerOptionCount) {
					case 2:
					case 3:
						$correctAnswerPercentageLowerLimit = 25;
						$correctAnswerPercentageUpperLimit = 60;
						break;
					case 4:
						$correctAnswerPercentageLowerLimit = 25;
						$correctAnswerPercentageUpperLimit = 70;
						break;
					default:
						$correctAnswerPercentageLowerLimit = null;
						$correctAnswerPercentageUpperLimit = null;
						break;
				}
			}

			if (!empty($correctAnswerPercentageLowerLimit) && !empty($correctAnswerPercentageUpperLimit)) {
				// when P < 0,25:
				if ($item['correct_answer_percentage'] < $correctAnswerPercentageLowerLimit) {
					$item['Messages'][] = __('This question has been answered incorrectly by many students. Has the alternative correct answer been included as correct?');
					$item['Messages'][] = __('In case the alternative correct answer has been included in the assessment, remove the question from the test. In case the incorrect alternative has been assessed as correct, include this as a correct answer and asses the test again.');
				} elseif ($item['correct_answer_percentage'] < $correctAnswerPercentageUpperLimit) {
					// when 0.25 < p < 0.70:

					$mostGivenIncorrectAnswerOption = $this->__getMostGivenIncorrectAnswerOption($item);
					// when a> 0.35:
					if (!empty($mostGivenIncorrectAnswerOption) && $mostGivenIncorrectAnswerOption['given_answer_percentage'] > 35) {
						$item['Messages'][] = __('Instead of choosing the correct alternative, students are choosing the alternative %s.', AppUtil::optionValue($mostGivenIncorrectAnswerOption['order']));
						$item['Messages'][] = __('Is it possible that the alternative answer should also be assessed as correct? Examine why many students are choosing the alternative answer %s.', AppUtil::optionValue($mostGivenIncorrectAnswerOption['order']));
					} else {
						$item['Messages'][] = __('This question does not distinguish between good and less good students: guessing has taken place. Has the content been covered in class?');
						$item['Messages'][] = __('It is not necessary to remove this question from the exam.');
					}
				}
			}
		}
		return $item;
	}

/**
 * Get items ids of an exam and optionally filter by a category
 *
 * @param int $examId An exam id
 * @param int[optional] $categoryId A category id
 * @return array An array with items ids
 */
	public function getIds($examId, $categoryId = null) {
		$fields = array('Item.id', 'Item.id');
		$conditions = array('Item.exam_id' => $examId);
		if ($categoryId !== null) {
			$conditions[] = array('Item.category_id' => $categoryId);
		}
		$data = $this->find('list', compact('fields', 'conditions'));
		return array_values($data);
	}

/**
 * Duplicate all or optionally only filtered items of given exam ids
 *
 * @param array $examIds A hash with original exam ids as key and corresponding duplicated exam ids as value
 * @param array $categoryIds A hash with original category ids as key and corresponding duplicated category ids as value
 * @param array[optional] $filteredIds A list of item ids to filter
 * @return array|bool A hash with original item ids as key and corresponding duplicated item ids as value, false on failure
 */
	public function duplicate($examIds, $categoryIds, $filteredIds = null) {
		$mapping = array();

		$conditions = array('Item.exam_id' => array_keys($examIds));
		if ($filteredIds !== null) {
			$conditions['Item.id'] = $filteredIds;
		}
		$items = $this->find('all', compact('conditions'));

		foreach ($items as $item) {
			$oldId = $item['Item']['id'];
			unset($item['Item']['id']);
			$item['Item']['exam_id'] = $examIds[$item['Item']['exam_id']];
			if ($item['Item']['category_id'] !== null) {
				$item['Item']['category_id'] = $categoryIds[$item['Item']['category_id']];
			}

			$this->create();
			if (!$this->save($item)) {
				return false;
			}
			$mapping[$oldId] = $this->getInsertID();
		}
		return $mapping;
	}

}
