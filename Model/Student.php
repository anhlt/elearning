<?php
class Student extends AppModel{
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'id'
        ),        
    );    
    public $hasMany = array(
        'LessonMembership',
        'Result' => array('dependent' => True ));
    
    public $hasAndBelongsToMany = array( 
        'Lesson'=>array(
            'joinTable'=> 'students_lessons', 
            'foreignKey'=> 'student_id', 
            'associationForeignKey'=> 'lesson_id')
    );

    public $validate = array(
        'full_name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => '氏名を入力してお願い'
            ), 
            'rule'    => array('maxLength', 256)
        ),
        'init_password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'パスワードを入力してお願い'
            )
        ),
        'init_verificode' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'verificodeを入力してお願い'
            )
        ),
        'question_verifycode_id' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'verificodeを入力してお願い'
            )
        ),
        'current_verifycode' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'verificodeを入力してお願い'
            )
        ),
        'date_of_birth' =>array(        	
            'date' => array(
                'rule'       => 'date',
                'message'    => '生年月日を入力してお願い',
                'allowEmpty' => true
            ),
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'パスワードを入力してお願い'
            )
        ),
        'email' => array(
            'email' => array(
                'rule'    => array('email', true),
                'message' => 'メールのフォーマットが正しくない'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'このメールが存在した'
            )
        ),
        'ip_address' =>  array(
            'ip_address' => array(
                'rule'    => array('ip', 'both'), // or 'IPv6' or 'both' (default)
                'message' => 'IPアドレスのフォーマットが正しくない'
            )
        ),
        'address' => array(
            'rule'    => array('maxLength', 256),
        ),
        'credit_card_number'=> array(
           'alphaNumeric' => array(
                'rule'     => 'alphaNumeric',
                'required' => true,
                'message'  => '数字'
            ),
           'between' => array(
                'rule'    => array('between', 24, 24),
                'message' => '24字しなけらばならない'
            )
        ),
        'phone_number'=>array(
            'alphaNumeric' => array(
                'rule'     => 'alphaNumeric',
                'required' => true,
                'message'  => '数字'
            ),
            'rule'    => array('maxLength', 256),
        )


    );
}
?>
