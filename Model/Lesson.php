<?php 
class Lesson extends AppModel {

	public $belongsTo=array(
		'Lecturer'=>array(
			'className'=>'Lecturer',
			'foreignKey'=>'lecturer_id')
		);
        public $actsAs = array('Containable');
	public $hasAndBelongsToMany = array(
        'Tag' => array(
            'className' => 'Tag',
            'joinTable' => 'lessons_tags',
            'foreignKey' => 'lesson_id',
            'associationForeignKey' => 'tag_id'
        ), 
        'Student'=> array(
            'className' => 'Tag', 
            'joinTable' => 'students_lessons', 
            'foreignKey' => 'lesson_id',
            'associationForeignKey' => 'student_id'
        ));
    public $hasMany = array(
                'LessonMembership' => array('dependent' => True ),
                'Document' => array('dependent' => True ), 
                'Comment'=> array('dependent' => True ), 
                'Test'=> array('dependent' => True )
    		);
    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A name is required'
            )
        ),
        'summary' => array(
            'lenght' => array(
                'rule'    => array('maxLength', '2000'),
                'message' => 'Maximum 2000 characters long'
            ),   
        )
    );

}

