<?php
App::uses('AppModel', 'Model');
/**
 * DevelopmentPhase Model
 *
 * @property Question $Question
 */
class DevelopmentPhase extends AppModel {

	public $actsAs = array('I18n' => array('fields' => array('name'), 'display' => 'name'));

	const DIVERGE = 1;
	const CONVERGE = 2;

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Question' => array(
			'className' => 'Question',
			'foreignKey' => 'development_phase_id',
			'dependent' => false
		)
	);
}