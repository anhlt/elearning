<?php 
class LessonController extends AppController {
	var $name = "Lesson";

  	var $uses = array('User', 'Lecturer','Question','Lesson','Tag', 'Document', 'Test', 'Study', 'Comment');
  	public $components = array('RequestHandler', 'Paginator');

  	public function beforeFilter() {
        parent::beforeFilter();
    }

    public function add($value='')
    {
    	if($this->request->is('post')) {
	    	$data = ($this->request->data);
	    	$data['Lesson']['lecturer_id'] = $this->Auth->user('id');
	    	$rawtags = explode(",",$data["hidden-data"]['Tag']['name']);
	    	$tags = array();
	    	foreach ($rawtags as $key => $value) {
	    		var_dump($value);
		    	$tag = $this->Tag->findByName($value);
		    	if (!$tag) {
		    		$this->Tag->create();
		    		$this->Tag->set("name",$value);
		    		$tag = $this->Tag->save();
		    	}
	    		array_push($tags, $tag['Tag']['id']);
	    	}
	    	$data['Tag'] = $tags;
			$this->Lesson->create();
			if($this->Lesson->saveAll($data)){
				$this->Session->setFlash(__('The Lesson has been saved'), 'alert', array(
					'plugin' => 'BoostCake',
					'class' => 'alert-success'
				));
				return $this->redirect(array('controller' => 'Lecturer', 'action' => 'manage'));
			}
			else{
				$this->Session->setFlash(__('The User could not be saved. Plz try again'), 'alert', array(
					'plugin' => 'BoostCake',
					'class' => 'alert-warning'
				));	
			}
    	}
    }
    public function edit(){
    	$lesson_id = $this->params['named']['id'];
    	//var_dump($lesson_id);
		$Lesson = $this->Lesson->findById($lesson_id);
		if (!$Lesson) {
			$this->Session->setFlash(__('This Lesson not exist'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
			));	
			return $this->redirect(array('controller' => 'Lecturer', 'action' => 'manage'));		
		}
		$this->set("id", $lesson_id);

    	if($this->request->is('post')){
	    	$data = ($this->request->data);
	    	$data['Lesson']['lecturer_id'] = $this->Auth->user('id');
	    	$rawtags = explode(",",$data["hidden-data"]['Tag']['name']);
	    	$tags = array();
	    	foreach ($rawtags as $key => $value) {
	    		#var_dump($value);
		    	$tag = $this->Tag->findByName($value);
		    	if (!$tag) {
		    		$this->Tag->create();
		    		$this->Tag->set("name",$value);
		    		$tag = $this->Tag->save();
		    	}
	    		array_push($tags, $tag['Tag']['id']);
	    	}
	    	$data['Tag'] = $tags;
			if($this->Lesson->saveAll($data)){
				$this->Session->setFlash(__('The Lesson Info has been saved'), 'alert', array(
					'plugin' => 'BoostCake',
					'class' => 'alert-success'
				));
			}
			else{
				$this->Session->setFlash(__('The Lesson Info could not be saved. Plz try again'), 'alert', array(
					'plugin' => 'BoostCake',
					'class' => 'alert-warning'
				));	
			}			
			return $this->redirect($this->referer());
    	}
    }

    public function delete($value='')
    {
    	$lesson_id = $this->params['named']['id'];
    	$lesson = $this->Lesson->find("first", array('conditions' => array('Lesson.id' => $lesson_id))); 
    	if($lesson && $this->Lesson->delete($lesson_id)){
			$this->Session->setFlash(__('The Lesson has been Removed'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-success'
			));
    	}else{
				$this->Session->setFlash(__('The Lesson could not be deleted. Plz try again'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
			));	

    	}
		return $this->redirect($this->referer());
    }

