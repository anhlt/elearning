<?php 
class TagController extends AppController {
  	var $uses = array('User', 'Lecturer','Question','Lesson');	
  	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow("search");
    }
    public function search($value='')
    {
		$this->layout = null;
		
    }

}