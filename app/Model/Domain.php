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

}