    public function doc()
	{			
		$user = $this->Auth->user();
		$lesson_id = $this->params['named']['id'];
		$this->Session->write('lesson_id', $lesson_id);

		$this->set("id", $lesson_id);			

		if($user["role"] == 'lecturer') {
			$lesson_id = $this->params['named']['id'];
			$sql = array("conditions"=> array("Lesson.id =" => $lesson_id, "Lesson.lecturer_id =" => $user['id']));
			$result = $this->Lesson->find('first',$sql);

			if($result != NULL) {
				$this->paginate = array(
				    'fields' => array('Document.id', 'Document.link', 'Document.title'),
					'limit' => 10,
					'conditions' => array(
						'Document.lesson_id' => $lesson_id
					)
				);

				$this->Paginator->settings = $this->paginate;
				$data = $this->Paginator->paginate("Document");
				$this->set('results', $data);				
			} else {
				$this->redirect(array('controller' => 'users' ,"action" => "permission" ));
			}
		} else {
			$this->redirect(array('controller' => 'users' ,"action" => "permission" ));
		}		
	}

	public function test() {
		$lesson_id = $this->Session->read('lesson_id');
		$this->set("id", $lesson_id);
		$user = $this->Auth->user();
		
		if($user["role"] == 'lecturer') {			
			$sql = array("conditions"=> array("Lesson.id =" => $lesson_id, "Lesson.lecturer_id =" => $user['id']));
			$result = $this->Lesson->find('first', $sql);

			if($result != NULL) {
				$this->paginate = array(
				    'fields' => array('Test.id', 'Test.title', 'Test.test_time','Test.link'),
					'limit' => 10,
					'conditions' => array(
						'Test.lesson_id' => $lesson_id
					)
				);

				$this->Paginator->settings = $this->paginate;
				$data = $this->Paginator->paginate("Test");
				$this->set('results', $data);
			} else {
				$this->redirect(array('controller' => 'users' ,"action" => "permission" ));
			}
		} else {
			$this->redirect(array('controller' => 'users' ,"action" => "permission" ));
		}		
	}

	public function bill() {
		$lesson_id = $this->Session->read('lesson_id');
		$this->set("id", $lesson_id);		
		$user = $this->Auth->user();
		
		if($user["role"] == 'lecturer') {
			$sql = array("conditions"=> array("Lesson.id =" => $lesson_id, "lecturer_id =" => $user['id']));
			$result = $this->Lesson->find('first', $sql);
			//var_dump($result);

			if($result != NULL) {				
				$this->paginate = array(
				    'fields' => array('Student.full_name', 'Student.id', 'Study.baned', 'Study.liked', 'Study.lesson_id', 
				    'Study.start_time', 'Lesson.lesson_time'),
					'limit' => 10,
					'conditions' => array(
					 	'Study.lesson_id' => $lesson_id),
						'contain' => array('Student', 'Lesson')
				);

				$this->Study->Behaviors->load('Containable');
				$students = $this->Paginator->paginate("Study");
				$this->set("results", $students);
				//var_dump($students);

			} else {
				$this->redirect(array('controller' => 'users' ,"action" => "permission" ));
			}
		} else {
			$this->redirect(array('controller' => 'users' ,"action" => "permission" ));
		}
	}


	public function student() {
		$lesson_id = $this->Session->read('lesson_id');
		$this->set("id", $lesson_id);		
		$user = $this->Auth->user();
		
		if($user["role"] == 'lecturer') {
			$sql = array("conditions"=> array("Lesson.id =" => $lesson_id, "lecturer_id =" => $user['id']));
			$result = $this->Lesson->find('first', $sql);
			//var_dump($result);

			if($result != NULL) {				
				$this->paginate = array(
				    'fields' => array('Student.full_name', 'Student.id', 'Study.baned', 'Study.liked', 'Study.lesson_id'),
					'limit' => 10,
					'conditions' => array(
					 	'Study.lesson_id' => $lesson_id),
						'contain' => array('Student')
				);

				$this->Study->Behaviors->load('Containable');
				$students = $this->Paginator->paginate("Study");
				$this->set("results", $students);
				//var_dump($students);

			} else {
				$this->redirect(array('controller' => 'users' ,"action" => "permission" ));
			}
		} else {
			$this->redirect(array('controller' => 'users' ,"action" => "permission" ));
		}
	}

