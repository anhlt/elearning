<?php
class DocumentController extends AppController {
	var $name = "Document";
	public $components = array('RequestHandler','Paginator','Util');
	var $uses = array('User', 'Lecturer','Question','Lesson','Tag', 'Document', 'Test', 'LessonMembership', 'Message');
	public $helpers = array("TsvReader");
	public function add() {
		$lesson_id = $this->params['named']['id'];
		$this->set('id', $lesson_id);
		$a['Document']['lesson_id'] = $lesson_id;

		if ($this->request->is('post')) {

			$data = $this->request->data['Document'];
			unset($data['check']);

			$upload_invalid = true;
			foreach ($data as $Document) {
				if(!is_uploaded_file($Document['link']['tmp_name'])) {
					$upload_invalid = false;					
					$this->Session->setFlash(__('ドキュメントをアップロードできない、もう一度お願い'), 'alert', array(
          	 			'plugin' => 'BoostCake',
            			'class' => 'alert-warning'));
					break;
				}
			}

			if($upload_invalid)
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
	                	$this->Session->setFlash(__('ドキュメントを保存できない、もう一度お願い'), 'alert', array(
          	 				'plugin' => 'BoostCake',
            				'class' => 'alert-warning'));
	                }
				}
	        }

	        if($upload_invalid)
 				$this->redirect(array('controller' => 'lesson', 'action' => 'doc', 'id' => $lesson_id));	
		}		
	}

	public function edit() {		
		$document_id = $this->params['named']['id'];		
		$this->set('document_id', $document_id);
		$results = $this->Document->find("first", array("conditions"=>array('id'=>$document_id)));		
		$this->set('result', $results['Document']);		
		
		$ihan = $this->params['named']['ihan'];	
		$this->set('ihan', $ihan);

		if ($this->request->is('post'))
		{				
			$data = $this->request->data['Document'];
			$this->request->data['Document']['id'] = $document_id;
			
			$uploaded = 0;			

			if($data['link']['name'] != '') {
				$uploaded = 1;
				if (is_uploaded_file($data['link']['tmp_name'])) {							
					$name = uniqid() . $data['link']['name'];				
					$this->request->data['Document']['link'] = $name;										
				} else {
					$uploaded = 2;
					$this->Session->setFlash(__('ドキュメントをアップロードできない、もう一度お願い'), 'alert', array(
	                    'plugin' => 'BoostCake',
	                    'class' => 'alert-warning'
	            	));
				}
			}
			else {
				$this->request->data['Document']['link'] = $results['Document']['link'];							
			}			
			
			if($uploaded != 2) {
				if($this->Document->save($this->request->data['Document']))
				{	
					if($uploaded == 1) {			 
						unlink(WWW_ROOT . 'files' . DS . $results['Document']['link']);
						move_uploaded_file($data['link']['tmp_name'], WWW_ROOT. 'files' . DS . $name);
					}			

					if($ihan == 'true') {				    	
				    	$sql = "UPDATE documents SET baned = '2' WHERE id = '$document_id'";
				    	$this->Document->query($sql);							    	
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
				
				$docs = $this->Document->find("first", array("conditions"=>array('id'=>$document_id))); 
				debug($docs['Document']['lesson_id']);
				$this->redirect(array('controller' => 'lesson', 'action' => 'doc', 'id' => $docs['Document']['lesson_id']));					
			}			
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
    	$doc  = $this->Document->findById($document_id);
    	$lesson = $this->Lesson->findById($doc ["Document"]['lesson_id']);
    	$lecturer_id = $lesson['Lecturer']['id'];
    	if($this->Auth->user('role') == 'lecturer' && $this->Auth->user('id') != $lecturer_id)
            $this->redirect(array('controller' => 'users', 'action' => 'permission'));
        $document = $this->Document->find("first", array("conditions"=>array("Document.id"=>$document_id)));
        $this->set("document", $document['Document']);
        $lesson_id = $document['Document']['lesson_id']; 
        $this->set("learnable", $this->Util->checkLessonAvailableWithStudent($lesson_id, $this->Auth->user("id")));
        if ($this->Auth->user('role')=='lecturer'){
			$this->set("learnable", LEARNABLE);
		}
    }

    public function report( $document_id){

        $document = $this->Document->findById($document_id);
        $lesson_id = $document['Document']['lesson_id'];
        $this->set('document', $document);
        $this->loadModel("Report");
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