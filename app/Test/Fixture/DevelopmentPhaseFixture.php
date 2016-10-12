<?php
App::uses('DevelopmentPhase', 'Model');

/**
 * DevelopmentPhase Fixture
 */
class DevelopmentPhaseFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'name_nld' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name_eng' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => DevelopmentPhase::DIVERGE,
			'name_nld' => 'Divergeren',
			'name_eng' => 'Diverge'
		),
		array(
			'id' => DevelopmentPhase::CONVERGE,
			'name_nld' => 'Convergeren',
			'name_eng' => 'Converge'
		),
	);

}
