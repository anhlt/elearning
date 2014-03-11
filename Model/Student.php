<?php
class Student extends AppModel{
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'id'
        ),        
    );    

    public $hasMany = array(
        'Study', 'Comment'
    );

    public $validate = array(
        'full_name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A username is required'
            )
        ),
        'init_password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A password is required'
            )
        ),
        'init_verificode' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A verificode is required'
            )
        ),
        'question_verifycode_id' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A verificode is required'
            )
        ),
        'current_verifycode' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A verificode is required'
            )
        ),
        'date_of_birth' =>array(        	
            'date' => array(
                'rule'       => 'date',
                'message'    => 'Enter a valid date',
                'allowEmpty' => true
            ),
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A password is required'
            )
        ),
        'email' => array(
            'email' => array(
                'rule'    => array('email', true),
                'message' => 'Please supply a valid email address.'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This Username has already been used.'
            )
        ),
        'ip_address' =>  array(
            'ip_address' => array(
                'rule'    => array('ip', 'IPv4'), // or 'IPv6' or 'both' (default)
                'message' => 'Please supply a valid IP address.'
            )
        ),
        'address' => array(
            'rule'    => array('maxLength', 1005),
        ),
    );

}
?>
