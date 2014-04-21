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
        if($this->Auth->loggedIn()){
        	if ($this->Auth->user('actived') == 0){
        		$this->Session->setFlash(__("あなたのアカウントがまだアクティブしない"), 'alert', array(
        			'plugin' => 'BoostCake',
        			'class' => 'alert-warning'
        			));
        		$this->Auth->logout();
        		$this->redirect('/users/login');
        	}else {
        		$user_id = $this->Auth->user("id");
        		$now = date("Y-m-d H:i:s");
        		$this->loadModel("User");
        		$query = "update `users` set login_time = '$now' where id = '$user_id' ";
        	//	echo $query; 
        		$this->User->query($query);
        	}
        }


        $this->loadModel('Parameter');
        $res = $this->Parameter->find("all");
        foreach ($res as $row) {
        	$paramater = $row['Parameter'];
        	define($paramater['name'], $paramater['value']);
        }
        
	}
}