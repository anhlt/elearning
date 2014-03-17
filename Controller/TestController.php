<?php
class TestController extends AppController {
	var $name = 'Test';
	var $uses = array('Test');

	public function add() {
		$lesson_id = $this->params['named']['id'];
		$this->set('id', $lesson_id);

		if ($this->request->is('post'))
		{				
			$data = $this->request->data['Test'];
			var_dump($this->request->data);
			
			if (is_uploaded_file($data['link']['tmp_name'])) {
				$name = $data['link']['name'];
				move_uploaded_file($data['link']['tmp_name'], WWW_ROOT."course/test".DS.$name);
				$this->request->data['Test']['link'] = $name;							
			} 

			$this->Test->create();
			$this->request->data['Test']['lesson_id'] = $lesson_id;

			if($this->Test->save($this->request->data['Test'])){
                $this->Session->setFlash(__('The test file has been uploaded'), 'alert', array(
	                'plugin' => 'BoostCake',
	                'class' => 'alert-success'
            	));
            	
				//return $this->redirect(array('controller' => 'test', 'action' => 'add','id'=>$lesson_id));
			} else {
                $this->Session->setFlash(__('The testfile could not be uploaded. Plz try again'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
            	));	
			}
		}
	}

	public function edit() {
		$test_id = $this->params['named']['id'];
		var_dump($test_id);

		$this->set("id", $test_id);

		$results = $this->Test->find("first", $test_id);
		$this->set('result', $results['Test']);

		var_dump($results);
	}

	public function delete() {
    	$id = $this->params['named']['id'];
    	if ($this->Test->delete($id)) {
            $this->Session->setFlash(__('The test has been deleted'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            )); 
            
            return $this->redirect($this->referer());      	
    	}
    }
}
?>