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
                'message' => 'ユーザ名を入力してお願い'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'パスワードを入力してお願い'
            )
        ),
        'repassword' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => '再パスワードを入力してお願い'
            )
        ),
        'email' => array(
            'email' => array(
                'rule' => array('email'),
                'message' => 'メールを入力してお願い'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'このメールが存在した'
            )
        ),
        'ip_address' => array(
            'ip_address' => array(
                'rule' => array('ip', 'IPv4'), // or 'IPv6' or 'both' (default)
                'message' => 'IPアドレスを入力してお願い'
            )
        ),
    );

}

?>
