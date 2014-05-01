<?php
/**
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

	public $helpers = array(
		'Html' => array('className' => 'TwitterBootstrap.BootstrapHtml'),
		'Form' => array('className' => 'CustomBootstrapForm'),
		'Paginator' => array('className' => 'TwitterBootstrap.BootstrapPaginator'),
		'Js',
		'Session',
		'Text',
		'HtmLawed'
	);

	public function beforeFilter() {
		$this->Auth->allow('display');

		$this->Security->blackHoleCallback = 'blackhole';
		if (Configure::read('forceSSL')) {
			$this->Security->requireSecure();
		}
	}

	public function isAuthorized($user) {
		if (isset($this->request->params['admin'])) {
			return ($user['role_id'] == Role::ADMIN);
		}
		return true;
	}

	public function blackhole($type) {
		if ($type == 'secure' && !$this->RequestHandler->isSSL()) {
			return $this->redirect('https://' . env('SERVER_NAME') . $this->here);
		}
		$this->setFlashError(__('Sorry, something went wrong. Please, try again.'));
		return $this->redirect('/');
	}

	public function setFlashSuccess($message) {
		$this->Session->setFlash($message, 'alert', array(
			'plugin' => 'TwitterBootstrap',
			'class' => 'alert-success'
		));
	}

	public function setFlashError($message) {
		$this->Session->setFlash($message, 'alert', array(
			'plugin' => 'TwitterBootstrap',
			'class' => 'alert-error'
		));
	}

}
