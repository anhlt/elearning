<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class  Parameter extends AppModel{
    var $validate = array(
    'value' => array(
        'format'=>array(
            'rule' => array('numeric')
            )
         )
    );

    public function getLessonCost(){
        $data = $this->query("SELECT value FROM parameters WHERE name = 'LESSON_COST'");
        return $data[0]['parameters']['value'];
    }
    public function getLecturerMoneyPercent(){
        $data = $this->query("SELECT value FROM parameters WHERE name = 'LECTURER_MONEY_PERCENT'");
        return $data[0]['parameters']['value'];
        
    }
    public function getEnableLessonTime(){
        $data = $this->query("SELECT value FROM parameters WHERE name = 'ENABLE_LESSON_TIME'");
        return $data[0]['parameters']['value'];
        
    }
    public function getWrongPasswordTimes(){
        $data = $this->query("SELECT value FROM parameters WHERE name = 'WRONG_PASSWORD_TIMES'");
        return $data[0]['parameters']['value'];
    }
    public function getLockTime(){
        $data = $this->query("SELECT value FROM parameters WHERE name = 'LOCK_TIME'");
        return $data[0]['parameters']['value'];
    }
    public function getSessionTime(){
        $data = $this->query("SELECT value FROM parameters WHERE name = 'SESSION_TIME'");
        return $data[0]['parameters']['value'];
    }
    public function getViolationsTimes(){
        $data = $this->query("SELECT value FROM parameters WHERE name = 'VIOLATIONS_TIMES'");
        return $data[0]['parameters']['value'];
    }
    public function getBackupTime(){
        $data = $this->query("SELECT value FROM parameters WHERE name = 'BACKUP_TIME'");
        return $data[0]['parameters']['value'];
    }
    public function updateParameter($name, $value){
        $return = $this->query("UPDATE parameters SET value = '$value' WHERE name = '$name'");
        return $return;
    }  
    
}
?>