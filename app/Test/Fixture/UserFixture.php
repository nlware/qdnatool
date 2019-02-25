<?php
App::uses('Role', 'Model');
App::uses('Security', 'Utility');

/**
 * User Fixture
 *
 */
class UserFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'username' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'role_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'surfconext_identifier' => array('type' => 'string', 'null' => true, 'default' => null, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'surfconext_identifier' => array('column' => 'surfconext_identifier', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
public $records = array();

/**
 * Initialize the fixture
 *
 * @return void
 * @see CakeTestFixture::init()
 */
	public function init() {
		$this->records = array(
			array(
				'id' => '1',
				'username' => 'test@test.nl',
				'name' => 'Test',
				'password' => Security::hash('testtest', 'sha1', true),
				'role_id' => Role::USER,
				'surfconext_identifier' => null
			),
		);
		parent::init();
	}

}
