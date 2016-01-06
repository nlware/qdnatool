<?php
App::uses('AppModel', 'Model');
/**
 * Domain Model
 *
 * @property Exam $Exam
 * @property Item $Item
 */
class Domain extends AppModel {

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
			'foreignKey' => 'domain_id',
			'dependent' => false
		)
	);

/**
 * analyse
 *
 * @param int $id A domain id
 * @param int $examId An exam id
 * @return bool
 */
	public function analyse($id, $examId) {
		$result = $this->Exam->executeAnalysis($examId, $id);

		if ($result) {
			$result = $this->saveAnalysis($id, $result);
		}
		return $result;
	}

/**
 * Creates domains and returns ids
 *
 * @param int $examId An exam id
 * @param array $names An array with names of domains
 * @return array An array with domain ids
 */
	public function createDomains($examId, $names) {
		$domainIds = array();
		if (!empty($names)) {
			$uniqueNames = array_values($names);
			$uniqueNames = array_unique($uniqueNames);

			$data = array('Domain' => array());
			foreach ($uniqueNames as $name) {
				$data['Domain'][] = array(
					'exam_id' => $examId,
					'name' => $name
				);
			}
			$this->create();
			$this->saveAll($data);

			$conditions = array('Domain.exam_id' => $examId);
			$domains = $this->find('list', compact('conditions'));

			foreach ($names as $i => $name) {
				foreach ($domains as $id => $domain) {
					if ($name === $domain) {
						$domainIds[$i] = $id;
						break;
					}
				}
			}
			return $domainIds;
		}
	}

/**
 * Duplicate all domains of given exam ids
 *
 * @param array $examIds A hash with original exam ids as key and corresponding duplicated exam ids as value
 * @return array|bool A hash with original domain ids as key and corresponding duplicated domain ids as value, false on failure
 */
	public function duplicate($examIds) {
		$mapping = array();

		$conditions = array('Domain.exam_id' => array_keys($examIds));
		$domains = $this->find('all', compact('conditions'));

		foreach ($domains as $domain) {
			$oldId = $domain['Domain']['id'];
			unset($domain['Domain']['id']);
			$domain['Domain']['exam_id'] = $examIds[$domain['Domain']['exam_id']];

			$this->create();
			if (!$this->save($domain)) {
				return false;
			}
			$mapping[$oldId] = $this->getInsertID();
		}
		return $mapping;
	}

}
