<?php
class DocumentController extends AppController {
	var $name = "Document";
	var $uses = array('Document','Lesson', 'Report');
	public $components = array('Util'); 
	public $helpers = array("TsvReader");

	public function add() {
		$lesson_id = $this->params['named']['id'];
		$this->set('id', $lesson_id);
		$list = array('video/mp4','audio/mpeg','audio/x-wav','image/jpeg','image/gif','image/png','application/pdf');
		if ($this->request->is('post')) {
			$data = $this->request->data['Document'];
			unset($data['check']);
			$error = array();
			foreach ($data as $Document)
			{				
				$Document['link']['name'] = str_replace(' ', '', $Document['link']['name']);
				$name = uniqid() . $Document['link']['name'];
				$data['Document']['link'] =  $name;
				if (is_uploaded_file($Document['link']['tmp_name'])) {
					$data['Document']['title'] = $Document['title'];					
					$data['Document']['lesson_id'] = $lesson_id;
					$this->Document->create();
//					if(!in_array(mime_content_type($Document['link']['tmp_name']),$list)) {
//						array_push($error, $Document['link']['name']);
//						continue;
//					}
					if ($this->Document->save($data)) {
						move_uploaded_file($Document['link']['tmp_name'], WWW_ROOT . "files". DS . $name);
					}
					else {
						array_push($error, $Document['link']['name']);
	                }
				}     
	        }
	        if(sizeof($error)!=0)
	        	$this->Session->setFlash(__('ドキュメントをアップロードできない、もう一度お願い '.implode(",", $error)), 'alert', array(
	 				'plugin' => 'BoostCake',
					'class' => 'alert-warning'));
	        else
				$this->Session->setFlash(__('ドキュメントがアップロードされた'), 'alert', array(
						'plugin' => 'BoostCake',
						'class' => 'alert-success'));

 			$this->redirect(array('controller' => 'lesson', 'action' => 'doc', 'id' => $lesson_id));	
		}		
	}



	public function edit($id='') {

		$old_file = $this->Document->findById($id);
		$old_file = $old_file['Document'];
		$list = array('video/mp4','audio/mpeg','audio/x-wav','image/jpeg','image/gif','image/png','application/pdf');
		$error = array();
		if ($this->request->is('put')) {
			$Document = $this->request->data['Document'];
			$Document['link']['name'] = str_replace(' ', '', $Document['link']['name']);
			$name = uniqid() . $Document['link']['name'];
			$data['Document']['link'] =  $name;
			if (is_uploaded_file($Document['link']['tmp_name'])) {
				$data['Document']['id'] = $old_file['id'];
				$data['Document']['title'] = $Document['title'];					
				$data['Document']['lesson_id'] = $old_file['lesson_id'];
				if($old_file['baned'] == 1)
					$data['Document']['baned'] = 2;
				$this->Document->create();
				if(!in_array(mime_content_type($Document['link']['tmp_name']),$list)) {
					array_push($error, $Document['link']['name']);
				}else if ($this->Document->save($data)) {
					move_uploaded_file($Document['link']['tmp_name'], WWW_ROOT . "files". DS . $name);
					unlink(WWW_ROOT . 'files' .DS . $old_file['link']);
				}
				else {
					array_push($error, $Document['link']['name']);
                }
			} 
	        if(sizeof($error)!=0)
	        	$this->Session->setFlash(__('ドキュメントをアップロードできない、もう一度お願い '.implode(",", $error)), 'alert', array(
	 				'plugin' => 'BoostCake',
					'class' => 'alert-warning'));
	        else
				$this->Session->setFlash(__('ドキュメントがアップロードされた'), 'alert', array(
						'plugin' => 'BoostCake',
						'class' => 'alert-success'));

 			$this->redirect(array('controller' => 'lesson', 'action' => 'doc', 'id' => $old_file['lesson_id']));
		} else {
			$this->request->data = $this->Document->findById($id);
		}
	}


	public function delete() 
	{
        $id = $this->params['named']['id'];
        $data = $this->Document->findById($id);
        $name = $data['Document']['link'];	
        if ($this->Document->delete($id)) 
        {
            unlink(WWW_ROOT . 'files' . DS . $name);
            $this->Session->setFlash(__('ドキュメントが削除された'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            )); 

            return $this->redirect($this->referer());      	
        }
    }

