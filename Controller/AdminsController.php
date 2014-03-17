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
        $this->Auth->allow("add_admin");
        $this->Auth->allow("remove_admin");
        $this->Auth->allow("view_violation");
        $this->Auth->allow("view_violation_content");
        $this->Auth->allow("view_violation_content_process");
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

        $violate_id = $res[0]['Violate']['id'];
        $this->set('violate_id', $violate_id);

      

        $student_id = $res[0]['Violate']['student_id'];
        $this->set('student_id', $student_id);
        $document_id = $res[0]['Violate']['document_id'];
        $this->set('document_id', $document_id);
        $content = $res[0]['Violate']['content'];
        $this->set('content', $content);
        $this->uses = array('Student');
        $res = $this->Student->find('all', array('conditions' => array('Student.id' => $student_id),
        ));
        $student_fullname = $res[0]['Student']['full_name'];
        $this->set('student_fullname', $student_fullname);

        $this->uses = array('Document');
        $res = $this->Document->find('all', array('conditions' => array('Document.id' => $document_id),
        ));
        $lesson_id = $res[0]['Document']['lesson_id'];
        $title = $res[0]['Document']['title'];
        $this->set('title', $title);
        $this->set('lesson_id', $lesson_id);

        $this->uses = array('Lesson');
        $res = $this->Lesson->find('all', array('conditions' => array('Lesson.id' => $lesson_id),));
        $lecturer_id = $res[0]['Lesson']['lecturer_id'];

      

        $violations = $this->Violate->find('all', array('conditions' => array("Violate.accepted" => 1)
        ));
        //debug($violations);
        $count = 0;
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

        $lecturer_name = $res[0]['Lecturer']['full_name'];
        $this->set('lecturer_name', $lecturer_name);
    }

    public function view_violation_content_process() {
        $this->uses = array('Violate');


        $id = $this->request->params['named']['id'];
        if ($this->checkContainKey($this->data, "accept")) {
            $res = $this->Violate->find('all', array('conditions' => array('Violate.id' => $id),
                "Violate.id" => $id
            ));
            $res[0]['Violate']['accepted'] = 1;
            $notify = "この違犯リポットを確認しました";
            $this->Violate->saveAll($res);
        } else {
            $notify = "この違犯リポットを削除しました";
            $this->Violate->delete($id);
        }

        $this->Session->setFlash(__($notify), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-success'
        ));
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
        //debug($res);
        $lesson_id = $res[0]['Document']['lesson_id'];

        $this->uses = array('Lesson');
        $res = $this->Lesson->find('all', array('conditions' => array('Lesson.id' => $lesson_id),));
        if(!$res)
        if ($lecturer_id == $res[0]['Lesson']['lecturer_id'])
            return 1;
        return 0;
    }

}
?>
