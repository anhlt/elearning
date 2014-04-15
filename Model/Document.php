<?php
class Document extends AppModel {
	public $name = 'document';
	
	public $validate = array(
		'title' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Title is required'
			)
		),		
		'link' => array(
			'required' => array(
				"rule" => "notEmpty",
				"message" => "Please enter email !",
			),
			'extension' => array(
				"rule" => array(
	            	'extension', array('pdf', 'mp4', 'jpg')
	        		),
				"message" => "Please supply a valid file !",
			),
		)
	);
}
?>
