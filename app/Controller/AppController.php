<?php
/**
 * Application level Controller
 *
 * @copyright     Copyright (c) NLWare B.V. (http://www.nlware.com)
 * @link          http://docs.qdnatool.org qDNAtool(tm) Project
 * @license       http://creativecommons.org/licenses/by-nc-sa/3.0/deed.en_GB CC BY-NC-SA 3.0 License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * @package		app.Controller
 */
class AppController extends Controller {

/**
 * An array of names of components to load
 *
 * @var array
 */
	public $components = array(
		'Security',
		'Auth' => array(
			'authenticate' => array('Form'),
			'authorize' => array('Controller'),
			'loginAction' => array('controller' => 'users', 'action' => 'login', 'admin' => false),
			'loginRedirect' => array('controller' => 'users', 'action' => 'home', 'admin' => false)
		),
		'RequestHandler',
		'Session',
		'DebugKit.Toolbar'
	);

/**
 * An array of names of helpers to load
 *
 * @var mixed A single name as a string or a list of names as an array.
 */
	public $helpers = array(
		'Html' => array('className' => 'TwitterBootstrap.BootstrapHtml'),
		'Form' => array('className' => 'CustomBootstrapForm'),
		'Paginator' => array('className' => 'TwitterBootstrap.BootstrapPaginator'),
		'Js',
		'Session',
		'Text',
		'HtmLawed'
	);

/**
 * beforeFilter
 *
 * @return void
 * @see Controller::beforeFilter()
 */
	public function beforeFilter() {
		$this->Auth->allow('display');

		$this->Security->blackHoleCallback = 'blackhole';
		if (Configure::read('forceSSL')) {
			$this->Security->requireSecure();
		}
	}

/**
 * isAuthorized
 *
 * @param array $user A user
 * @return booleanl
 */
	public function isAuthorized($user) {
		if (isset($this->request->params['admin'])) {
			return ($user['role_id'] == Role::ADMIN);
		}
		return true;
	}

/**
 * Blackhole callback
 *
 * @param string $type Type of error
 * @return void
 */
	public function blackhole($type) {
		if ($type == 'secure' && !$this->RequestHandler->isSSL()) {
			return $this->redirect('https://' . env('SERVER_NAME') . $this->here);
		}
		$this->setFlashError(__('Sorry, something went wrong. Please, try again.'));
		return $this->redirect('/');
	}

/**
 * Used to set a session variable that can be used to output success messages in the view.
 *
 * @param string $message Message to be flashed
 * @return void
 */
	public function setFlashSuccess($message) {
		$this->Session->setFlash($message, 'alert', array(
			'plugin' => 'TwitterBootstrap',
			'class' => 'alert-success'
		));
	}

/**
 * Used to set a session variable that can be used to output error messages in the view.
 *
 * @param string $message Message to be flashed
 * @return void
 */
	public function setFlashError($message) {
		$this->Session->setFlash($message, 'alert', array(
			'plugin' => 'TwitterBootstrap',
			'class' => 'alert-error'
		));
	}

}
