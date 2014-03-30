<?php

class StudentsController extends AppController {
    //   var $helpers = array("Html", "Form");//このところはちょっと変、AppController　にあるのに
    var $components = array("Auth");//ここも
    var $useTable = array('Question', 'Student');
    public function beforeFilter(){
        $this->Auth->allow("register");	

    }

    public function index(){

    }

    public function fix_account(){
        $id = $this->Auth->user("id");
        if($this->request->is('post')){
            $full_name = $this->data["Students"]["full_name"];
            $address = $this->data['Students']['address'];
            $phone_number = $this->data["Students"]["phone_number"];
            $email = $this->data["Students"]["email"];
            $username = $this->data["Students"]["username"];
            $password = $this->data["Students"]["password"];
            $repassword = $this->data['Students']['rePassword'];
            $credit_card_number = $this->data["Students"]["credit_card_number"];
            $answer_verifycode = $this->data["Students"]["answer_verifycode"];
            $student_info = array("full_name"=>$full_name, "address"=>$address, "phone_number"=>$phone_number, "email"=>$email, "username"=>$username, "password"=>"$password", "repassword"=>$repassword, "credit_card_number"=>$credit_card_number); 
            $this->Student->updateAll($student_info, array('id'=>$id));
     //       $this->Student->save($student_info);
            $this->redirect(array('controller'=>'students', 'action'=>'profile'));
        }
        $this->loadModel('Question');
        //     echo ($id);    
        $student = $this->Student->find('first', array('conditions'=>array('id'=>$id)));  
        $this->set('student', $student['Student']);
        $questions = $this->Question->find('all');
        $droplist = array();
        foreach ($questions as $question) {
            $droplist[$question['Question']['id']] = $question['Question']['question'];
        }
        $this->set('droplist', $droplist);
    }

    public function register(){
        $this->loadModel('Question');
        $this->loadModel('User');
        $this->loadModel('Student');
        $questions = $this->Question->find('all');
        $droplist = array();
        foreach ($questions as $question) {
            $droplist[$question['Question']['id']] = $question['Question']['question'];
        }
        $this->set('droplist', $droplist);


        if($this->request->is("post")){

                $this->User->create();
                $this->request->data['Student']['ip_address'] = $this->request->clientIp();
                $this->request->data['User']['role'] = 'student';
                if($this->User->saveAll($this->request->data)){
                    $this->Session->setFlash(__('The user has been saved'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-success'
                    ));
                    return $this->redirect(array('controller' => 'users', 'action' => 'login'));
                }
                $this->Session->setFlash(__('The User could not be saved. Plz try again'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                ));
            }




     //        $full_name = $this->data["Students"]["full_name"];
     //        $address = $this->data['Students']['address'];
     //        $phone_number = $this->data["Students"]["phone_number"];
     //        $email = $this->data["Students"]["email"];
     //        $username = $this->data["Students"]["username"];
     //        $password = $this->data["Students"]["password"];
     //        $repassword = $this->data['Students']['rePassword'];
     //        $credit_card_number = $this->data["Students"]["credit_card_number"];
     //        $answer_verifycode = $this->data["Students"]["answer_verifycode"];
     //        $birthday = $this->data["Students"]["date_of_birth"];


     //        //Gia su nhu oke het roi
     //        $student_info = array("full_name"=>$full_name, "address"=>$address, "phone_number"=>$phone_number, "email"=>$email, "username"=>$username, "password"=>"$password", "repassword"=>$repassword, "credit_card_number"=>$credit_card_number); 

     //        //チェックがよければ、データベースに保存してプロファイルの画面に移動する
     //        $this->Student->save($student_info);
     //        $user_id = $this->Student->getLastInsertId();
     //        echo ("id cua nguoi dung la ".$user_id);
     //        $this->Auth->login(array("id"=>$user_id));
     //        $user_id= $this->Auth->user("id"); 
     //        $this->User->save(array("id"=> $user_id, "username"=>$username, "role"=>"student"));
     // //       $this->redirect(array("controller"=>"students", "action"=>"profile"));
     //        echo ("id lay ra tu auth la ".$user_id);
     //    }    
    }

    public function profile(){
        $this->loadModel('Question');
        $id = $this->Auth->user("id");
        $student = $this->Student->find('first', array('conditions'=>array('Student.id'=>$id)));  
        $this->set('student', $student['Student']);
    }

    public function history(){
        $user_id = $this->Auth->user("id");
        $this->LoadModel("StudentsLesson");
        $options['fields'] = array("Lesson.*", "StudentsLesson.*");
        $options['conditions'] = array("student_id"=>$user_id);
        $options['joins'] = array(
            array("table"=>"lessons", "alias"=>"Lesson", "conditions"=>array("Lesson.id = StudentsLesson.lesson_id"))
        );
        $res = $this->StudentsLesson->find("all", $options);
        $this->set("history", $res);


     //   debug($res);
    }
}
