<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
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

	public $helpers = array(
		'Session',
		'Html' => array('className' => 'BoostCake.BoostCakeHtml'),
		'Form' => array('className' => 'BoostCake.BoostCakeForm'),
		'Paginator' => array('className' => 'BoostCake.BoostCakePaginator'),
	);
	
	public $components = array(
	'Auth' => array(
		'flash' => array(
			'element' => 'alert',
			'key' => 'auth',
			'params' => array(
				'plugin' => 'BoostCake',
				'class' => 'alert-error'
			),
			'loginRedirect' => array('controller' => 'pages', 'action' => 'display', 'home'),
       		'logoutRedirect' => array('controller' => 'pages', 'action' => 'display', 'home')
		)
	),
	'Session',
	);
	public function beforeRender() {
	     $this->response->disableCache();
	}
	public function beforeFilter()
	{
        if($this->Auth->loggedIn() && $this->Auth->user('actived') == 0){
        	$this->Auth->logout();
        	$this->redirect('/');
			$this->Session->setFlash(__("You account haven't been actived"), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
				));

        }
	}
}