<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
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
		$this->Session->setFlash(__('Sorry, something went wrong. Please, try again.'), 'alert', array('plugin' => 'TwitterBootstrap', 'class' => 'alert-error'));
		return $this->redirect('/');
	}
}