	public function summary() {
		$lesson_id = $this->Session->read('lesson_id');		
		$this->set("id", $lesson_id);
		$user = $this->Auth->user();

		if($user["role"] == 'lecturer') {
			$sql = array("conditions"=> array("Lesson.id =" => $lesson_id, "lecturer_id =" => $user['id']));
			$results = $this->Lesson->find('first', $sql);
			
			if($results != NULL) {
				$sql = array(
				    'fields' => array('Study.liked'),
					'conditions' => array(
					'Study.lesson_id' => $lesson_id)
				);

				$results = $this->Study->find('all', $sql);
				$row = count($results);
				$like = 0;
				foreach ($results as $key => $liked) {					
					if($key) {						
						$like++;
					}
				}			
				
				$this->set('row', $row);
				if($row) {
					$this->set('like', ceil(($like/$row) * 100));
				} else {
					$this->set('like', 0);
				}
			} else {
				$this->redirect(array('controller' => 'users' ,"action" => "permission" ));
			}
		} else {
			$this->redirect(array('controller' => 'users' ,"action" => "permission" ));
		}		
	}

	public function report() {
		$lesson_id = $this->Session->read('lesson_id');		
		$this->set("id", $lesson_id);
		$user = $this->Auth->user();
		//var_dump($user['id']);

		if($user["role"] == 'lecturer') {
			$sql = array("conditions"=> array("Lesson.id =" => $lesson_id, "lecturer_id =" => $user['id']));
			$results = $this->Lesson->find('first', $sql);
			
			if($results != NULL) {				
				$sql = array(
				    'fields' => array('Comment.id', 'Comment.user_id', 'Comment.content', 'Comment.time'),
					'conditions' => array(
						'Comment.lesson_id' => $lesson_id),
						//'contain' => array('Student')
				);
				
				$results = $this->Comment->find('all', $sql);
				//var_dump($results);					
				
				$this->set('results', $results);
				
			} else {
				$this->redirect(array('controller' => 'users' ,"action" => "permission" ));
			}
		} else {
			$this->redirect(array('controller' => 'users' ,"action" => "permission" ));
		}		
	}

    public function banstudent($value=''){
    	$lesson_id = $this->params['named']['lesson_id'];
    	$student_id = $this->params['named']['student_id'];

    	$member = $this->Study->find("first",array(
    				'conditions' => array('lesson_id' => $lesson_id ,'student_id' => $student_id )
    			)
    		);
		$member['Study']['baned'] = !$member['Study']['baned'];

    	if($this->Study->save($member)){
			$this->Session->setFlash(__('The User has been Baned'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-success'
			));
    	}else{
				$this->Session->setFlash(__('The User could not be baned. Plz try again'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
			));	
    	}
		return $this->redirect($this->referer());

    }   

     public function deletestudent($value=''){
    	$lesson_id = $this->params['named']['lesson_id'];
    	$student_id = $this->params['named']['student_id'];

    	$member = $this->Study->find("first",array(
    				'conditions' => array('lesson_id' => $lesson_id ,'student_id' => $student_id )
    			)
    		);

    	if($this->Study->delete($member['Study']['id'])){
			$this->Session->setFlash(__('The User has been Removed'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-success'
			));
    	}else{
				$this->Session->setFlash(__('The User could not be deleted. Plz try again'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
			));	
    	}
		return $this->redirect($this->referer());

    } 

    public function delete_lesson() {
    	$lesson_id = $this->Session->read('lesson_id');
		$this->set("id", $lesson_id);		
		$user = $this->Auth->user();

		
		if($user["role"] == 'lecturer') {
			$sql = array("conditions"=> array("Lesson.id =" => $lesson_id, "lecturer_id =" => $user['id']));
			$result = $this->Lesson->find('first', $sql);
			//var_dump($result);

			if($result != NULL) 
			{				
				if ($this->Lesson->delete($lesson_id))
				{
					$this->Session->setFlash(__('The Lesson has been deleted'), 'alert', array(
	                'plugin' => 'BoostCake',
	                'class' => 'alert-success'
		            )); 		            
            		return $this->redirect(array('controller' => 'lecturer' ,"action" => "manage" ));
            	} 
			}

		} else {
			$this->redirect(array('controller' => 'users' ,"action" => "permission" ));
		}
    }
}