    public function show($document_id){
    	/* Se co cac truong hop nhu sau:
			-Admin luon luon xem dc file
			-Giao vien xem duoc file khi khong bi cam
		*/
		//Get doc info
    	$doc  = $this->Document->findById($document_id);
    	if(sizeof($doc) == 0){
			$this->Session->setFlash(__('すみません、いまそのドキュメントをアクセスできない'), 'alert', array(
	                'plugin' => 'BoostCake',
	                'class' => 'alert-danger'
	            ));
			$this->redirect($this->referer());
    	}
    	$lesson_id = $doc ["Document"]['lesson_id'];
    	//get lesson info
    	$lesson = $this->Lesson->findById($lesson_id);
    	$lecturer_id = $lesson['Lecturer']['id'];
    	//get current useruser
    	$user = $this->Auth->user();
    	switch ($user['role']) {
    		case 'student':
    			if ($doc["Document"]['baned'] == 1) {
    				$this->Session->setFlash(__('すみません、このドキュメントは管理者に禁止された。コピーライトに関するの問題だ'), 'alert', array(
		                'plugin' => 'BoostCake',
		                'class' => 'alert-danger'
		            ));
		            $this->redirect($this->referer());
    			}
    			$status = $this->Util->checkLessonAvailableWithStudent($lesson_id,$user['id']);
    			switch ($status) {
    				case UNREGISTER:
    				    $this->Session->setFlash(__('その授業はまだ登録しないかた'), 'alert', array(
			                'plugin' => 'BoostCake',
			                'class' => 'alert-warning'
		           		 )); 
			            $this->redirect($this->referer());
    					break;
    				case BANED:
    				    $this->Session->setFlash(__('あなたは教師が禁止されました'), 'alert', array(
			                'plugin' => 'BoostCake',
			                'class' => 'alert-warning'
		           		 )); 
    					$this->redirect($this->referer());
    					break;
    				case LEARNABLE:
    					break;
    				case OVER_DAY:
    				    $this->Session->setFlash(__('その授業は終わりました、もう一度登録してください'), 'alert', array(
			                'plugin' => 'BoostCake',
			                'class' => 'alert-warning'
		           		 )); 
    					$this->redirect($this->referer());
    					break;
    			}
    			break;
    		case 'lecturer':
    			if ($doc["Document"]['baned'] == 1) {
    				$this->Session->setFlash(__('すみません、このドキュメントは管理者に禁止された。コピーライトに関するの問題だ'), 'alert', array(
		                'plugin' => 'BoostCake',
		                'class' => 'alert-danger'
		            )); 
		            $this->redirect($this->referer());

    			}
    			if ($user['id'] != $lecturer_id) {
		            $this->redirect(array('controller' => 'users', 'action' => 'permission'));
    			}
    			break;
    		case 'admin':
    			# code...
    			break;
    		default:
    			# code...
    			break;
    	}
        $this->set("document", $doc['Document']);
    }

    public function report( $document_id){

        $document = $this->Document->findById($document_id);
        $lesson_id = $document['Document']['lesson_id'];
        $this->set('document', $document);
        $this->loadModel("Report");
        $this->loadModel("Violate");
        if ($this->Violate->hasAny(array('student_id'=>$this->Auth->user('id'), 'document_id'=>$document_id ) ) ) {
        	 $this->set('message', 'すみません、レポートは一回だけです');
        }
        if ($this->Report->hasAny(array('document_id'=> $document_id, 'state'=>TEACHER_UNFIX))){
            $this->set('message', 'システムはこのファイルがコピーライトに違反したことが分かりました');
        }
        if ($this->request->is('post')){
            $content = $this->data['report']['content'];
            $this->loadModel("Violate");
            $data = array("student_id"=>$this->Auth->user("id"), "document_id"=>$document_id, "content"=>$content); 
            $this->Violate->save($data);
            $this->redirect(array("controller"=>"lesson","action"=>"learn",$lesson_id));  
        } 

    }
}
