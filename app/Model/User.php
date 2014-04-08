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

	public function equalToField($check, $otherfield) {
		//get name of field
		$fname = '';
		foreach ($check as $key => $value) {
			$fname = $key;
			break;
		}
		return $this->data[$this->alias][$otherfield] === $this->data[$this->alias][$fname];
	}

	public function checkCurrentPassword($check) {
		$password = array_values($check);
		$password = $password[0];
		return ($this->find(
			'count', array(
				'conditions' => array(
					'User.id' => AuthComponent::user('id'),
					'User.password' => AuthComponent::password($password)
				)
			)
		) > 0);
	}

	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		return true;
	}

	public function beforeValidate($options = array()) {
		foreach ($this->hasAndBelongsToMany as $k => $v) {
			if (isset($this->data[$k][$k])) {
				$this->data[$this->alias][$k] = $this->data[$k][$k];
			}
		}
		return true;
	}

	public function view($id) {
		$options = array(
			'conditions' => array(
				'User.id' => $id
			),
			'contain' => array(
				'Role'
			)
		);
		if (AuthComponent::user('role_id') != Role::ADMIN) {
			$options['conditions'][] = array('User.id' => AuthComponent::user('id'));
		}
		return $this->find('first', $options);
	}

	public function adminUpdate($data) {
		if (empty($data['User']['password'])) {
			unset($data['User']['password']);
		}
		return $this->save($data);
	}

	public function adminEdit($id) {
		return $this->find(
			'first', array(
				'conditions' => array(
					'User.id' => $id
				)
			)
		);
	}

	public function changePassword($data) {
		$this->id = AuthComponent::user('id');
		return $this->save($data);
	}
}