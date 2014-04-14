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

		if ($this->request->is('post')) 
		{

		if ($this->request->is('post')) {

			$data = $this->request->data['Document'];
			unset($data['check']);
			//echo "<pre>";

			foreach ($data as $Document) 
			{				
				$name = uniqid() . $Document['link']['name'];			
				$data['Document']['link'] =  $name;

				if (is_uploaded_file($Document['link']['tmp_name'])) {
					$data['Document']['title'] = $Document['title'];
<<<<<<< HEAD
					move_uploaded_file($Document['link']['tmp_name'], WWW_ROOT."course".DS.$name);
          $data['Document']['link'] =  DS . "course" . DS .$name;
=======
					move_uploaded_file($Document['link']['tmp_name'], WWW_ROOT."files".DS.$name);
>>>>>>> cef4a777e7686fad76b1e92ae3628c77ea682ccd
					$data['Document']['lesson_id'] = $lesson_id;
					$this->Document->create();
					if ($this->Document->save($data)) {
						$this->Session->setFlash(__('The document has been uploaded'), 'alert', array(
							'plugin' => 'BoostCake',
							'class' => 'alert-success'));
					};
				}
				else
	                $this->Session->setFlash(__('The document could not be uploaded. Plz try again'), 'alert', array(
	                    'plugin' => 'BoostCake',
	                    'class' => 'alert-warning'));      
	        	}
 				$this->redirect(array('controller' => 'lesson', 'action' => 'doc', 'id' => $lesson_id));	
			}
		}
	}

	public function edit() {
		$id = $this->params['named']['id'];
		$document_id = $this->params['named']['document_id'];		
		$this->set('id', $id);
		$this->set('document_id', $document_id);
		$results = $this->Document->find("first", array("conditions"=>array('id'=>$document_id)));		
		$this->set('result', $results['Document']);

		$ihan = false;
		if($this->params['named']['ihan']) {
			$ihan = $this->params['named']['ihan'];						
		}
		$this->set('ihan', $ihan);		

		if ($this->request->is('post'))
		{				
			$data = $this->request->data['Document'];
			//debug($data);
			$this->request->data['Document']['id'] = $document_id;

			if (is_uploaded_file($data['link']['tmp_name'])) {
				$name = $data['link']['name'];
				unlink(WWW_ROOT.DS.$results['Document']['link']);
				move_uploaded_file($data['link']['tmp_name'], WWW_ROOT."course/".$name);
				$this->request->data['Document']['link'] = "course/".$name;										
			} else {
				$results = $this->Document->find("first", array("conditions"=>array('id'=>$document_id)));
				$this->request->data['Document']['link'] = $results['Document']['link'];							
			}			

			if($this->Document->save($this->request->data['Document'])){
				$ihan = $data['ihan'];				

				if($ihan) {					
					$report = $this->Report->find("first", array('conditions' => array('document_id' => $document_id)));
					$report['Report']['state'] = 0;
					$this->Report->create();
			    	$this->Report->save($report);			    	
				}
				$this->set('ihan', $ihan);				

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

			if($ihan)
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
        $lesson_id = $document['Lesson']['id']; 
        if ($this->Util->checkLessonAvailableWithStudent($lesson_id, $this->Auth->user("id"))){
            $this->set("learnable", 1);
        }else {
            $this->set("learnable", -1);
        }
    }

    public function report($lesson_id,  $document_id){
        $document = $this->Document->find("first", array("conditions"=>array("id"=>$document_id)));
        $this->set("document", $document['Document']);
        if ($this->request->is('post')){
            $content = $this->data['report']['content'];
            $this->loadModel("Violate");
            $data = array("student_id"=>$this->Auth->user("id"), "document_id"=>"document_id", "content"=>$content, "accepted"=>0); 
            $this->Violate->save($data);
            $this->redirect(array("controller"=>"lesson","action"=>"learn",$lesson_id));  
        }

    	$id = $this->params['named']['id'];
    	if ($this->Document->delete($id)) {
            $this->Session->setFlash(__('The document has been deleted'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            )); 

            return $this->redirect($this->referer());      	
    	}
    }
}
