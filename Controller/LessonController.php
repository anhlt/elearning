<?php 
class LessonController extends AppController {
    var $name = "Lesson";
    var $uses = array('User', 'Lecturer','Question','Lesson','Tag', 'Document', 'Test', 'LessonMembership', 'Comment', 'Student', 'Report');
    public $components = array('RequestHandler', 'Paginator','Util');


    public function beforeFilter() {
        parent::beforeFilter();
        $this->Session->delete("scroll");
    }

    public function add($value='')
    {
        if($this->request->is('post')) {
            $data = ($this->request->data);
            $data['Lesson']['lecturer_id'] = $this->Auth->user('id');
            $rawtags = explode(",",$data["hidden-data"]['Tag']['name']);
            $tags = array();
            foreach ($rawtags as $key => $value) {
                var_dump($value);
                $tag = $this->Tag->findByName($value);
                if (!$tag) {
                    $this->Tag->create();
                    $this->Tag->set("name",$value);
                    $tag = $this->Tag->save();
                }
                array_push($tags, $tag['Tag']['id']);
            }
            $data['Tag'] = $tags;
            $this->Lesson->create();
            if($this->Lesson->saveAll($data)){
                $id = $this->Lesson->getInsertID();
                $this->Session->setFlash(__('授業がセーブされた'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('controller' => 'Document', 'action' => 'add','id' =>  $id));
            }
            else{
                $this->Session->setFlash(__('授業をセーブできない、もう一度お願い'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                ));
                return $this->redirect(array('controller' => 'lecturer', 'action' => 'lesson'));

            }
        }
    }

    public function edit(){
        if (empty($this->request->data)) {
            $id = ($this->params['named']['id']);
            $Lesson = $this->Lesson->findById($id);
            $this->request->data = $Lesson;
            $this->set('Tags',$Lesson['Tag']);
        }
        else{
            $data = ($this->request->data);
            $rawtags = explode(",",$data["hidden-data"]['Tag']['name']);
            $tags = array();
            foreach ($rawtags as $key => $value) {
                $tag = $this->Tag->findByName($value);
                if (!$tag) {
                    $this->Tag->create();
                    $this->Tag->set("name",$value);
                    $tag = $this->Tag->save();
                }
                array_push($tags, $tag['Tag']['id']);
            }
            $data['Tag'] = $tags;
            if($this->Lesson->save($data)) {
                $this->Session->setFlash(__('授業の情報がセーブされた'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
            }
            else{
                $this->Session->setFlash(__('授業の情報をセーブできない、もう一度お願い'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                ));	
            }			
            $this->redirect(array('controller' => 'lecturer'));
        }
    }

    public function doc()
    {			
        $user = $this->Auth->user();
        $lesson_id = $this->params['named']['id'];
        $this->Session->write('lesson_id', $lesson_id);

        $this->set("id", $lesson_id);			
        if($user["role"] == 'lecturer') {
            $lesson_id = $this->params['named']['id'];
            $sql = array("conditions"=> array("Lesson.id =" => $lesson_id, "Lesson.lecturer_id =" => $user['id']));
            $result = $this->Lesson->find('first',$sql);

            if($result != NULL) {
                $this->paginate = array(
                    'fields' => array('Document.id', 'Document.link', 'Document.title'),
                    'limit' => 10,
                    'conditions' => array(
                        'Document.lesson_id' => $lesson_id
                    )
                );

                $this->Paginator->settings = $this->paginate;
                $data = $this->Paginator->paginate("Document");
                $this->set('results', $data);				
            } else {
                $this->redirect(array('controller' => 'users' ,"action" => "permission" ));
            }
        } else {
            $this->redirect(array('controller' => 'users' ,"action" => "permission" ));
        }		
    }

    public function test() {
        $lesson_id = $this->Session->read('lesson_id');
        $this->set("id", $lesson_id);
        $user = $this->Auth->user();

        if($user["role"] == 'lecturer') {			
            $sql = array("conditions"=> array("Lesson.id =" => $lesson_id, "Lesson.lecturer_id =" => $user['id']));
            $result = $this->Lesson->find('first', $sql);

            if($result != NULL) {
                $this->paginate = array(
                    'fields' => array('Test.id', 'Test.title', 'Test.test_time','Test.link'),
                    'limit' => 10,
                    'conditions' => array(
                        'Test.lesson_id' => $lesson_id
                    )
                );

                $this->Paginator->settings = $this->paginate;
                $data = $this->Paginator->paginate("Test");
                $this->set('results', $data);
            } else {
                $this->redirect(array('controller' => 'users' ,"action" => "permission" ));
            }
        } else {
            $this->redirect(array('controller' => 'users' ,"action" => "permission" ));
        }		
    }

    public function bill() {
        $lesson_id = $this->Session->read('lesson_id');
        $this->set("id", $lesson_id);		
        $user = $this->Auth->user();

        if($user["role"] == 'lecturer') {
            $sql = array("conditions"=> array("Lesson.id =" => $lesson_id, "lecturer_id =" => $user['id']));
            $result = $this->Lesson->find('first', $sql);
            //var_dump($result);

            if($result != NULL) {				
                $this->paginate = array(
                    'conditions' => array(
                        'LessonMembership.lesson_id' => $lesson_id)
                    );
                $this->LessonMembership->Behaviors->load('Containable');
                $students = $this->Paginator->paginate("LessonMembership");
                $this->set("results", $students);

                $this->loadModel("Parameter");
                $this->set("lesson_time",$this->Parameter->getEnableLessonTime()); 

            } else {
                $this->redirect(array('controller' => 'users' ,"action" => "permission" ));
            }
        } else {
            $this->redirect(array('controller' => 'users' ,"action" => "permission" ));
        }
    }

    public function student() {
        $lesson_id = $this->Session->read('lesson_id');
        $this->set("id", $lesson_id);		
        $user = $this->Auth->user();

        if($user["role"] == 'lecturer') {
            $sql = array("conditions"=> array("Lesson.id =" => $lesson_id, "lecturer_id =" => $user['id']));
            $result = $this->Lesson->find('first', $sql);
            //var_dump($result);

            if($result != NULL) {				
                $this->paginate = array(
                    'conditions' => array(
                        'LessonMembership.lesson_id' => $lesson_id)
                    );

                $this->LessonMembership->Behaviors->load('Containable');
                $students = $this->Paginator->paginate("LessonMembership");
                $this->set("results", $students);
            } else {
                $this->redirect(array('controller' => 'users' ,"action" => "permission" ));
            }
        } else {
            $this->redirect(array('controller' => 'users' ,"action" => "permission" ));
        }
    }

    public function summary() {
        $lesson_id = $this->Session->read('lesson_id');		
        $this->set("id", $lesson_id);
        $user = $this->Auth->user();

        if($user["role"] == 'lecturer') {
            $sql = array("conditions"=> array("Lesson.id =" => $lesson_id, "lecturer_id =" => $user['id']));
            $results = $this->Lesson->find('first', $sql);

            if($results != NULL) {
                $sql = array(
                    'fields' => array('LessonMembership.liked'),
                    'conditions' => array(
                        'LessonMembership.lesson_id' => $lesson_id)
                    );

                $results = $this->LessonMembership->find('all', $sql);
                $row = count($results);
                $like = 0;
                foreach ($results as $result) {									
                    if($result['LessonMembership']['liked']) {						
                        $like++;
                    }
                }		

                //echo $like . ' & ' . $row;
                $this->set('row', $row);
                if($row) {
                    $this->set('like', ceil(($like/$row) * 100));
                } else {
                    $this->set('like', 0);
                }
            } else {
                $this->redirect(array('controller' => 'users' ,"action" => "permission" ));
            }
        } else {
            $this->redirect(array('controller' => 'users' ,"action" => "permission" ));
        }		
    }

    public function report() {
        $lesson_id = $this->Session->read('lesson_id');		
        $this->set("id", $lesson_id);
        $user = $this->Auth->user();	

        if($user["role"] == 'lecturer') {
            $sql = array("conditions"=> array("Lesson.id =" => $lesson_id, "lecturer_id =" => $user['id']));
            $results = $this->Lesson->find('first', $sql);

            if($results != NULL) {				
                $results = $this->Document->find('all', array('conditions'=> array('Document.lesson_id' => $lesson_id)));
                //var_dump($results);
                $reports = array();
                foreach ($results as $result) {
                    $docs = null;
                    $link = $result['Document']['link'];
                    $sql = array('conditions' => array('Report.document_id' => $result['Document']['id'], 'Report.state' => 1));
                    $docs = $this->Report->find('first', $sql);

                    if($docs != NULL) {
                        $x['Document']['id'] = $result['Document']['id'];
                        $x['Document']['title'] = $result['Document']['title'];						
                        array_push($reports, $x);
                    }	    					
                }	
                $this->set('reports', $reports);

                $bans = $this->Document->find('all', array('conditions'=> array('Document.baned' => 1)));
                $this->set('bans', $bans);               

                $sql = array(
                    'fields' => array('Comment.id', 'Comment.user_id', 'Comment.content', 'Comment.time'),
                    'conditions' => array(
                        'Comment.lesson_id' => $lesson_id),
                    //'contain' => array('Student')
                );				
                $results = $this->Comment->find('all', $sql);

                $index = 0;
                foreach ($results as $result)
                {
                    if($result['Comment']['user_id'] == $user['id']) {
                        $results[$index]['Comment']['full_name'] = '私';						
                    } else {
                        $sql = array('fields' => array('Student.full_name'),
                            'conditions' => array(
                                'Student.id' => $result['Comment']['user_id'])
                            );					

                        $s = $this->Student->find('first', $sql);								
                        $results[$index]['Comment']['full_name'] = $s['Student']['full_name'];
                    }

                    $index ++;
                }				

                $this->set('results', $results);

            } else {
                $this->redirect(array('controller' => 'users' ,"action" => "permission" ));
            }
        } else {
            $this->redirect(array('controller' => 'users' ,"action" => "permission" ));
        }		
    }

    public function banstudent($value=''){
        $lesson_id = $this->params['named']['lesson_id'];
        $student_id = $this->params['named']['student_id'];

        $member = $this->LessonMembership->find("first", array(
            'conditions' => array('lesson_id' => $lesson_id ,'student_id' => $student_id )
        )
    );
        $member['LessonMembership']['baned'] = !$member['LessonMembership']['baned'];

        if($this->LessonMembership->save($member)){
            if($member['LessonMembership']['baned'])
                $info = 'The user has been baned !';
            else
                $info = 'The user has been allowed !';

            $this->Session->setFlash(__($info), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
        }else{
            $this->Session->setFlash(__('ユーザをロックできない'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'
            ));	
        }
        return $this->redirect($this->referer());
    }

    public function search(){
        if ($this->request->is('post')){
            $lesson = $this->data['Lessons'];
            $rank_stt = $lesson['searchOption'];
            $rankOption = $lesson['rankOption'];
            $search_value = $lesson['keyword'];
            $this->set("rank_stt", $rank_stt);
            $this->Lesson->recursive = 2;

            if (strpos($search_value, "＋")){
                $keyword_r = explode("＋", $search_value);
                $operation = 'AND';
            }else {
                 $keyword_r = explode("｜", $search_value);
                 $operation = 'OR';
            }
            $lesson_and_r1 = array();

            if ($rank_stt == RANK_BY_LECTURER) {
                foreach ($keyword_r as $row) {
                    array_push($lesson_and_r1, array("Lecturer.full_name like"=>"%".$row."%"));
                } 
                $options['conditions'] = array($operation=>$lesson_and_r1);
                if ($rankOption == ASC) {
                    $options['order'] = array('Lecturer.full_name ASC'); 
                }else {
                    $options['order'] = array('Lecturer.full_name DESC'); 
                }
                $lessons = $this->Lesson->find("all", $options);
            }else if ($rank_stt == RANK_BY_LESSON){
                foreach ($keyword_r as $row) {
                    array_push($lesson_and_r1, array("Lesson.name like"=>"%".$row."%"));
                }
                $options['conditions'] = array($operation=>$lesson_and_r1);  
                if ($rankOption == ASC) {
                    $options['order'] = array('Lesson.name ASC'); 
                }else {
                    $options['order'] = array('Lesson.name DESC'); 
                }
                $lessons = $this->Lesson->find("all", $options);
            }else if ($rank_stt == RANK_BY_TAG){
                foreach ($keyword_r as $row) {
                    array_push($lesson_and_r1, array("Tag.name like"=>"%".$row."%"));
                }
                $options['conditions'] = array($operation=>$lesson_and_r1);  
                if ($rankOption == ASC) {
                    $options['order'] = array('Tag.name ASC'); 
                }else {
                    $options['order'] = array('Tag.name DESC'); 
                }

                $this->loadModel("LessonsTag");
                $this->LessonsTag->recursive = 3;
                $lessons = $this->LessonsTag->find("all", $options);
            }
          //  debug($lessons);
            $this->set("lessons", $lessons);
        }
    }

    public function deletestudent($value=''){
        $lesson_id = $this->params['named']['lesson_id'];
        $student_id = $this->params['named']['student_id'];

        $member = $this->LessonMembership->find("first", array(
            'conditions' => array('lesson_id' => $lesson_id ,'student_id' => $student_id )
        )
    );

        if($this->LessonMembership->delete($member['LessonMembership']['id'])){
            $this->Session->setFlash(__('ユーザが削除された'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
        }else{
            $this->Session->setFlash(__('ユーザを削除できない、もう一度お願い'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'
            ));	
        }
        return $this->redirect($this->referer());
    } 

    public function delete() {
        $lesson_id = $this->Session->read('lesson_id');
        $this->set("id", $lesson_id);		
        $user = $this->Auth->user();

        if($user['role'] == 'lecturer') {
            $result = $this->Lesson->findById($lesson_id);
            if($result != NULL) 
            {
                
                $docs = $this->Document->find('all', array('conditions'=> array('Document.lesson_id' => $lesson_id)));
                foreach ($docs as $doc) {
                    $link = $doc['Document']['link'];
                    if ($this->Document->delete($doc['Document']['id'])) {
                        unlink(WWW_ROOT. 'files' . DS . $link);                        
                    }                               
                }

                $tests = $this->Test->find('all', array('conditions'=> array('Test.lesson_id' => $lesson_id)));                   
                foreach ($tests as $test) {
                    $link = $test['Test']['link'];
                    if ($this->Test->delete($test['Test']['id'])) {
                        unlink(WWW_ROOT. 'tsv' . DS . $link);                       
                    }                               
                }   


                if ($this->Lesson->delete($lesson_id))
                {	
                    //del document file
                    // delete comment
                    $this->Comment->deleteAll(array('Comment.lesson_id' => $lesson_id), false);

                    $this->Session->setFlash(__('授業が削除された'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-success'
                    )); 		            
                    return $this->redirect($this->referer());
                } 
            }

        } else {
            $this->redirect(array('controller' => 'users' ,"action" => "permission" ));
        }          
    }

    public function learn($lesson_id){

        $user_id = $this->Auth->user("id");
        if ($this->Session->read("scroll")== "1"){
            $this->set("scroll", "1");
            $this->Session->delete("scroll");
        }
        $this->Lesson->recursive = 2; 
        $options['conditions'] = array("Lesson.id"=>$lesson_id); 
        $lessons = $this->Lesson->find("first",$options); 
        $this->set("lessons",  $lessons);
        if($lessons['Lesson']['baned'] == 1){
            $this->Session->setFlash(__('この授業は禁止された'), 'alert', array(
                'plugin'=>'BoostCake','class' =>'alert-warning'));
            $this->redirect(array('controller' =>'lesson' ,'action' =>'search' ));
        }
        $learnedStudents = $lessons['Student'];
        $likedNumber = 0;
        $liked = 0;
        $student_lesson_id = -11; 
        foreach($learnedStudents as $student){
            if ($student['StudentsLesson']['liked'] == true){
                $likedNumber += 1; 
                if ($student['id']==$user_id) {
                    $liked = 1;
                }
            }
            if ($student['id'] == $user_id){
                $student_lesson_id = $student['StudentsLesson']['id'];
            }
        }
        $this->set("likePeople", $likedNumber);
        $this->set("liked", $liked);
        $this->set('student_lesson_id', $student_lesson_id);

        $learnable = $this->Util->checkLessonAvailableWithStudent($lesson_id, $user_id);
        $this->set("learnable", $learnable); 

    }

    public function register($lesson_id){
        $user_id = $this->Auth->user('id');
        if ($this->Util->checkLessonAvailableWithStudent($lesson_id, $user_id)==LEARNABLE){
           $this->redirect("/lesson/learn/".$lesson_id);     
        }
        if ($this->request->is('post')){
            $this->loadModel('StudentsLesson');
            $data = array("student_id"=>$user_id, "lesson_id"=>$lesson_id, "price"=>LESSON_COST, "percent_for_teacher"=>LECTURER_MONEY_PERCENT) ;   
            $this->StudentsLesson->save($data); 

            $student_lesson_id = $this->StudentsLesson->getLastInsertID();
            $this->loadModel("LessonMembership");
            $this->LessonMembership->recursive = 3;
            $student_lesson = $this->LessonMembership->findById($student_lesson_id);
         //   debug($student_lesson);
            $student_username = $student_lesson['Student']['User']['username'];
            $student_fullname = $student_lesson['Student']['full_name'];
            $student_address = $student_lesson['Student']['address'];
            $student_account = $student_lesson['Student']['credit_card_number'];
            $lecturer_username = $student_lesson['Lesson']['Lecturer']['User']['username'];
            $lecturer_fullname = $student_lesson['Lesson']['Lecturer']['full_name'];
            $lecturer_address = $student_lesson['Lesson']['Lecturer']['address'];
            $lecturer_account =$student_lesson['Lesson']['Lecturer']['credit_card_number'];
            $days_attended = $student_lesson['LessonMembership']['days_attended'];
            $price = $student_lesson['LessonMembership']['price'];
            $percent_for_teacher= $student_lesson['LessonMembership']['percent_for_teacher'];
            $student_phone_number = $student_lesson['Student']['phone_number'];
            $lecturer_phone_number = $student_lesson['Lesson']['Lecturer']['phone_number'];
            
            $data2 = array("days_attended"=>$days_attended, "price"=>$price, "percent_for_teacher"=>$percent_for_teacher, 
                "lecturer_username"=>$lecturer_username, "lecturer_fullname"=>$lecturer_fullname, "lecturer_address"=>$lecturer_address, "lecturer_account"=>$lecturer_account, 
                "student_username"=>$student_username, "student_fullname"=>$student_fullname, "student_address"=>$student_address, "student_account"=>$student_account
                    ,"student_phone_number" =>$student_phone_number,"lecturer_phone_number" =>$lecturer_phone_number);

           $this->loadModel("History");
           $this->History->save($data2);
           $this->redirect(array("controller"=>"lesson", "action"=>"learn", $lesson_id));
        
        }
    }


    public function like($student_lesson_id){
        //  $this->set("liked", true);
        $this->loadModel("StudentsLesson");
        $this->StudentsLesson->id = $student_lesson_id; 
        $data = array("liked"=>1);
        $this->StudentsLesson->save($data);
        $this->redirect($this->referer());
    }
    public function dislike($student_lesson_id){
        $this->loadModel("StudentsLesson");
        $this->StudentsLesson->id = $student_lesson_id; 
        $data = array("liked"=>0);
        $this->StudentsLesson->save($data);
        $this->redirect($this->referer());
    }

    public function comment($lesson_id, $content){
        $this->loadModel("Comment");
        $data = array("user_id"=>$this->Auth->user("id"), "lesson_id"=>$lesson_id, "content"=>$content);
        $this->Comment->save($data);
        $this->Session->write("scroll", "1");
        $this->redirect($this->referer());
    }
    public function report_violate($lesson_id){
        $lesson= $this->Lesson->findById($lesson_id);
        $this->set("lesson", $lesson['Lesson']);
        if ($this->request->is('post')){
            debug($this->data);
            $content = $this->data['report']['content'];
            $data_save = array("lecturer_id"=>$this->Auth->user("id"), "content"=>$content, "lesson_id"=>$lesson_id);
            $this->loadModel("Ihan");
            $this->Ihan->save($data_save);
            $this->Session->setFlash(__('違犯レポートが管理者に送れた'), 'alert', array(
                'plugin'=>'BoostCake','class' =>'alert-warning'));
            $this->redirect("/lesson/learn/".$lesson_id);
        } 
    }

    public function searchByTag($tag_name=""){
        $this->loadModel("Tag");
        $this->Tag->recursive = 3; 
        $tag_list = $this->Tag->find("all", array("order"=>"Tag.name asc"));
        $this->set("tag_list", $tag_list);
        $rankStt = 0; 
        if ($this->request->is('post')){
            $rankStt =$this->data['rankWay']['rankStt'];
        }

            // $query_base = "select lessons.id, lessons.name, users.username, lecturers.full_name, 
            //                         count(students_lessons.id) as student_number, sum(students_lessons.liked) as liked_number
            //         from lessons, tags, lessons_tags, students_lessons, lecturers, users
            //         where tags.name = '".$tag_name."' and 
            //                 tags.id = lessons_tags.tag_id and 
            //                 lessons_tags.lesson_id = lessons.id and
            //                 lessons.lecturer_id = lecturers.id and 
            //                 students_lessons.lesson_id= lessons.id and 
            //                 lecturers.id = users.id
            //         group by lessons.id"; 

             $query_base = "select lessons.id as LESSID, lessons.name, users.username, lecturers.full_name, 
                                 (select count(students_lessons.id) from students_lessons, lessons where lessons.id = students_lessons.lesson_id and lessons.id = LESSID) as student_number, 
                                 (select sum(students_lessons.liked) from students_lessons, lessons where lessons.id = students_lessons.lesson_id and lessons.id = LESSID) as liked_number
                    from lecturers, users, lessons 
                    where   lessons.lecturer_id = lecturers.id and 
                            lecturers.id = users.id and 
                            lessons.id in (
                                select lessons.id 
                                from  tags, lessons_tags, lessons
                                where tags.name = '".$tag_name."' and 
                                tags.id = lessons_tags.tag_id and 
                                lessons_tags.lesson_id = lessons.id
                            )
                    group by lessons.id"; 
            if ($rankStt==1){
                $query = $query_base. " order by student_number desc"; 
            }else if ($rankStt == 0){
                $query = $query_base. " order by liked_number desc"; 
            }else if ($rankStt==2){
                $query = $query_base. " order by lecturers.full_name desc"; 
            }else if ($rankStt == 3){
                $query = $query_base. " order by users.username desc"; 
            }
            //debug($query);
            $res = $this->Lesson->query($query);
            //debug($res);
            $this->set("res", $res);


    }

}
