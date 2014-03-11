<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ManagerController
 *
 * @author Tha
 */
class AdminsController extends AppController {

    //var $uses = array('User', 'Admin', 'Violate',);
    public $components = array('Paginator');

    //put your code here
    public function beforeFilter() {
//       $this->Auth->allow("add_admin");
//       $this->Auth->allow("remove_admin");
//       $this->Auth->allow("remove_admin_process");
//       $this->Auth->allow("view_violation");
//        $this->Auth->allow("view_violation_content");
//        $this->Auth->allow("view_violation_content_process");
       
    }

    public function index() {
        
    }

    public function add_admin() {
        $this->uses = array('User', 'Admin');

        if ($this->request->is('post')) {
            $this->request->data["User"]["role"] = "admin";
            $this->request->data["User"]["actived"] = 1;
            if ($this->User->saveAll($this->request->data)) {

                $this->Session->setFlash(__('The user has been saved'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('controller' => 'admins', 'action' => 'add_admin'));
            }
            $this->Session->setFlash(__('The User could not be saved. Plz try again'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'
            ));
        }

        //if ($repassword != $password) {
//            $notify = "パスワードとリパスワードは違がいました";
//            $this->redirect(array("controller" => "admins",
//                "action" => "admin",
//                "notify" => "パスワードとリパスワードは違った",
//            ));
//        } else {
//            $count = $this->admin->find('count', array('conditions' => array("admin.username " => $username)));
//            echo "count = " . $count;
//            if ($count > 0)
//                $notify = "このユーザ名が存在していました";
//            else {
//                $notify = "アカウントが作成されました";
//                $this->admin->save(array("username" => $username, "password" => $password, "email" => $email, "ip_address" => $ip_address));
//                $this->user->save(array("username" => $username, "password" => $password, "salt" => "0000", "active" => "1", "role" => "admin"));
//            }
//        }
//        
//        $this->redirect(array("controller" => "admins",
//            "action" => "admin",
//            "notify" => $notify,
//        ));
    }

    public function remove_admin() {
        $this->uses = array('User', 'Admin');
        $this->paginate = array(
            'limit' => 1,
            'fields' => array(),
            'conditions' => array(
                "User.actived" => 1,
                "User.role" => "admin")
        );

        $this->Paginator->settings = $this->paginate;
        $res = $this->Paginator->paginate("User");
        $this->set('res', $res);
        //debug($res);
    }

    public function remove_admin_process($id) {
        if(!isset($id))$this->redirect(array("action" => "remove_admin"));
        $this->uses = array('User', 'Admin');
        if ($this->User->delete($id))
            $this->Session->setFlash(__('The admin has been deleted'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
        $this->redirect(array("action" => "remove_admin"));
    }

    public function view_violation() {
        // $this->uses = array('Violate', 'Lecturer','Student','Document');
        $this->uses = array('Violate');

        $this->paginate = array(
            'limit' => 1,
            'fields' => array(),
            'conditions' => array(
                "Violate.accepted" => 0)
        );

        $this->Paginator->settings = $this->paginate;
        $res = $this->Paginator->paginate("Violate");
        $this->set('res', $res);
    }
   
    
    public function view_violation_content($id) {
        $this->uses = array('Violate');
        $res = $this->Violate->find('all', array('conditions' => array('Violate.id' => $id),
            "Violate.accepted" => 0
        ));
        if ($res) {
            $violate_id = $res[0]['Violate']['id'];
            $this->set('violate_id', $violate_id);
            $student_id = $res[0]['Violate']['student_id'];
            $this->set('student_id', $student_id);
            $document_id = $res[0]['Violate']['document_id'];
            $this->set('document_id', $document_id);
            $content = $res[0]['Violate']['content'];
            $this->set('content', $content);
        }
        $this->uses = array('Student');
        $res = $this->Student->find('all', array('conditions' => array('Student.id' => $student_id),
        ));
        if ($res) {
            $student_fullname = $res[0]['Student']['full_name'];
            $this->set('student_fullname', $student_fullname);
        }
        $this->uses = array('Document');
        $res = $this->Document->find('all', array('conditions' => array('Document.id' => $document_id),
        ));

        if ($res) {
            $lesson_id = $res[0]['Document']['lesson_id'];
            $title = $res[0]['Document']['title'];
            $this->set('title', $title);
            $this->set('lesson_id', $lesson_id);
        }
        $this->uses = array('Lesson');
        $res = $this->Lesson->find('all', array('conditions' => array('Lesson.id' => $lesson_id),));
        if ($res) {
            $lecturer_id = $res[0]['Lesson']['lecturer_id'];
        }
        $violations = $this->Violate->find('all', array('conditions' => array("Violate.accepted" => 1)
        ));

        $count = 0;
        if ($violations)
            foreach ($violations as $violation) {
                if ($this->checkDocumentIsOfLecturer($lecturer_id, $violation['Violate']['document_id']))
                    $count++;
            }

        $this->set('count', $count);

        $this->set('lecturer_id', $lecturer_id);

        $this->uses = array('User');

        $res = $this->User->find('all', array('conditions' => array(
                'User.id' => $lecturer_id,
            ),
        ));
        if ($res)
            $lecturer_name = $res[0]['Lecturer']['full_name'];
        $this->set('lecturer_name', $lecturer_name);
    }

    public function view_violation_content_process() {
        $this->uses = array('Violate');
        
        $id = $this->request->params['named']['id'];
        $count = $this->request->params['named']['count'];
        $lecturer_id = $this->request->params['named']['lecturer_id'];
        if ($this->checkContainKey($this->data, "accept")) {
            $res = $this->Violate->find('all', array('conditions' => array('Violate.id' => $id),
                "Violate.id" => $id
            ));
            $res[0]['Violate']['accepted'] = 1;
            $notify = "この違犯リポットを確認しました";
            //echo $notify;
            $this->Violate->saveAll($res);
            $notify = $notify.$this->checkLockLecturerAccount($count+1,$lecturer_id);
            
        } else {
            $notify = "この違犯リポットを削除しました";
            $this->Violate->delete($id);
        }

        $this->Session->setFlash(__($notify), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-success'
        ));
        echo "notifiy" + $notify;
        $this->redirect(array("action" => "view_violation"));
    }

    private function checkContainKey($param, $_key) {

        foreach ($param as $key => $value) {
            if ($key == $_key)
                return 1;
        }
        return 0;
    }

    private function checkDocumentIsOfLecturer($lecturer_id, $document_id) {

        $this->uses = array('Document');
        $res = $this->Document->find('all', array('conditions' => array('Document.id' => $document_id),
        ));
        
        if (!$res)
            return 0;
        $lesson_id = $res[0]['Document']['lesson_id'];
        $this->uses = array('Lesson');
        $res = $this->Lesson->find('all', array('conditions' => array('Lesson.id' => $lesson_id),));

        if ($lecturer_id == $res[0]['Lesson']['lecturer_id'])
            return 1;
        return 0;
    }
    
    private function checkLockLecturerAccount($number_of_violation,$user_id)
    {
        echo $number_of_violation;
        if($number_of_violation >= 3)
        {
            $this->uses = array('User');
            echo "user_id =".$user_id; 
            $res = $this->User->find('all', array('conditions' => array('User.id' => $user_id)
             
            ));
            if($res)
            {$res[0]['User']['actived'] = 0;
            $this->User->saveall($res);
            echo "vuot qua 3 lan";
            }
            return "<br>　この先生は違犯回数が3回以上なのでアカウントがロックされていた";
        }
        return "";
        
    }

}
?>



