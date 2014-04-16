<?php
class Result extends AppModel {
    public $belongsTo = array(
        'Student'=>array(
            'className'=>'Student', 
            'foreignKey'=>'student_id',
            'dependent' => True 

        ), 
        'Test'=>array(
            'className'=>'Test', 
            'foreignKey'=>'test_id',
            'dependent' => True 
        ));
}
?>
