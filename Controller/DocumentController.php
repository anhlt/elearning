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
					move_uploaded_file($Document['link']['tmp_name'], WWW_ROOT."files".DS.$name);
					$data['Document']['lesson_id'] = $lesson_id;
					$this->Document->create();
					if ($this->Document->save($data)) {
						$this->Session->setFlash(__('The document has been uploaded'), 'alert', array(
							'plugin' => 'BoostCake',
							'class' => 'alert-success'));
					}
					else {
	                	$this->Session->setFlash(__('The document could not be uploaded. Plz try again'), 'alert', array(
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
		$results = $this->Document->find("first", array("conditions"=>array('id'=>$document_id)));		
		$this->set('result', $results['Document']);		
		
		$ihan = $this->params['named']['ihan'];	
		$this->set('ihan', $ihan);

		if ($this->request->is('post'))
		{				
			$data = $this->request->data['Document'];
			$this->request->data['Document']['id'] = $document_id;

			echo WWW_ROOT;
			if (is_uploaded_file($data['link']['tmp_name'])) {				
				unlink(WWW_ROOT . DS . 'pdf' . DS . $results['Document']['link']);
				$name = uniqid() . $data['link']['name'];
				move_uploaded_file($data['link']['tmp_name'], WWW_ROOT. 'pdf' . DS . $name);
				$this->request->data['Document']['link'] = $name;										
			} else {
				$results = $this->Document->find("first", array("conditions"=>array('id'=>$document_id)));
				$this->request->data['Document']['link'] = $results['Document']['link'];							
			}			

			if($this->Document->save($this->request->data['Document'])){	

				if($ihan == 'true') {		
					$report = $this->Report->find("first", array('conditions' => array('document_id' => $document_id)));
					$report['Report']['state'] = 0;
					$this->Report->create();
			    	$this->Report->save($report);		    	
				}
                $this->Session->setFlash(__('The document file has been update'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));		

            } else {
                $this->Session->setFlash(__('The document file could not be update. Plz try again'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
            	));	
			}
	
			if($ihan == 'true') {
				$this->redirect(array('controller' => 'lesson', 'action' => 'report', 'id' => $id));
				//echo "_True";
			}
			else {
				$this->redirect(array('controller' => 'lesson', 'action' => 'doc', 'id' => $id));
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
            unlink(WWW_ROOT.DS.$name);    		
            $this->Session->setFlash(__('The document has been deleted'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            )); 

            return $this->redirect($this->referer());      	
        }
    }

    public function show($document_id){
        $document = $this->Document->find("first", array("conditions"=>array("Document.id"=>$document_id)));
        $this->set("document", $document['Document']);
        $lesson_id = $document['Document']['lesson_id']; 
        if ($this->Util->checkLessonAvailableWithStudent($lesson_id, $this->Auth->user("id"))){
            $this->set("learnable", 1);
        }else {
            $this->set("learnable", -1);
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
