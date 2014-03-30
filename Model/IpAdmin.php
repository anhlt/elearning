<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class  IpAdmin extends AppModel{
    var $validate = array(
    'ip_address' => array(
        'format'=>array(
            'rule' => array('ip', 'IPv4'),
            'message' => 'IPアドレスのフォーマットが正しくない'
        ),
        'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'IPアドレスが空しい'
        )
     )
);
}
?>