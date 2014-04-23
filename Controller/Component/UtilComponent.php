<?php
class UtilComponent extends Component{
    var $uses = array('StudentsLesson','Document');
    public function checkLessonAvailableWithStudent($lesson_id, $student_id){
        $this->StudentsLesson = ClassRegistry::init("StudentsLesson");
        $options['conditions'] = array("student_id"=>$student_id, "lesson_id"=>$lesson_id);
        $options['order'] = array("days_attended desc");
        $res =  $this->StudentsLesson->find("first", $options);
        if (count($res)==0){
            return UNREGISTER; 
        }else {
            $day_attended = $res['StudentsLesson']['days_attended'];
            $now = date("Y-m-d H:i:s");
            $days = floor((strtotime($now)- strtotime($day_attended))/24/3600);
            if ($days > MAX_LEARN_DAY){ 
                return OVER_DAY;
            }else {
                if ($res['StudentsLesson']['baned']==1)
                    return BANED; 
            }
        }
        return LEARNABLE;
    }
    public function violate($id)
    {
        $this->Document = ClassRegistry::init("Document");
        $Document = $this->Document->find('all',array(
            'joins' => array(
                    array(
                        'table' => 'violates',
                        'alias' => 'Violate',
                        'type' => 'LEFT',
                        'conditions' => array(
                            'Document.id = Violate.document_id'
                        )
                    ),
                    array(
                        'table' => 'lessons',
                        'type' => 'LEFT',
                        'conditions' => array(
                            'Document.lesson_id = lessons.id'
                        )
                    ),

                ),
            'conditions' => array(
                'lessons.lecturer_id' => $id,
                ),
            'group' => 'Document.id',
            ));
        return sizeof($Document);    
    }
    public function checkUserLogin($user_id){
        $this->User = ClassRegistry::init("User");
        $res = $this->User->findById($user_id);
        $user = $res['User'];
        $login_time = $user['login_time'];
        $now = date("Y-m-d H:i:s");
        $hours = floor((strtotime($now)- strtotime($login_time)));
        if ($hours> SESSION_TIME) {
            return false;
        }else {
            return true; 
        }
    }
}
