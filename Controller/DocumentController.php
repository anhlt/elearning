<?php
class DocumentController extends AppController {
	var $name = "Document";
	var $uses = array('Document','Lesson', 'Report');
	public $components = array('Util'); 
	public $helpers = array("TsvReader");
	public function add() {
		$lesson_id = $this->params['named']['id'];
		$this->set('id', $lesson_id);
		$a['Document']['lesson_id'] = $lesson_id;

		if ($this->request->is('post')) {

			$data = $this->request->data['Document'];
			unset($data['check']);
			foreach ($data as $Document)
			{				
				$name = uniqid() . $Document['link']['name'];			
				$data['Document']['link'] =  $name;
				if (is_uploaded_file($Document['link']['tmp_name'])) {
					$data['Document']['title'] = $Document['title'];					
					$data['Document']['lesson_id'] = $lesson_id;
					$this->Document->create();
					if ($this->Document->save($data)) {
						move_uploaded_file($Document['link']['tmp_name'], WWW_ROOT . "files". DS . $name);
						$this->Session->setFlash(__('ドキュメントがアップロードされた'), 'alert', array(
							'plugin' => 'BoostCake',
							'class' => 'alert-success'));
					}
					else {
	                	$this->Session->setFlash(__('ドキュメントをアップロードできない、もう一度お願い'), 'alert', array(
          	 				'plugin' => 'BoostCake',
            				'class' => 'alert-warning'));
	                }
				}     
	        }

 			$this->redirect(array('controller' => 'lesson', 'action' => 'doc', 'id' => $lesson_id));	
		}		
	}

	public function edit() {
		$id = $this->params['named']['id'];
		$document_id = $this->params['named']['document_id'];		
		$this->set('id', $id);
		$this->set('document_id', $document_id);
		$results = $this->Document->find("first", array("conditions"=>array('Document.id'=>$document_id)));		
		$this->set('result', $results['Document']);		
		
		$ihan = $this->params['named']['ihan'];	
		$this->set('ihan', $ihan);

		if ($this->request->is('post'))
		{				
			$data = $this->request->data['Document'];
			$this->request->data['Document']['id'] = $document_id;
			$uploaded = false;
			if (is_uploaded_file($data['link']['tmp_name'])) {
				$uploaded = true;			
				$name = uniqid() . $data['link']['name'];				
				$this->request->data['Document']['link'] = $name;										
			} else {
				$this->request->data['Document']['link'] = $results['Document']['link'];							
			}			

			if($this->Document->save($this->request->data['Document']))
			{	
				if($uploaded) {
					unlink(WWW_ROOT . 'files' . DS . $results['Document']['link']);
					move_uploaded_file($data['link']['tmp_name'], WWW_ROOT. 'files' . DS . $name);
				}

				if($ihan == 'true') {		
					$report = $this->Report->find("first", array('conditions' => array('document_id' => $document_id)));
					$report['Report']['state'] = 0;
					$this->Report->create();
			    	$this->Report->save($report);

			    	$doc = $this->Document->find("first", array('conditions' => array('id' => $document_id)));
					$doc['Document']['baned'] = 0;
					debug($doc);
					$this->Document->create();
			    	$this->Document->save($doc);	    	
				}
                $this->Session->setFlash(__('ドキュメントが更新された'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));		

            } else {
                $this->Session->setFlash(__('ドキュメントを更新できない、もう一度お願い'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
            	));	
			}
	
			if($ihan == 'true')
				$this->redirect(array('controller' => 'lesson', 'action' => 'report', 'id' => $id));	
			else
				$this->redirect(array('controller' => 'lesson', 'action' => 'doc', 'id' => $id));		
		}
	}

	public function delete() 
	{
        $id = $this->params['named']['id'];
        $data = $this->Document->find('first', $id);
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
        if ($this->Violate->hasAny(array('student_id'=>$this->Auth->user('id') ) ) ) {
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
