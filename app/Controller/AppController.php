<?php
/**
 * Application level Controller
 *
 * @copyright     Copyright (c) NLWare B.V. (https://www.nlware.com)
 * @link          https://docs.qdnatool.org qDNAtool(tm) Project
 * @license       https://creativecommons.org/licenses/by-nc-sa/3.0/deed.en_GB CC BY-NC-SA 3.0 License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * @package		app.Controller
 * @property AuthComponent $Auth
 * @property FlashComponent $Flash
 * @property RequestHandler $RequestHandler
 * @property SecurityComponent $Security
 * @property SessionComponent $Session
 * @property DebugKit.ToolbarComponent $Toolbar
 */
class AppController extends Controller {

/**
 * An array of names of components to load
 *
 * @var array
 * @see Controller::components
 */
	public $components = array(
		'Security',
		'Auth' => array(
			'authenticate' => array('Form'),
			'authorize' => array('Controller'),
			'loginAction' => array('controller' => 'users', 'action' => 'login', 'admin' => false),
			'loginRedirect' => array('controller' => 'users', 'action' => 'home', 'admin' => false)
		),
		'Flash',
		'RequestHandler',
		'Session',
		'DebugKit.Toolbar'
	);

/**
 * An array of names of helpers to load
 *
 * @var mixed A single name as a string or a list of names as an array.
 * @see Controller::helpers
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
 * @return bool
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
		if ($type == 'secure' && !$this->request->is('ssl')) {
			return $this->redirect('https://' . env('SERVER_NAME') . $this->here);
		}
		$this->Flash->error(__('Sorry, something went wrong. Please, try again.'));
		return $this->redirect('/');
	}

}
