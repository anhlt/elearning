<?php 
class Tag extends AppModel {
       public $hasAndBelongsToMany  = array(
		"Lesson"=>array(
			'className'=>'Lesson', 
			'joinTable'=>'lessons_tags', 
			'foreignKey'=>'tag_id',
			'associationForeignKey'=>'lesson_id'
	));
	// public $validate = array(
 //        'name' => array(
 //            'required' => array(
 //                'rule' => array('notEmpty'),
 //                'message' => '入力してお願い'
 //            )
 //        ),
 //    );
}
