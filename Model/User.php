<?php

App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel {

    public $validate = array(
        'username' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'ユーザ名を入力してお願い'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'このユーザ名が存在した'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'パスワードを入力してお願い'
            ),
            'length' => array(
                'rule' => array('minLength', '8'),
                'message' => '最低限は８つのキャラクタだ'
            ),
        ),
        'role' => array(
            'valid' => array(
                'rule' => array('inList', array('student', 'lecturer', 'admin')),
               // 'message' => 'メールのフォーマットが正しくない',
                'allowEmpty' => false
            )
        )
    );
    public $hasOne = array(
        'Lecturer' => array(
            'className' => 'Lecturer',
            'foreignKey' => 'id',
            'dependent' => true
            ),
            'Student' => array(
                'className' => 'Student',
                'foreignKey' => 'id',
                'dependent' => true
            ),
            'Admin' => array(
                'className' => 'Admin',
                'foreignKey' => 'id',
                'dependent' => true
            )
    );
    public $hasMany = array(
        'MessageSent' => array(
            'className' => 'Message',
            'foreignKey' => 'user_id'
        ),
        'MessageReceived' => array(
            'className' => 'Message',
            'foreignKey' => 'recipient_id'
        )
    );

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        return true;
    }
}
