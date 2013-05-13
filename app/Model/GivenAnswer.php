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

	//The Associations below have been created with all possible keys, those that are not needed can be removed

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
}