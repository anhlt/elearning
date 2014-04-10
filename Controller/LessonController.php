<?php 
class LessonController extends AppController {
    var $name = "Lesson";

    var $uses = array('User', 'Lecturer','Question','Lesson','Tag', 'Document', 'Test', 'LessonMembership');
    public $components = array('RequestHandler', 'Paginator', 'Util');
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
                $this->Session->setFlash(__('The Lesson has been saved'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('controller' => 'Document', 'action' => 'add','id' =>  $id));
            }
            else{
                $this->Session->setFlash(__('The User could not be saved. Plz try again'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                ));	
            }
        }
    }
    public function edit(){
        $lesson_id = $this->params['named']['id'];
        //var_dump($lesson_id);
        $Lesson = $this->Lesson->findById($lesson_id);
        if (!$Lesson) {
            $this->Session->setFlash(__('This Lesson not exist'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'
            ));	
            return $this->redirect(array('controller' => 'Lecturer', 'action' => 'manage'));		
        }
        $this->set("id", $lesson_id);

        if($this->request->is('post')){
            $data = ($this->request->data);
            $data['Lesson']['lecturer_id'] = $this->Auth->user('id');
            $rawtags = explode(",",$data["hidden-data"]['Tag']['name']);
            $tags = array();
            foreach ($rawtags as $key => $value) {
                #var_dump($value);
                $tag = $this->Tag->findByName($value);
                if (!$tag) {
                    $this->Tag->create();
                    $this->Tag->set("name",$value);
                    $tag = $this->Tag->save();
                }
                array_push($tags, $tag['Tag']['id']);
            }
            $data['Tag'] = $tags;
            if($this->Lesson->saveAll($data)){
                $this->Session->setFlash(__('The Lesson Info has been saved'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
            }
            else{
                $this->Session->setFlash(__('The Lesson Info could not be saved. Plz try again'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                ));	
            }			
            return $this->redirect($this->referer());
        }
    }

    public function delete($value='')
    {
        $lesson_id = $this->params['named']['id'];
        $lesson = $this->Lesson->find("first", array('conditions' => array('Lesson.id' => $lesson_id))); 
        if($lesson && $this->Lesson->delete($lesson_id)){
            $this->Session->setFlash(__('The Lesson has been Removed'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
        }else{
            $this->Session->setFlash(__('The Lesson could not be deleted. Plz try again'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'
            ));	

        }
        return $this->redirect($this->referer());
    }


    public function deletestudent($value=''){
        $lesson_id = $this->params['named']['lesson_id'];
        $student_id = $this->params['named']['student_id'];

        $member = $this->LessonMembership->find("first",array(
            'conditions' => array('lesson_id' => $lesson_id ,'student_id' => $student_id )
        )
    );

        if($this->LessonMembership->delete($member['LessonMembership']['id'])){
            $this->Session->setFlash(__('The User has been Removed'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
        }else{
            $this->Session->setFlash(__('The User could not be deleted. Plz try again'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'
            ));	
        }
        return $this->redirect($this->referer());

    }


    public function detail_doc()
    {			
        $user = $this->Auth->user();
        $lesson_id = $this->params['named']['id'];
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

    public function detail_test() {
        $lesson_id = $this->params['named']['id'];
        $this->set("id", $lesson_id);
        $user = $this->Auth->user();

        if($user["role"] == 'lecturer') {
            $lesson_id = $this->params['named']['id'];
            $sql = array("conditions"=> array("Lesson.id =" => $lesson_id, "Lesson.lecturer_id =" => $user['id']));
            $result = $this->Lesson->find('first',$sql);

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

    public function detail_coin() {

    }

    public function summary() {

    }

    public function report() {

    }

    public function banstudent($value=''){
        $lesson_id = $this->params['named']['lesson_id'];
        $student_id = $this->params['named']['student_id'];

        $member = $this->LessonMembership->find("first",array(
            'conditions' => array('lesson_id' => $lesson_id ,'student_id' => $student_id )
        )
    );
        $member['LessonMembership']['baned'] = !$member['LessonMembership']['baned'];

        if($this->LessonMembership->save($member)){
            $this->Session->setFlash(__('The User has been Baned'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
        }else{
            $this->Session->setFlash(__('The User could not be baned. Plz try again'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'
            ));	
        }
        return $this->redirect($this->referer());
    }
    public function search(){
        if($this->request->is("post")){
            $lesson = $this->data['Lessons'];
            if (isset( $lesson['rank'])){
                $rank_stt = $lesson['rank'];
                $this->set("rank_stt", $rank_stt);
                $this->Lesson->recursive = 2;
                if($rank_stt == RANK_BY_LECTURER){ 
                    $options['order'] = array('Lecturer.full_name'); 
                    $lessons = $this->Lesson->find("all", $options);
                    $this->set("lessons", $lessons);
                }else if ($rank_stt==RANK_BY_LESSON){//rank by lessons's name
                    $options['order'] = array("Lesson.name");
                    $lessons = $this->Lesson->find("all", $options);
                    $this->set("lessons", $lessons);

                }else if ($rank_stt==RANK_BY_TAG){
                    $this->Tag->recursive = 3; 
                    $options['order'] = array("Tag.name"); 
                    $lessons = $this->Tag->find("all", $options);
                    $this->set("lessons", $lessons);
                }
                //		debug($lessons);
                //		die();
            }
        }
        if ($this->request->is('get')) {
            $this->Tag->recursive = 3;

            if (isset($this->params['url']['search_value'])) {
                $tag_value = $this->params['url']['search_value'];
                $options['conditions'] = array("Tag.name LIKE" => "%".$tag_value."%");
                $tags = $this->Tag->find("all", $options); 
                $this->set("tags", $tags);
            }
        }
    }
    public function show($lesson_id){
        $user_id = $this->Auth->user("id");
        if ($this->Util->checkLessonAvailableWithStudent($lesson_id, $user_id)){
            $this->redirect("/lesson/learn/".$lesson_id);     
        } 
        $this->Lesson->recursive = 2; 
        $options['conditions'] = array("Lesson.id"=>$lesson_id); 
        $lessons = $this->Lesson->find("first",$options); 
        $this->set("lessons",  $lessons);
    }
    public function learn($lesson_id){
        $user_id = $this->Auth->user("id");
    //    $this->loadModel("StudentsLesson");
    //    $options['conditions'] = array("student_id"=>$user_id, "lesson_id"=>$lesson_id);
    //    $res =  $this->StudentsLesson->find("first", $options);
     /*   if (count($res)==0){
            $this->Session->setFlash(__("この授業はまだ登録しません"));
            $this->redirect("/lesson/show/".$lesson_id);
            return; 
        }else {
            $day_attended = $res['StudentsLesson']['days_attended'];
            $now = date("Y-m-d H:i:s");
            $days = floor((strtotime($now)- strtotime($day_attended))/24/3600);
            if ($days > MAX_LEARN_DAY){ 
                $this->Session->setFlash(__("この授業はまだ登録しません"));
                $this->redirect("/lesson/show/".$lesson_id);
            }

        }*/

        if (!$this->Util->checkLessonAvailableWithStudent($lesson_id, $user_id)){
             $this->Session->setFlash(__("<div class = 'alert alert-warning'>この授業はまだ登録しません</div>"));
             $this->redirect("/lesson/show/".$lesson_id);
        } 
        $this->Lesson->recursive = 2; 
        $options['conditions'] = array("Lesson.id"=>$lesson_id); 
        $lessons = $this->Lesson->find("first",$options); 
        $this->set("lessons",  $lessons);

        // いいねを計算する     
        $learnedStudents = $lessons['Student'];
        $likedNumber = 0;
        $liked = 0;
        foreach($learnedStudents as $student){
            if ($student['StudentsLesson']['liked'] == true){
                $likedNumber += 1; 
                if ($student['id']==$user_id) {
                    $liked = 1;
                }
            }
            if ($student['id'] ==$user_id){
                $student_lesson_id = $student['StudentsLesson']['id'];
            }
        }
        $this->set("likePeople", $likedNumber);
        $this->set("liked", $liked);
        $this->set('student_lesson_id', $student_lesson_id);
     //   debug($lessons);
    }

    public function register($lesson_id){
        $user_id = $this->Auth->user('id');
        if ($this->Util->checkLessonAvailableWithStudent($lesson_id, $user_id)){
            $this->redirect("/lesson/learn/".$lesson_id);     
        } 
        if ($this->request->is('post')){
            $this->loadModel('StudentsLesson');
            $data = array("student_id"=>$user_id, "lesson_id"=>$lesson_id) ;   
            $this->StudentsLesson->save($data);
            /*
             * save log, create hoa don
             * 
             */

            $this->redirect(array("controller"=>"lesson", "action"=>"learn", $lesson_id));
        }
    }

  /*  public function learn($lesson_id){
        $this->loadModel("StudentsLesson");
        $options['fields']= array("Lecturer.*", "User.*", "Lesson.*");
        $options['joins'] = array(
            array("table"=>"lecturers", "alias"=>"Lecturer",  "conditions"=>array("Lecturer.id = Lesson.lecturer_id")), 
            array("table"=>"users", "alias"=>"User", "conditions"=>array("User.id=Lesson.lecturer_id"))
        ); 
        $options['conditions'] = array("Lesson.id"=>$lesson_id); 
        $lessons = $this->Lesson->find("all",$options); 
        $this->set("lessons", $lessons);
        //Get tai lieu 
        $options['fields'] = array("Document.*");
        $options['joins'] = array(
            array("table"=>"documents", "alias"=>"Document", "conditions"=>array("Document.lesson_id = Lesson.id")));
        $options['conditions'] = array("Lesson.id"=>$lesson_id); 
        $documents = $this->Lesson->find("all", $options);
        $this->set("documents", $documents);

        //Get comment
        $options['fields'] = array("Comment.*" ,"User.username");
        $options['joins'] = array(
            array("table"=>"comments", "alias"=>"Comment", "conditions"=>array("Comment.lesson_id = Lesson.id")), 
            array("table"=>"users", "alias"=>"User", "conditions"=>array("Comment.user_id = User.id" ))
        );
        $options['conditions'] = array("Lesson.id"=>$lesson_id); 
        $comments = $this->Lesson->find("all", $options);
      //  debug($comments);
        $this->set("comments", $comments);
       //受講した学生の数を数える
        $learnPeople = $this->StudentsLesson->find("count", array("conditions"=>array("lesson_id"=>$lesson_id)));
        $likePeople = $this->StudentsLesson->find("count", array("conditions"=>array("lesson_id"=>$lesson_id,  "liked"=>1)));
        //いいねの数を数える
        $res = $this->StudentsLesson->find("first", array("fields"=>"liked", "conditions"=>array("student_id"=>$this->Auth->user("id"), "lesson_id"=>$lesson_id))); 
        $this->set("liked", $res['StudentsLesson']["liked"]);
        $this->set("learnedPeople", $learnPeople);
        $this->set("likePeople", $likePeople);
  }*/

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
        $this->redirect($this->referer());
    }



}
