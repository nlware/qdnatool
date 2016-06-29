<?php
App::uses('AppModel', 'Model');
/**
 * Category Model
 *
 * @property Exam $Exam
 * @property Item $Item
 */
class Category extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'exam_id' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'This field cannot be left blank'
			),
			'required' => array(
				'rule' => 'notBlank',
				'required' => true,
				'on' => 'create'
			)
		),
		'name' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'This field cannot be left blank'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 255),
				'allowEmpty' => false,
				'message' => 'Can not be longer than %d characters.'
			),
			'isUnique' => array(
				'rule' => array('isUnique', array('exam_id', 'name'), false),
				'message' => 'This name has already been taken for this exam.'
			),
			'required' => array(
				'rule' => 'notBlank',
				'required' => true,
				'on' => 'create'
			)
		)
	);

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
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'category_id',
			'dependent' => false
		)
	);

/**
 * analyse
 *
 * @param int $id A category id
 * @param int $examId An exam id
 * @return bool
 */
	public function analyse($id, $examId) {
		$result = $this->Exam->doAnalyse($examId, $id);

		if ($result) {
			$result = $this->saveAnalysis($id, $result);
		}
		return $result;
	}

/**
 * Creates categories for given category names and exam and returns ids
 *
 * @param int $examId An exam id
 * @param array $names An array with names of categories
 * @return bool|array An array with category ids in the same order as corresponding given names, false on failure
 */
	public function createCategories($examId, $names) {
		$categoryIds = array();
		if (!empty($names)) {
			$uniqueNames = array_values($names);
			$uniqueNames = array_unique($uniqueNames);

			$data = array();
			foreach ($uniqueNames as $name) {
				$data[] = array(
					'exam_id' => $examId,
					'name' => $name
				);
			}
			$this->create();
			if ($this->saveAll($data)) {
				$conditions = array('Category.exam_id' => $examId);
				$categories = $this->find('list', compact('conditions'));

				foreach ($names as $i => $name) {
					foreach ($categories as $id => $category) {
						if ($name === $category) {
							$categoryIds[$i] = $id;
							break;
						}
					}
				}
			} else {
				$categoryIds = false;
			}
		}
		return $categoryIds;
	}

/**
 * Duplicate all categories of given exam ids
 *
 * @param array $examIds A hash with original exam ids as key and corresponding duplicated exam ids as value
 * @return array|bool A hash with original category ids as key and corresponding duplicated category ids as value, false on failure
 */
	public function duplicate($examIds) {
		$mapping = array();

		$conditions = array('Category.exam_id' => array_keys($examIds));
		$categories = $this->find('all', compact('conditions'));

		foreach ($categories as $category) {
			$oldId = $category['Category']['id'];
			unset($category['Category']['id']);
			$category['Category']['exam_id'] = $examIds[$category['Category']['exam_id']];

			$this->create();
			if (!$this->save($category)) {
				return false;
			}
			$mapping[$oldId] = $this->getInsertID();
		}
		return $mapping;
	}

}
