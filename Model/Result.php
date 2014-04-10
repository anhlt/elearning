<?php
class Result extends AppModel {
    public $belongsTo = array(
        'Student'=>array(
            'className'=>'Student', 
            'foreignKey'=>'student_id'
        ), 
        'Test'=>array(
            'className'=>'Test', 
            'foreignKey'=>'test_id'
        ));
}
?>
