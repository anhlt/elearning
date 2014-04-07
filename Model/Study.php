<?php
class Study extends AppModel {
	public $useTable = 'study';
	
	public $belongsTo = array(
        'Student', 'Lesson'
    );
}