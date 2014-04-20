<?php
class UtilComponent extends Component{
    var $uses = array('StudentsLesson');
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
}
