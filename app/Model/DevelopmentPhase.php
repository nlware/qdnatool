<?php
App::uses('AppModel', 'Model');

/**
 * DevelopmentPhase Model
 *
 * @property Question $Question
 */
class DevelopmentPhase extends AppModel {

/**
 * actsAs behaviors
 *
 * @var array
 */
	public $actsAs = array('I18n' => array('fields' => array('name'), 'display' => 'name'));

	const DIVERGE = 1;

	const CONVERGE = 2;

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
