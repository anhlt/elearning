<?php

class LecturerController extends AppController {
	var $name = "Lecturer";
  	var $uses = array('User', 'Lecturer','Question','Lesson', 'Test', 'Document', 'Comment');	

	public $components = array('RequestHandler','Paginator');
	public $helpers = array('Js' => array('Jquery'),'LeftMenu');

    

  	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow("add");
        $User = $this->Auth->user();
        if($User['role'] != 'lecturer')
        	$this->redirect(array('controller' => 'users', 'action' => 'permission'));
    }

    public function add(){
    	if($this->Auth->loggedIn()){
      	  $this->redirect('/');
    	}
    	$questions = $this->Question->find('all');
    	$droplist = array();
    	foreach ($questions as $question) {
     		$droplist[$question['Question']['id']] = $question['Question']['question'];
    	}
    	$this->set('droplist', $droplist);

		if($this->request->is('post')){
			$this->User->create();
			$this->request->data['Lecturer']['ip_address'] = $this->request->clientIp();
			$this->request->data['Lecturer']['init_verifycode'] = $this->request->data['Lecturer']['current_verifycode']; 
			$this->request->data['Lecturer']['init_password'] = AuthComponent::password($this->request->data['User']['password']);

			$this->request->data['User']['role'] = 'lecturer';
			var_dump($this->request->data);
			if($this->User->saveAll($this->request->data)){
				$this->Session->setFlash(__('The user has been saved'), 'alert', array(
					'plugin' => 'BoostCake',
					'class' => 'alert-success'
				));
				return $this->redirect(array('controller' => 'pages', 'action' => 'display'));
			}
			$this->Session->setFlash(__('The User could not be saved. Plz try again'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
			));
		}
	}

    public function edit(){
		$questions = $this->Question->find('all');
		$droplist = array();
		foreach ($questions as $question) {
			$droplist[$question['Question']['id']] = $question['Question']['question'];
		}
		$this->set('droplist', $droplist);
    	if (empty($this->request->data)) {

	        $lecturer_id = $this->Auth->user('id');
	        $this->request->data = $this->Lecturer->findById($lecturer_id);

    	}else{
			$this->request->data['Lecturer']['ip_address'] = $this->request->clientIp();
			if($this->Lecturer->save($this->request->data)){
				$this->Session->setFlash(__('The user has been saved'), 'alert', array(
					'plugin' => 'BoostCake',
					'class' => 'alert-success'
				));
				return $this->redirect(array('controller' => 'lecturer', 'action' => 'manage'));
			}
			$this->Session->setFlash(__('The User could not be saved. Plz try again'), 'alert', array(
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
		var_dump($lesson);
		die();
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
                $this->Session->setFlash(__('Your comment has been uploaded'), 'alert', array(
	                'plugin' => 'BoostCake',
	                'class' => 'alert-success'
            	));       	
				
			} else {
                $this->Session->setFlash(__('Your comment could not be uploaded. Plz try again'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
            	));	
			}
		}
		return $this->redirect($this->referer());
	}	
}