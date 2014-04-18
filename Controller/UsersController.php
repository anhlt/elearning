<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
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
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

class UsersController extends AppController {
	var $uses = array('User', 'Lecturer','Admin','Student','Question','Parameter');
    var $components = array("Auth");
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add');
        $this->Auth->allow('verifycode');
        $this->mc = new Memcached();
        $this->mc->addServer("localhost", 11211);
    }

	public function index($value='')
	{
	}

    public function add(){
    	if($this->request->is('post')){
    		$this->User->create();
    		if($this->User->save($this->request->data)){
    			$this->Session->setFlash(__('The user has been saved'), 'alert', array(
					'plugin' => 'BoostCake',
					'class' => 'alert-success'
				));
    			return $this->redirect(array('controller' => 'user', 'action' => 'login'));
    		}
			$this->Session->setFlash(__('The User could not be saved. Plz try again'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
			));
    	}

    }
 
	public function login() {

	   	if($this->Auth->loggedIn()){
      	  $this->redirect('/');
    	}
    	
	    if ($this->request->is('post')) {
	    	$data = ($this->request->data);
	    	$user = $this->User->findByUsername($data['User']['username']);
	    	if (isset($user['User']) && $user['User']['role'] =='admin') {
		        $this->Session->setFlash(__('Manager cant not login here'), 'alert', array(
					'plugin' => 'BoostCake',
					'class' => 'alert-warning'
				));
	    	}

	        if ($this->Auth->login()) {
	        	$this->Session->write('failedTime',0);
	        	$user = $this->Auth->user();

		  		if($pause = $this->mc->get($user['username'])){
	        		$this->Auth->logout();
		  			$this->Session->setFlash(__('This account have been locked until'. date('Y-m-d H:i:s', $pause)), 'alert', array(
							'plugin' => 'BoostCake',
							'class' => 'alert-warning'
						));
					$this->redirect(array('controller'=>'users','action' => 'login'));
		  		}

	        	if ($user['actived'] == -1 && $user['role'] == 'lecturer') {
	        		$this->Auth->logout();
			        $this->Session->setFlash(__('This account have been locked'), 'alert', array(
						'plugin' => 'BoostCake',
						'class' => 'alert-warning'
					));	
					$this->redirect(array('controller'=>'users','action' => 'verifycode'));
	        	}

	        	if($user['role'] == 'lecturer' && $this->request->clientIp() != $user['Lecturer']['ip_address'])
	        	{
			        $this->Session->setFlash(__('Wrong IP'), 'alert', array(
						'plugin' => 'BoostCake',
						'class' => 'alert-warning'
					));
	        		$this->redirect(array('controller'=>'Users','action'=>'verifycode'));
	        	}            
	            return $this->redirect('/');
	        }else
	        {
	        	if(!empty($user)){
		        	if(isset($failedTime))
			        	$this->Session->write('failedTime',$failedTime+1);
			        else
			        	$this->Session->write('failedTime',1);
	            	if($failedTime >= $this->Parameter->getWrongPasswordTimes()){
	    				$this->mc->set($user['User']['username'],time() + 50, time() + 50);
	    				$user['User']['actived'] = '-1';	
	    				unset($user['User']['password']);
	    				$this->User->save($user);
	    			}
				}
		        $this->Session->setFlash(__('Invalid username or password, try again '.$failedTime .' time(s)'), 'alert', array(
					'plugin' => 'BoostCake',
					'class' => 'alert-warning'
				));	
	        }
	    }
	}

	public function logout(){
		return $this->redirect($this->Auth->logout());
	}

	public function verifycode($value='')
	{
	
		$this->Auth->logout();
		$questions = $this->Question->find('all');
    	$droplist = array();
    	foreach ($questions as $question) {
     		$droplist[$question['Question']['id']] = $question['Question']['question'];
    	}
    	$this->set('droplist', $droplist);

		if ($this->request->is('post')) {
			$data = ($this->request->data);
			if ($this->Auth->login()) {
	        	$this->Session->write('failedTime',0);
				$user = $this->Auth->user();
				$user['actived'] = 1;
				$this->User->save($user);
				if($user['role'] == 'lecturer' && $data['Lecturer']['question_verifycode_id'] == $user['Lecturer']['question_verifycode_id'] 
					 && $data['Lecturer']['current_verifycode'] == $user['Lecturer']['current_verifycode']){
					$this->Lecturer->id = $this->Auth->user('id');
					$this->Lecturer->saveField('ip_address',$this->request->clientIp());
				}else{
					$this->Auth->logout();
					$this->Session->setFlash(__('Wrong verifycode, try again'), 'alert', array(
						'plugin' => 'BoostCake',
						'class' => 'alert-warning'));
	        		$this->redirect(array('controller'=>'Users','action'=>'verifycode'));

				}
	            return $this->redirect('/');
			}

			$this->Session->setFlash(__('Invalid username or password, try again'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
			));
		}
	}



	public function permission($value='')
	{
		$this->Session->setFlash(__("You don't have permission to visit this page"), 'alert', array(
		'plugin' => 'BoostCake',
		'class' => 'alert-warning'
		));
	}	

	public function deactive($value='')
	{
		$this->Session->setFlash(__("You account haven't been actived"), 'alert', array(
		'plugin' => 'BoostCake',
		'class' => 'alert-warning'
		));
	}

	public function changepassword($value='')
	{
		$User =$this->User->findById($this->Auth->User('id'));
		if($this->request->is('post') || $this->request->is('put')){
			if(AuthComponent::password($this->request->data['User']['current_password']) == $User['User']['password']){
		        if(!empty($this->request->data['User']['password']) && !empty($this->request->data['User']['password_retype'])){
		            if($this->request->data['User']['password'] == $this->request->data['User']['password_retype']){
		                $this->User->id=$this->Auth->user('id');
		                if($this->User->save($this->request->data)){
		                    $this->Session->setFlash(__('changed'), 'alert', array(
          	 				'plugin' => 'BoostCake',
			                'class' => 'alert-success'));
							return $this->redirect($this->referer());
		                } else {
		                    $this->Session->setFlash(__('Could not change your password due a server problem, try again latter'), 'alert', array(
          	 				'plugin' => 'BoostCake',
            				'class' => 'alert-warning'));
		                }
		            } else {
		                $this->Session->setFlash(__('Your password and your retype must match'), 'alert', array(
          	 				'plugin' => 'BoostCake',
            				'class' => 'alert-warning'));
		            }
		        } else {
		            $this->Session->setFlash(__('Password or retype not sent'), 'alert', array(
          	 				'plugin' => 'BoostCake',
            				'class' => 'alert-warning'));
		        }
		    }else{
	            $this->Session->setFlash(__('Current pw wrong'), 'alert', array(
          	 				'plugin' => 'BoostCake',
            				'class' => 'alert-warning'));

		    }
		}else{
			$this->request->data = $User;
		}
	}
}
