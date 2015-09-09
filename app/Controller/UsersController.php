<?php
App::uses('Role', 'Model');
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

/**
 * beforeFilter
 *
 * @return void
 * @see AppController::beforeFilter()
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('logout', 'saml_login', 'classic_login');
	}

	/*
	public function beforeRender()
	{
		$model = Inflector::singularize($this->name);
		foreach ($this->{$model}->hasAndBelongsToMany as $k=>$v) {
			if (isset($this->{$model}->validationErrors[$k]))
			{
				$this->{$model}->{$k}->validationErrors[$k] = $this->{$model}->validationErrors[$k];
			}
		}
	}
	*/

/**
 * view method
 *
 * @param string $id A user id
 * @return void
 * @throws NotFoundException
 */
	public function view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->view($id));
	}

/**
 * add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Flash->success(__('The user has been saved'));
				return $this->redirect(array('admin' => false, 'action' => 'index'));
			} else {
				$this->Flash->error(__('The user could not be saved. Please, try again.'));
			}
		}
		$roles = $this->User->Role->find('list');
		$this->set(compact('roles'));
	}

/**
 * edit method
 *
 * @param string $id A user id
 * @return void
 * @throws NotFoundException
 */
	public function admin_edit($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->adminUpdate($this->request->data)) {
				$this->Flash->success(__('The user has been saved'));
				return $this->redirect(array('admin' => false, 'action' => 'index'));
			} else {
				$this->Flash->error(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->adminEdit($id);
		}
		$roles = $this->User->Role->find('list');
		$this->set(compact('roles'));
	}

/**
 * delete method
 *
 * @param string $id A user id
 * @return void
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 */
	public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Flash->success(__('User deleted'));
			return $this->redirect(array('admin' => false, 'action' => 'index'));
		}
		$this->Flash->error(__('User was not deleted'));
		return $this->redirect(array('admin' => false, 'action' => 'index'));
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$conditions = array();
		$contain = array('Role');
		if ($this->Auth->user('role_id') != Role::ADMIN) {
			$conditions = array('User.id' => $this->Auth->user('id'));
		}
		$this->paginate = compact('contain');
		$this->set('users', $this->paginate($conditions));
	}

/**
 * account method
 *
 * @return void
 */
	public function account() {
	}

/**
 * home method
 *
 * @return void
 */
	public function home() {
	}

/**
 * classic_login method
 *
 * @return void
 */
	public function classic_login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				return $this->redirect($this->Auth->redirectUrl());
			} else {
				$this->Flash->error(__('Invalid username or password, try again'));
			}
		}
	}

/**
 * login method
 *
 * @return void
 */
	public function login() {
	}

/**
 * saml_login method
 *
 * @return void
 */
	public function saml_login() {
		//App::import('Vendor', 'simplesamlphp', array('file' => DS . 'usr' . DS . 'share' . DS . 'simplesamlphp' . DS . 'lib' . DS . '_autoload.php'));
		require_once ('/usr/share/simplesamlphp/lib/_autoload.php');

		$as = new SimpleSAML_Auth_Simple('SURFconext');

		$as->requireAuth(array('ReturnTo' => 'https://www.qdnatool.org/users/saml_login'));
		$nameid = $as->getAuthData("saml:sp:NameID");

		if (!empty($nameid)) {
			$conditions = array('User.surfconext_identifier' => $nameid['Value']);
			$user = $this->User->find('first', compact('conditions'));
			if (empty($user['User'])) {
				//create user
				$attributes = $as->getAttributes();

				$data = array(
					'User' => array(
						'username' => $attributes['urn:mace:dir:attribute-def:mail'][0],
						'name' => $attributes['urn:mace:dir:attribute-def:displayName'][0],
						'role_id' => Role::USER,
						'surfconext_identifier' => $nameid['Value'],
					)
				);

				$this->User->create();
				if ($this->User->save($data)) {
					$conditions = array('User.surfconext_identifier' => $nameid);
					$user = $this->User->find('first', compact('conditions'));
				}
			}

			if (!empty($user['User'])) {
				if ($this->Auth->login($user['User'])) {
					$this->Flash->success(__('Successful login via SURFconext.'));
					return $this->redirect($this->Auth->redirectUrl());
				}
			}
		}
		$this->Flash->error(__('Failed to login via SURFconext. Please, try again.'));
		return $this->redirect('/');
	}

/**
 * logout method
 *
 * @return void
 */
	public function logout() {
		//App::import('Vendor', 'simplesamlphp', array('file' => DS . 'usr' . DS . 'share' . DS . 'simplesamlphp' . DS . 'lib' . DS . '_autoload.php'));
		require_once ('/usr/share/simplesamlphp/lib/_autoload.php');

		$as = new SimpleSAML_Auth_Simple('SURFconext');
		if ($as->isAuthenticated()) {
			return $this->redirect($as->getLogoutURL($this->Auth->logout()));
		} else {
			return $this->redirect($this->Auth->logout());
		}
	}

/**
 * change_password method
 *
 * @return void
 */
	public function change_password() {
		if ($this->request->is('post')) {
			if ($this->User->changePassword($this->request->data)) {
				$this->Flash->success(__('Password has been changed.'));
				return $this->redirect(array('action' => 'account'));
			} else {
				$this->Flash->error(__('Password could not be changed. Plaese try again.'));
			}
		}
	}

}
