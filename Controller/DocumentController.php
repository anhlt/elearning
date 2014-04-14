<?php
class DocumentController extends AppController {
	var $name = "Document";
	var $uses = array('Document','Lesson');

	public function add() {
		$lesson_id = $this->params['named']['id'];
		$this->set('id', $lesson_id);
		$a['Document']['lesson_id'] = $lesson_id;
		if ($this->request->is('post')) {
			$data = $this->request->data['Document'];
			unset($data['check']);
			echo "<pre>";
			var_dump($data);
			foreach ($data as $Document) {
				var_dump($Document);
				$name = uniqid() . $Document['link']['name'];
				if (is_uploaded_file($Document['link']['tmp_name'])) {
					$data['Document']['title'] = $Document['title'];
					move_uploaded_file($Document['link']['tmp_name'], WWW_ROOT."course".DS.$name);
          $data['Document']['link'] =  DS . "course" . DS .$name;
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
 		$this->redirect(array('controller' => 'lecturer'));	
		}
	}

	public function upload() {
	}

	public function edit() {
		$document_id = $this->params['named']['id'];
	}

	public function delete() {
		$document_id = $this->params['named']['id'];
	}

    public $helpers = array("TsvReader");
    public function show($document_id){
        $document = $this->Document->find("first", array("conditions"=>array("id"=>$document_id)));
        $this->set("document", $document['Document']);
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
    }
}
