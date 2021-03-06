<?php

class StudentsController extends AppController {
    var $components = array("Auth", "Paginator", "Loger", "Util");
    var $uses = array('Student');
    public $helpers = array("Util", "Paginator");
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow("register");
        if($this->Auth->loggedIn() && $this->Auth->user('role') != 'student')
            $this->redirect(array('controller' => 'users', 'action' => 'permission'));
        // if($this->Auth->loggedIn() && $this->Auth->user('actived') != 1)
        //     $this->redirect(array('controller' => 'users', 'action' => 'deactive'));
    }

    public function index(){
        $this->redirect("/students/profile");
    }

    public function fix_account(){
        $this->Student->recursive = 2;
        $id = $this->Auth->user("id");
        if($this->request->is('post')){
            $user = $this->data['User'];
            $student = $this->data['Student'];
            $this->Student->id = $id;
            $this->Student->save($student);
            $this->loadModel("User");
            $this->User->id = $id;
            $this->User->save($user);
            $this->Session->setFlash("<div class = 'alert alert-warning alert-dismissable'>プロファイルが更新された</div>");
           // $this->Loger->writeLog(O,"", "Student", "ユーザがアカウントを更新した", "" );
            $this->redirect("/students/profile");
        }
        $student = $this->Student->find('first', array('conditions'=>array('Student.id'=>$id)));  
        $this->set('student', $student['Student']); 
    }

    public function register(){
        $this->loadModel('User');
        $this->loadModel('Student');

        if($this->request->is("post")){
            if ($this->request->data['User']['password'] != $this->request->data['User']['rePassword']){
                $this->Session->setFlash(__('パスワードと再パスワードが違いました。もう一度お願いします！'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                ));
            }else {
                $this->User->create();
                $this->request->data['Student']['ip_address'] = $this->request->clientIp();
                $this->request->data['User']['role'] = 'student';
                $this->request->data['Student']['init_password'] = AuthComponent::password($this->request->data['User']['password']);
                unset($this->request->data['User']['rePassword']); 
                if($this->User->saveAll($this->request->data)){
                    $this->Session->setFlash(__('登録が成功した、ログインしてください'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-success'
                    ));
                    return $this->redirect(array('controller' => 'users', 'action' => 'login'));
                }
                $this->Session->setFlash(__('登録が失敗した、もう一度お願い'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                ));
            }
        }
    }

    public function delete(){
        if ($this->request->is("post")){
            $user_id = $this->Auth->user("id");
            $this->loadModel("User");
            $this->User->delete($user_id);
            $this->Student->delete($user_id);
            $this->loadModel("Comment");
            $this->Comment->deleteAll(array("user_id"=>$user_id));
            $this->loadModel("Result");
            $this->Result->deleteAll(array("student_id"=>$user_id));
            $this->loadModel("Violate");
            $this->Violate->deleteAll(array("student_id"=>$user_id));
            $this->redirect($this->Auth->logout());
        }
    }
    public function profile(){
        $id = $this->Auth->user("id");
        $student = $this->Student->find('first', array('conditions'=>array('Student.id'=>$id)));  
        $this->set('student', $student['Student']);
        $this->set('user', $student['User']);
    }

    public function history(){
        $user_id = $this->Auth->user("id");
        $this->Student->recursive = 3;
        $options['conditions'] = array("Student.id"=>$user_id);
        $res = $this->Student->find("first", $options);
        $this->set("student", $res);

        $this->loadModel("Result");
        $this->Result->recursive = 2;

        $paginate = array(
            "limit"=>10,
            "order"=>array("Result.time"=>"desc"), 
            "conditions"=>array("Student.id"=>$user_id)
        );
        $this->Paginator->settings = $paginate;
        $tests = $this->Paginator->paginate("Result");

        $this->set("tests", $tests);
    }
    public function search(){
        $keyword = $this->params['url']['search_value'];
        $this->loadModel("Document");
        $keyword_r = explode(";", $keyword);
        $document_or_r = array();
        foreach ($keyword_r as $row) {
            array_push($document_or_r, array("Document.title like"=>"%".$row."%"));
        }

        //    $option['conditions'] = array("Document.title like "=>"%".$keyword."%"); 
        //    debug($document_or_r);
        $option['conditions'] = array("OR"=>$document_or_r); 
        $documents =$this->Document->find("all", $option);
        $this->set("documents", $documents);

        $this->loadModel("Lesson");
        $lesson_or_r = array();
        foreach ($keyword_r as $row) {
            array_push($lesson_or_r, array("Lesson.name like"=>"%".$row."%"));
            array_push($lesson_or_r, array("Lesson.summary like"=>"%".$row."%"));
        }

        $option['conditions'] = array("OR"=>$lesson_or_r);
        $lessons =$this->Lesson->find("all", $option);
        $this->set("lessons", $lessons);


        $lecturer_or_r = array();
        foreach ($keyword_r as $row) {
            array_push($lecturer_or_r, array("Lecturer.full_name like"=>"%".$row."%"));
            array_push($lecturer_or_r, array("User.username like"=>"%".$row."%"));
        }
        $this->loadModel("Lecturer");

        $option['conditions'] = array("OR"=>$lecturer_or_r);

        $lecturers =$this->Lecturer->find("all", $option);
        $this->set("lecturers", $lecturers);

        $this->loadModel("Student");
        $student_or_r = array();
        foreach ($keyword_r as $row) {
            array_push($student_or_r, array("Student.full_name like"=>"%".$row."%"));
            array_push($student_or_r, array("User.username like"=>"%".$row."%"));
        }
        $option['conditions'] = array("OR"=>$student_or_r);
        $students =$this->Student->find("all", $option);
        $this->set("students", $students);

        $this->set("keyword", $keyword);
    }
}
