<?php

class LecturerController extends AppController {
	var $name = "Lecturer";
  	var $uses = array('User', 'Lecturer','Question','Lesson', 'Test', 'Document', 'Comment');	

	public $components = array('RequestHandler', 'Paginator');		
    public $helpers = array('LeftMenu');
    

  	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('add', 'upload_test', 'manage', 'view'));
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
			$this->request->data['User']['role'] = 'lecturer';
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
			'limit' => 10,
			'conditions' => array(
				'Lesson.lecturer_id' => $this->Auth->user('id')
			)
		);

		$this->Paginator->settings = $this->paginate;
		$data = $this->Paginator->paginate("Lesson");
		$this->set('results',$data);
	}

	public function reply() {
		$user_id = $this->Auth->user()['id'];
		$lesson_id = $this->params['named']['id'];

		if ($this->request->is('post'))
		{			
			$data['Comment']['user_id'] = $user_id;
			$data['Comment']['lesson_id'] = $lesson_id;
			$data['Comment']['content'] = $this->request->data['Report']['content'];

			var_dump($data);
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