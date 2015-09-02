<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 * @property Role $Role
 */
class User extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'username' => array(
			'email' => array(
				'rule' => 'email',
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Please supply a valid email address.'
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'last' => true,
				'message' => 'This username has already been taken.'
			),
			'required' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'on' => 'create'
			),
		),
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'last' => true,
				'message' => 'This field cannot be left blank'
			),
			'required' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'on' => 'create'
			)
		),
		'current_password' => array(
			'rule' => 'checkCurrentPassword',
			'message' => 'Wrong password'
		),
		'confirm_password' => array(
			'equalToField' => array(
				'rule' => array('equalToField', 'password'),
				'message' => 'Require the same value to password.'
			)
		),
		'password' => array(
			'minLength' => array(
				'rule' => array('minLength', 6),
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Passwords must be at least 8 characters long.'
			),
			'required' => array(
				'rule' => 'notEmpty',
				//'required' => 'create'
			),
		),
		'role_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'last' => true,
				'message' => 'This field cannot be left blank'
			),
			'required' => array(
				'rule' => 'notEmpty',
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
		'Role' => array(
			'className' => 'Role',
			'foreignKey' => 'role_id'
		)
	);

/**
 * equalToField
 *
 * @param array $check Value to validate
 * @param string $otherfield Fieldname of field to compare value with
 * @return bool
 */
	public function equalToField($check, $otherfield) {
		//get name of field
		$fname = '';
		foreach ($check as $key => $value) {
			$fname = $key;
			break;
		}
		return $this->data[$this->alias][$otherfield] === $this->data[$this->alias][$fname];
	}

/**
 * checkCurrentPassword
 *
 * @param array $check Value to validate
 * @return bool
 */
	public function checkCurrentPassword($check) {
		$password = array_values($check);
		$password = $password[0];
		$conditions = array(
			'User.id' => AuthComponent::user('id'),
			'User.password' => AuthComponent::password($password)
		);
		return ($this->find('count', compact('conditions')) > 0);
	}

/**
 * Called before each save operation, after validation. Return a non-true result
 * to halt the save.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if the operation should continue, false if it should abort
 * @see Model::save()
 */
	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		return true;
	}

/**
 * beforeValidate method
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @see Model::beforeValidate()
 */
	public function beforeValidate($options = array()) {
		foreach ($this->hasAndBelongsToMany as $k => $v) {
			if (isset($this->data[$k][$k])) {
				$this->data[$this->alias][$k] = $this->data[$k][$k];
			}
		}
		return true;
	}

/**
 * view method
 *
 * @param int $id A user id
 * @return array User data
 */
	public function view($id) {
		$conditions = array('User.id' => $id);
		$contain = array('Role');
		if (AuthComponent::user('role_id') != Role::ADMIN) {
			$conditions[] = array('User.id' => AuthComponent::user('id'));
		}
		return $this->find('first', compact('conditions', 'contain'));
	}

/**
 * adminUpdate method
 *
 * @param array $data User data
 * @return bool
 */
	public function adminUpdate($data) {
		if (empty($data['User']['password'])) {
			unset($data['User']['password']);
		}
		return $this->save($data);
	}

/**
 * adminEdit method
 *
 * @param int $id A user ID
 * @return bool
 */
	public function adminEdit($id) {
		$conditions = array('User.id' => $id);
		return $this->find('first', compact('conditions'));
	}

/**
 * changePassword method
 *
 * @param array $data User data
 * @return bool
 */
	public function changePassword($data) {
		$this->id = AuthComponent::user('id');
		return $this->save($data);
	}

}
