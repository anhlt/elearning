<?php
class UtilComponent extends Component{
    var $uses = array('StudentsLesson', 'Lecturer');
    public function checkLessonAvailableWithStudent($lesson_id, $student_id){  //$student_id co the la teacher
        $this->StudentsLesson = ClassRegistry::init("StudentsLesson");
        $this->Lesson = ClassRegistry::init("Lesson");
        $lesson = $this->Lesson->findById($lesson_id); 
        $lecturer_id = $lesson['Lesson']['lecturer_id'];

        if ($lecturer_id == $student_id){
            return LEARNABLE;
        }
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
