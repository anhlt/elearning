<?php
class Message extends AppModel {
	public $belongsTo = array(
	        'Sender' => array(
	            'className' => 'User',
	            'foreignKey' => 'user_id'
	        ),
	        'Recipient' => array(
	            'className' => 'User',
	            'foreignKey' => 'recipient_id'
		    )
	    );
    public $validate = array(
        'content' => array(
            'alphaNumeric' => array(
                'rule'     => 'alphaNumeric',
                'required' => true,
                'message'  => 'Alphabets and numbers only'
            ),
            'maxLength' => array(
		        'rule'    => array('maxLength', 150),
		        'message' => 'Usernames must be no larger than 150 characters long.'
            )
        ),
	    'type' => array(
	         'allowedChoice' => array(
	             'rule'    => array('inList', array('Block', 'Delete','Unblock')),
	             'message' => 'Enter either Warning or Delete.'
	         )
	    ),
	    'object_type' => array(
	         'allowedChoice' => array(
	             'rule'    => array('inList', array('Document', 'Lesson')),
	             'message' => 'Enter either Document or Lesson.'
	         )
	    ),
	    'user_id' => array(    
	    	'rule' => 'notEmpty',
    		'required' => true
	    ),
	    'recipient_id' => array(    
	    	'rule' => 'notEmpty',
    		'required' => true
	    ),
    );

}