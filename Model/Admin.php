<?php

/**
 * Description of ManagerModel
 *
 * @author Tha
 */
class Admin extends AppModel {

    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'id'
    ));
    public $hasMany = array(
        'IpAdmin' => array('dependent' => True));
    public $validate = array(
        'username' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A username is required'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A password is required'
            )
        ),
        'repassword' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A password is required'
            )
        ),
        'email' => array(
            'email' => array(
                'rule' => array('email'),
                'message' => 'Please supply a valid email address.'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This Username has already been used.'
            )
        ),
        'ip_address' => array(
            'ip_address' => array(
                'rule' => array('ip', 'IPv4'), // or 'IPv6' or 'both' (default)
                'message' => 'Please supply a valid IP address.'
            )
        ),
    );

}

?>
