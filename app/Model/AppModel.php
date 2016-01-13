<?php
/**
 * Application model for Cake.
 *
 * @copyright     Copyright (c) NLWare B.V. (http://www.nlware.com)
 * @link          http://docs.qdnatool.org qDNAtool(tm) Project
 * @package       app.Model
 * @license       http://creativecommons.org/licenses/by-nc-sa/3.0/deed.en_GB CC BY-NC-SA 3.0 License
 */

App::uses('Model', 'Model');

/**
 * Application model for qDNAtool.
 *
 * @package       app.Model
 */
class AppModel extends Model {

/**
 * actsAs behaviors
 *
 * @var array
 */
	public $actsAs = array('Containable');

/**
 * recursive
 *
 * @var int
 */
	public $recursive = -1;

/**
 * Deletes multiple model records based on a set of conditions.
 * A workaround for CakePHP lack of support for recursive
 *
 * @param mixed $conditions Conditions to match
 * @param bool $cascade Set to true to delete records that depend on this record
 * @param bool $callbacks Run callbacks
 * @param int $recursive (Optional) Overrides the default recursive level
 * @return bool True on success, false on failure
 */
	public function deleteAll($conditions, $cascade = true, $callbacks = false, $recursive = null) {
		if (!isset($recursive)) {
			$recursive = $this->recursive;
		}

		if ($recursive == -1) {
			$belongsTo = $this->belongsTo;
			$hasOne = $this->hasOne;
			$this->unbindModel(array(
				'belongsTo' => array_keys($belongsTo),
				'hasOne' => array_keys($hasOne)
			), true);
		}

		$result = parent::deleteAll($conditions, $cascade, $callbacks);

		if ($recursive == -1) {
			$this->bindModel(array(
				'belongsTo' => $belongsTo,
				'hasOne' => $hasOne
			), true);
		}

		return $result;
	}

/**
 * Updates multiple model records based on a set of conditions.
 * A workaround for CakePHP lack of support for recursive
 *
 * @param array $fields Set of fields and values, indexed by fields.
 *    Fields are treated as SQL snippets, to insert literal values manually escape your data.
 * @param mixed $conditions Conditions to match, true for all records
 * @param int $recursive (Optional) Overrides the default recursive level
 * @return bool True on success, false on failure
 */
	public function updateAll($fields, $conditions = true, $recursive = null) {
		if (!isset($recursive)) {
			$recursive = $this->recursive;
		}

		if ($recursive == -1) {
			$belongsTo = $this->belongsTo;
			$hasOne = $this->hasOne;
			$this->unbindModel(array(
				'belongsTo' => array_keys($belongsTo),
				'hasOne' => array_keys($hasOne)
			), true);
		}

		$result = parent::updateAll($fields, $conditions);

		if ($recursive == -1) {
			$this->bindModel(array(
				'belongsTo' => $belongsTo,
				'hasOne' => $hasOne
			), true);
		}

		return $result;
	}

/**
 * removeFieldFromSchema method
 *
 * @param string $fieldname Fieldname
 * @return void
 */
	public function removeFieldFromSchema($fieldname) {
		unset($this->_schema[$fieldname]);
	}

/**
 * Save analysis to an ezam or a domain
 *
 * @param int $id An domain/exam id
 * @param array $analysis An array with analysis data
 * @return bool True on success, or false on failure.
 * @throws NotImplementedException
 */
	public function saveAnalysis($id, $analysis) {
		if (!in_array($this->alias, array('Domain', 'Exam'))) {
			throw new NotImplementedException();
		}

		$cronbachsAlpha = $analysis[0];
		$maxAnswerOptionCount = $analysis[1];
		$correctAnswerCount = $analysis[2];
		$correctAnswerPercentage = $analysis[3];
		$correctAnswerIrc = $analysis[4];
		$givenAnswerOptionCount = $analysis[5];
		$givenAnswerOptionPercentage = $analysis[6];
		$givenAnswerOptionIrc = $analysis[7];

		$conditions = array(sprintf('%s.id', $this->alias) => $id);
		$contain = array('Item' => array('AnswerOption'));
		$object = $this->find('first', compact('conditions', 'contain'));

		if ($this->alias === 'Domain') {
			$data = array(
				'Domain' => array(
					'id' => $id,
					'cronbachs_alpha' => $cronbachsAlpha,
				)
			);
		} elseif ($this->alias === 'Exam') {
			$data = array(
				'Exam' => array(
					'id' => $id,
					'exam_state_id' => ExamState::ANALYSED,
					'cronbachs_alpha' => $cronbachsAlpha,
					'max_answer_option_count' => $maxAnswerOptionCount,
					'analysed' => date('Y-m-d H:i:s')
				)
			);
		}

		foreach ($object['Item'] as $i => $item) {
			$data['Item'][$i] = array('id' => $item['id']);
			if ($this->alias === 'Domain') {
				$data['Item'][$i]['domain_correct_answer_irc'] = $correctAnswerIrc[$i];
			} elseif ($this->alias === 'Exam') {
				$data['Item'][$i]['correct_answer_count'] = $correctAnswerCount[$i];
				$data['Item'][$i]['correct_answer_percentage'] = $correctAnswerPercentage[$i];
				$data['Item'][$i]['correct_answer_irc'] = $correctAnswerIrc[$i];
				$data['Item'][$i]['missing_answer_count'] = $givenAnswerOptionCount[$i * ($maxAnswerOptionCount + 1)];
				$data['Item'][$i]['missing_answer_percentage'] = $givenAnswerOptionPercentage[$i * ($maxAnswerOptionCount + 1)];
			}

			for ($j = 0; !empty($item['answer_option_count']) && $j < $item['answer_option_count']; $j++) {
				if (empty($item['AnswerOption'][$j]['id'])) {
					$data['Item'][$i]['AnswerOption'][$j]['order'] = ($j + 1);
				} else {
					$data['Item'][$i]['AnswerOption'][$j]['id'] = $item['AnswerOption'][$j]['id'];
				}

				if ($this->alias === 'Domain') {
					$data['Item'][$i]['AnswerOption'][$j]['domain_given_answer_irc'] = (is_nan($givenAnswerOptionIrc[$i * ($maxAnswerOptionCount + 1) + $j + 1])?null:$givenAnswerOptionIrc[$i * ($maxAnswerOptionCount + 1) + $j + 1]);
				} elseif ($this->alias === 'Exam') {
					$data['Item'][$i]['AnswerOption'][$j]['given_answer_count'] = $givenAnswerOptionCount[$i * ($maxAnswerOptionCount + 1) + $j + 1];
					$data['Item'][$i]['AnswerOption'][$j]['given_answer_percentage'] = $givenAnswerOptionPercentage[$i * ($maxAnswerOptionCount + 1) + $j + 1];
					$data['Item'][$i]['AnswerOption'][$j]['given_answer_irc'] = (is_nan($givenAnswerOptionIrc[$i * ($maxAnswerOptionCount + 1) + $j + 1])?null:$givenAnswerOptionIrc[$i * ($maxAnswerOptionCount + 1) + $j + 1]);
				} else {
				}
			}
		}
		$this->id = $id;
		return $this->saveAll($data, array('deep' => true));
	}

}
