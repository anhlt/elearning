<?php
class Test extends AppModel {    
    public $belongsTo = array('Lesson');
	public $validate = array(
		'title' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Title is required'
			)
		),
		'link' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'File upload is required'
			)
		),
		'test_time' => array(
			'required' => array(
				'rule'    => 'numeric',
				'message' => 'Require a number'
			)
		),		
	);
}
?>
