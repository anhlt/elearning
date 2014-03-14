<?php 
class Tag extends AppModel {
        public $hasAndBelongsToMany  = array(
		"Lesson"=>array(
			'className'=>'Lesson', 
			'joinTable'=>'lessons_tags', 
			'foreignKey'=>'tag_id',
			'associationForeignKey'=>'lesson_id'
	));
	public $validate = array(
        'name' => array(
            'lenght' => array(
                'rule'    => array('minLength', '2'),
                'message' => 'Minimum 2 characters long'
            ),
        )
    );
}
