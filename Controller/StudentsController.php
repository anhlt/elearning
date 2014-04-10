<?php

class StudentsController extends AppController {
    //   var $helpers = array("Html", "Form");//このところはちょっと変、AppController　にあるのに
    var $components = array("Auth");//ここも
    var $useTable = array('Question', 'Student');
    public $helpers = array("Util");
    public function beforeFilter(){
        $this->Auth->allow("register");	

    }

    public function index(){

    }

    public function fix_account(){
        $this->Student->recursive = 2;
        $id = $this->Auth->user("id");
        if($this->request->is('post')){
            $user = $this->data['User'];
            $student = $this->data['Student'];
            debug($user);
            debug($student);
            $this->Student->id = $id;
            $this->Student->save($student);
            $this->loadModel("User");
            $this->User->id = $id;
            $this->User->save($user);
            $this->Session->setFlash("<div class = 'alert alert-warning alert-dismissable'>プロファイルが更新された</div>");
            $this->redirect("/students/profile");
        }
        $this->loadModel('Question');
        $student = $this->Student->find('first', array('conditions'=>array('Student.id'=>$id)));  
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

    public function delete(){
        if ($this->request->is("post")){
            $user_id = $this->Auth->user("id");
            $this->loadModel("User");
            $this->User->updateAll(array("actived"=>DELETED.""), array("User.id"=>$user_id));
            $this->redirect($this->Auth->logout());
        }
    }
    public function profile(){
        $this->loadModel('Question');
        $id = $this->Auth->user("id");
        $student = $this->Student->find('first', array('conditions'=>array('Student.id'=>$id)));  
        $this->set('student', $student['Student']);
        $this->set('user', $student['User']);
    }

    public function history(){
        $user_id = $this->Auth->user("id");
        $this->Student->recursive = 2;
        $options['conditions'] = array("Student.id"=>$user_id);
        $res = $this->Student->find("first", $options);
        $this->set("student", $res);
        
        $this->loadModel("Result");
        $this->Result->recursive = 2;
        $options['order'] = array("Result.time");
        $tests = $this->Result->find("all",$options);
        $this->set("tests", $tests);
        //debug($tests);
    }
}
