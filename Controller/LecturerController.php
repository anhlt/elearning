<?php

class LecturerController extends AppController {
	var $name = "Lecturer";
  	var $uses = array('User', 'Lecturer','Question','Lesson', 'Test', 'Document', 'Comment','Violate');	
	public $components = array('RequestHandler','Paginator');
	public $helpers = array('Js' => array('Jquery'),'LeftMenu');

  	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow("add");
        if($this->Auth->loggedIn() && $this->Auth->user('role') != 'lecturer')
        	$this->redirect(array('controller' => 'users', 'action' => 'permission'));
    }

    public function add(){
    	if($this->Auth->loggedIn()){
      	  $this->redirect('/');
    	}
		if($this->request->is('post')){
			$this->User->create();
			$this->request->data['Lecturer']['ip_address'] = $this->request->clientIp();
			$this->request->data['Lecturer']['question_verifycode'] = base64_encode($this->request->data['Lecturer']['question_verifycode']);
			$this->request->data['Lecturer']['current_verifycode'] = base64_encode($this->request->data['Lecturer']['current_verifycode']); 
			$this->request->data['Lecturer']['init_verifycode'] = $this->request->data['Lecturer']['current_verifycode']; 
			$this->request->data['Lecturer']['init_password'] = AuthComponent::password($this->request->data['User']['password']);
			$this->request->data['User']['role'] = 'lecturer';
			if($this->User->saveAll($this->request->data)){
				$this->Session->setFlash(__('ユーザがセーブされた'), 'alert', array(
					'plugin' => 'BoostCake',
					'class' => 'alert-success'
				));
				return $this->redirect(array('controller' => 'pages', 'action' => 'display'));
			}else{
				unset($this->request->data['Lecturer']['question_verifycode']);
				unset($this->request->data['Lecturer']['current_verifycode']);
			}
			$this->Session->setFlash(__('ユーザをセーブできない、もう一度お願い'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
			));
		}
	}

    public function edit(){
    	if (empty($this->request->data)) {
	        $lecturer_id = $this->Auth->user('id');
	        $this->request->data = $this->Lecturer->findById($lecturer_id);
			$this->request->data['Lecturer']['question_verifycode'] = base64_decode($this->request->data['Lecturer']['question_verifycode']);
		    $this->request->data['Lecturer']['current_verifycode'] = base64_decode($this->request->data['Lecturer']['current_verifycode']);
    	}else{
			$this->request->data['Lecturer']['ip_address'] = $this->request->clientIp();
			$this->request->data['Lecturer']['current_verifycode'] = base64_encode($this->request->data['Lecturer']['current_verifycode']);
			$this->request->data['Lecturer']['question_verifycode'] = base64_encode($this->request->data['Lecturer']['question_verifycode']);

			if($this->Lecturer->save($this->request->data)){
				$this->Session->setFlash(__('ユーザがセーブされた'), 'alert', array(
					'plugin' => 'BoostCake',
					'class' => 'alert-success'
				));
				return $this->redirect(array('controller' => 'lecturer', 'action' => 'manage'));
			}
			$this->Session->setFlash(__('ユーザをセーブできない、もう一度お願い'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
			));
	    }
    }
	
	public function index(){
		$user = $this->Auth->user();
		if($user["role"] != 'lecturer'){
			$this->redirect(array('controller' => 'users' ,"action" => "permission" ));
		}
	}

	public function lesson($value='')
	{
		
	}	
	public function manage($value='')
	{
		$this->paginate = array(
		    'fields' => array('Lesson.id', 'Lesson.Name','Lesson.summary'),
			'limit' => 5,
			'conditions' => array(
				'Lesson.lecturer_id' => $this->Auth->user('id')
			)
		);

		$results = $this->paginate('Lesson');
		$this->set('results',$results);
	}


	public function studentmanage()
	{
		$lesson_id = $this->params['named']['lesson_id'];
		$lesson = $this->Lesson->findById($lesson_id);
		$this->paginate = array(
		    'fields' => array('Student.full_name','Student.id','LessonMembership.baned','LessonMembership.liked','LessonMembership.lesson_id'),
			'limit' => 10,
			'conditions' => array(
			 	'LessonMembership.lesson_id' => $lesson_id),
			'contain' => array('Student')
		);

		#$this->LessonMembership->Behaviors->load('Containable');
		$students = $this->paginate("LessonMembership");
		$this->set("results",$students);
	}

	public function reply() {
		$user_id = $this->Auth->user('id');
		$lesson_id = $this->params['named']['id'];

		if ($this->request->is('post'))
		{			
			$data['Comment']['user_id'] = $user_id;
			$data['Comment']['lesson_id'] = $lesson_id;
			$data['Comment']['content'] = $this->request->data['Report']['content'];
			$data['Comment']['time'] = date('Y-m-d H:i:s');

			$this->Comment->create();
			if($this->Comment->save($data)){
                $this->Session->setFlash(__('あなたのコメントがアップロードされた'), 'alert', array(
	                'plugin' => 'BoostCake',
	                'class' => 'alert-success'
            	));       	
				
			} else {
                $this->Session->setFlash(__('あなたのコメントをアップロードできない、もう一度お願い'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
            	));	
			}
		}
		return $this->redirect($this->referer());
	}

	public function delete($value='')
	{
		$user_id = $this->Auth->user('id');
		$User = $this->User->findById($user_id);
		if($this->request->is('post') || $this->request->is('put')){
			if(AuthComponent::password($this->request->data['User']['current_password']) == $User['User']['password']){
				$user_id = $this->Auth->user('id');
				$this->User->delete($user_id);
				$this->Auth->logout();
				$this->redirect('/');
			}
		}
	}
	public function violate()
	{
		$id = $this->Auth->user('id');
		$Document = $this->Document->find('all',array(
		    'joins' => array(
			        array(
			            'table' => 'violates',
			            'alias' => 'Violate',
			            'type' => 'LEFT',
			            'conditions' => array(
			                'Document.id = Violate.document_id'
			            )
			        ),
			        array(
			            'table' => 'lessons',
			            // 'alias' => 'Lesson',
			            'type' => 'LEFT',
			            'conditions' => array(
			                'Document.lesson_id = lessons.id'
			            )
			        ),

			   	),
		    'conditions' => array(
		    	'lessons.lecturer_id' => $id,
		    	),
			'group' => 'Document.id',
			));
		$this->set('docs',$Document);
	}
}
