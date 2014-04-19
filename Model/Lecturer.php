<?php 
class Lecturer extends AppModel {
	public $belongsTo = array(
		'User' => array(
		    'className' => 'User',
		    'foreignKey' => 'id'
		)
	);
	public $hasMany = array(
		'Lesson'=>array(
           		 'className' => 'Lesson',
         		 'foreignKey' => 'lecturer_id',
        		 'dependent' => true

            ),
		); 
	public $validate = array(
		'full_name' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'A full_name is required'
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
	        	'rule'    => array('email'),
	        	'message' => 'Please supply a valid email address.'
	    	),
			'isUnique' => array(
              'rule' => 'isUnique',
              'message' => 'This Username has already been used.'
            )
	    ),
        'ip_address' =>  array(
 			'ip_address' => array(
     	   	'rule'    => array('ip', 'both'), // or 'IPv6' or 'both' (default)
        	'message' => 'Please supply a valid IP address.'
    		)
 		),
 		'address' => array(
 			'rule'    => array('maxLength', 1005),
 		),
	);
    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['init_verificode'])) {
        	$this->data[$this->alias]['current_verifycode'] = md5($this->data[$this->alias]['current_verifycode']);
            $this->data[$this->alias]['init_verificode'] = $this->data[$this->alias]['current_verifycode'];
        }
        return true;
    }
}
