<?php 
class Lesson extends AppModel {
	public $belongsTo="Lecturer";
    public $actsAs = array('Containable');
	public $hasAndBelongsToMany = array(
        'Tag' => array(
            'className' => 'Tag',
            'joinTable' => 'lessons_tags',
            'foreignKey' => 'lesson_id',
            'associationForeignKey' => 'tag_id'
        ));
    public $hasMany = array(
        'LessonMembership' => 
            array('dependent' => True ),
        'Document'
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

