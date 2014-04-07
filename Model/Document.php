<?php
class Document extends AppModel {
//	public $name = 'document';
//	public $hasMany = 'Document';
	public $belongsTo = array(
		'Lesson'=>array(
			'className'=>'Lesson', 
			'foreignKey'=>'lesson_id'
		));	
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
				'message' => 'Title is required'
			)
		),
		'cb' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Title is required'
			)
		),			
	);
}
?>
