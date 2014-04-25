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

    var $uses = array('User', 'Lecturer', 'Admin', 'Student', 'Question', 'Parameter');
    var $components = array("Auth");

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add');
        $this->Auth->allow('verifycode');
        //$this->mc = new Memcached();
        //$this->mc->addServer("localhost", 11211);
    }

    public function index($value='')
    {
    }

    public function add(){
        if($this->request->is('post')){
            $this->User->create();
            if($this->User->save($this->request->data)){
                $this->Session->setFlash(__('登録が成功した'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                    ));
                return $this->redirect(array('controller' => 'user', 'action' => 'login'));
            }
            $this->Session->setFlash(__('登録できない、もう一度お願い'), 'alert', array(
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
		        $this->Session->setFlash(__('管理者はこの画面でロクインできない'), 'alert', array(
					'plugin' => 'BoostCake',
					'class' => 'alert-warning'
				));
				$this->redirect(array('controller'=>'users','action' => 'login'));
	    	}
	        if ($this->Auth->login()) {
	        	$this->Session->write('failedTime',0);
	        	$user = $this->Auth->user();
//		  		if($pause = $this->mc->get($user['username'])){
//	        		$this->Auth->logout();
//		  			$this->Session->setFlash(__('このアカウントは'.date('Y-m-d H:i:s', $pause).'　までロックされる'), 'alert', array(
//							'plugin' => 'BoostCake',
//							'class' => 'alert-warning'
//						));
//					$this->redirect(array('controller'=>'users','action' => 'login'));
//		  		}

            if ($user['actived'] == -1 && $user['role'] == 'lecturer') {
                $this->Auth->logout();
                $this->Session->setFlash(__('このアカウントはロックされた'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                    )); 
                $this->redirect(array('controller'=>'users','action' => 'verifycode',$user['username']));
            }

            if($user['role'] == 'lecturer' && $this->request->clientIp() != $user['Lecturer']['ip_address'])
            {
                $this->Session->setFlash(__('IPアドレスが違う'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                    ));
                $this->redirect(array('controller'=>'Users','action'=>'verifycode',$user['username']));
            }            
            return $this->redirect('/');
        }else
        {
            $locktime = $this->Parameter->getLockTime();
            $failedTime = $this->Session->read('failedTime');
            if(isset($failedTime))
                $this->Session->write('failedTime',$failedTime+1);
            else
                $this->Session->write('failedTime',1);
            if($failedTime >= $this->Parameter->getWrongPasswordTimes()){
                if(!empty($user)){
                    $this->mc->set($user['User']['username'],time() + $locktime, time() + $locktime);
                    if($user['User']['role'] == 'lecturer' && $user['User']['actived'] == 1){
                        $user['User']['actived'] = '-1';    
                        unset($user['User']['password']);
                        $this->User->save($user);
                    }
                }
                $this->Session->setFlash(__('このアカウントは までロックされる'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                    ));
            }else
            $this->Session->setFlash(__('ユーザ名、パスワードが違う'.$failedTime .' time(s)'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'
                ));
        }
    }
}

public function logout(){
    $id = $this->Auth->user("id");
    $sql = "update `users` set `login_time` = '0000-00-00 00:00:00' where id = '$id'";
    $this->User->query($sql);
    return $this->redirect($this->Auth->logout());
}

public function verifycode($value='')
{
    $user = $this->User->findByUsername($value);
    if ($this->request->is('post') ||$this->request->is('put') ) {
        $data = ($this->request->data);
        $this->Session->write('failedTime',0);
        if($user['User']['role'] == 'lecturer' && $data['User']['username']== $user['User']['username']&& md5($data['Lecturer']['verifycode']) == $user['Lecturer']['current_verifycode']){
            $this->Lecturer->id = $user['User']['id'];
            $this->Lecturer->saveField('ip_address',$this->request->clientIp());
            $user['User']['actived'] = '1';
            unset($user['User']['password']);
            $this->User->save($user);
            $this->redirect(array('controller'=>'Users','action'=>'login'));
        }else{
            $this->Session->setFlash(__('verifycodeが違う'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'));
            $this->redirect(array('controller'=>'Users','action'=>'verifycode',$user['User']['username']));
        }
        return $this->redirect('/');
        
        $this->Session->setFlash(__('ユーザ名、パスワードが違う'), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-warning'
            ));
    }
    else{
        $this->Auth->logout();
        $user = $this->User->findByUsername($value);
        $this->request->data = $user;
        $this->request->data['Lecturer']['question_verifycode'] = base64_decode($this->request->data['Lecturer']['question_verifycode']);
    }
}
public function permission($value='')
{
    $this->Session->setFlash(__("あなたはこのページにアクセス権がない"), 'alert', array(
        'plugin' => 'BoostCake',
        'class' => 'alert-warning'
        ));
}   

public function deactive($value='')
{
    $this->Session->setFlash(__("このアカウントはまだアクティブされない"), 'alert', array(
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
                        $this->Session->setFlash(__('更新された'), 'alert', array(
                            'plugin' => 'BoostCake',
                            'class' => 'alert-success'));
                        return $this->redirect($this->referer());
                    } else {
                        $this->Session->setFlash(__('パスワードを更新できない。もう一度おねがい'), 'alert', array(
                            'plugin' => 'BoostCake',
                            'class' => 'alert-warning'));
                    }
                } else {
                    $this->Session->setFlash(__('パスワードと再パスワードが同じ'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-warning'));
                }
            } else {
                $this->Session->setFlash(__('パスワードが送られない'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'));
            }
        }else{
            $this->Session->setFlash(__('現在のパスワードが違う'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'));

        }
    }else{
        $this->request->data = $User;
    }
}
}
