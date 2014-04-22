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
            'className' => 'Student', 
            'joinTable' => 'students_lessons', 
            'foreignKey' => 'lesson_id',
            'associationForeignKey' => 'student_id'
        ));
    public $hasMany = array(
                'LessonMembership' => array('dependent' => True ),
                'LessonsTag' => array('dependent' => True ),
                'Document' => array('dependent' => True ), 
                'Comment'=> array('dependent' => True ), 
                'Test'=> array('dependent' => True )
    		);
    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'ユーザ名を入力してお願い'
            )
        ),
        'summary' => array(
            'lenght' => array(
                'rule'    => array('maxLength', '2000'),
                'message' => '最大限は2000つのキャラクタだ'
            ),   
        )
    );

}

