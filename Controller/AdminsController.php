<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AdminsController extends AppController {

    //public $components = array('RequestHandler');
    public $components = array('Paginator');
    public $helpers = array('Js');
    var $uses = array('Admin', 'IpAdmin', 'Lecturer', 'User', 'Student', 'Parameter');

    public function beforeFilter() {
        if($this->Auth->loggedIn() && $this->Auth->user('role') != 'admin')
            $this->redirect(array('controller' => 'users', 'action' => 'permission'));
        // if($this->Auth->loggedIn() && $this->Auth->user('actived') != 1)
        //     $this->redirect(array('controller' => 'users', 'action' => 'deactive'));
    }

    public function index() {
        
    }

//以下はipアドレス管理の機能だ    
//    
    public function add_ip_address() {
        $id = $this->Auth->user('id');
        //$this->Session->write('id', '911');
        //$id = $this->Session->read('id');
        //echo $id;
        if ($this->request->is('post')) {
            //echo $this->Session->read('id');
            $this->loadModel('IpAdmin');
            //echo $this->request->data['add']['ip_address'];
            $ip_address = $this->request->data['add']['ip_address'];
            $this->IpAdmin->set(array('ip_address' => $ip_address));
            //check empty
            if ($ip_address == NULL) {
                $this->Session->setFlash(__('IPアドレスが空しい'));
                //check exist
            } else if ($this->IpAdmin->query("SELECT * FROM ip_admins WHERE admin_id = '$id' and ip_address = '$ip_address'") != NULL) {
                //$this->Session->setFlash(__('IPアドレスが存在した'));
                $this->Session->setFlash(__('IPアドレスが存在した'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                ));
                //check format    
            } else if ($this->IpAdmin->validates()) {
                $sql = "INSERT INTO ip_admins VALUES('$id','$ip_address')";
                $this->IpAdmin->query($sql);
            } else {
                //$this->Session->setFlash(__('IPアドレスのフォーマットが正しくない'));
                $this->Session->setFlash(__('IPアドレスのフォーマットが正しくない'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                ));
            }
        }
        $datas = $this->IpAdmin->findAllByAdmin_id($id);
        $this->set('data', $datas); // truyen du lieu cho view tung ung voi ten function
    }

    public function edit_ip_address() {
        $id = $this->Auth->user('id');
        $this->loadModel('IpAdmin');
        if ($this->request->is('post')) {
            $old_ip_address = $this->request->data['edit']['old_ip_address'];
            $new_ip_address = $this->request->data['edit']['new_ip_address'];
            $this->IpAdmin->set(array('ip_address' => $new_ip_address));
            //debug($this->IpAdmin);die;
            //check exist
            if ($this->IpAdmin->query("SELECT * FROM ip_admins WHERE admin_id = '$id' and ip_address = '$new_ip_address'") != NULL) {
                //$this->Session->setFlash(__('IPアドレスが存在した'));
                $this->Session->setFlash(__('IPアドレスが存在した'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                ));
                //check format    
            } else if ($this->IpAdmin->validates()) {
                //echo $old_ip_address;
                //echo $new_ip_address;
                $sql = "UPDATE ip_admins SET ip_address = '$new_ip_address' WHERE admin_id = '$id' and ip_address = '$old_ip_address' ";
                //echo "ok";
                if (!$this->IpAdmin->query($sql)) {
                    $this->redirect(array('controller' => 'admins', 'action' => 'add_ip_address')); // chuyen ve View/Admin/add_ip_address.ctp
                }
            } else {
                //echo "loi";
                //$this->Session->setFlash(__('IPアドレスのフォーマットが正しくない'));
                $this->Session->setFlash(__('IPアドレスのフォーマットが正しくない'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                ));
            }
        }
    }

    public function delete_ip_address($ip_address) {
        $id = $this->Auth->user('id');
        $sql = "DELETE FROM ip_admins WHERE (admin_id = '$id' and ip_address = '$ip_address')";
        $this->IpAdmin->query($sql);
        $this->redirect(array('action' => 'add_ip_address')); // chuyen ve View/Admin/add_ip_address.ctp
    }

//以下は先生管理の機能だ
    public function manage_lecturer() {
        $this->loadModel('User');
        $this->loadModel('Lecturer');
        if ($this->request->is('post')) {
            $this->set('data', NULL);
            $username = $this->request->data["search"]["username"];
            //echo $username;
            //die();
            if ($username != NULL) {
                $sql1 = "SELECT * FROM lecturers, users WHERE (lecturers.id = users.id and 
                    users.username = '$username')";

                $data = $this->Lecturer->User->query($sql1);
                if ($data != NULL) {
                    $this->set('data', $data);
                    // $this->redirect(array('action'=>'manage_lecturer'));
                } else {
                    $this->Session->setFlash(__('見つけない'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-warning'
                    ));
                    //$this->redirect(array('action'=>'manage_lecturer'));
                }
            } else {
                $sql2 = "SELECT * FROM lecturers, users WHERE (lecturers.id = users.id and users.role = 'lecturer')";
                //$sql = "SELECT * FROM users";
                $data = $this->Lecturer->User->query($sql2);
                //var_dump($data);
                if ($data == NULL) {
                    $this->Session->setFlash(__('ダータがない'));
                } else {
                    $this->set('data', $data);
                }
            }
        } else {
            $sql2 = "SELECT * FROM lecturers, users WHERE (lecturers.id = users.id and users.role = 'lecturer')";
            //$sql = "SELECT * FROM users";
            $data = $this->Lecturer->User->query($sql2);
            //var_dump($data);
            $this->set('data', $data);
            if ($data == NULL) {
                $this->Session->setFlash(__('ダータがない'));
            } else {
                $this->set('data', $data);
            }
        }
    }

    public function view_infor_lecturer($id_lecturer) {
        //echo $id_lecturer;
        $this->loadModel('User');
        $this->loadModel('Lecturer');
        $sql = "SELECT * FROM Lecturers, Users WHERE (Lecturers.id = Users.id and Users.id = '$id_lecturer')";
        $infor = $this->Lecturer->User->query($sql);
        //echo "<pre>";
        //var_dump($infor);
        $this->set('infor', $infor);
    }

    public function unlock_lecturer($id_lecturer) {
        $this->loadModel('User');
        $sql = "UPDATE users SET actived = 1 WHERE users.id = '$id_lecturer'";
        $result = $this->User->query($sql);
        $this->redirect(array('action' => 'manage_lecturer'));
    }

    public function lock_lecturer($id_lecturer) {
        $this->loadModel('User');
        $sql = "UPDATE users SET actived = 0 WHERE users.id = '$id_lecturer'";
        $result = $this->User->query($sql);
        $this->redirect(array('action' => 'manage_lecturer'));
    }

    public function reset_password_lecturer($id_lecturer, $init_password) {
        //echo $id;
        //echo $init_password;
        $this->loadModel('User');
        $sql = "UPDATE users SET password = '$init_password' WHERE users.id = '$id_lecturer'";
        if (!$this->User->query($sql)) {
            //$this->Session->setFlash(__('パスワードのリセットが成功された'));
            $this->Session->setFlash(__('パスワードのリセットが成功された'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
            $this->redirect(array('action' => 'manage_lecturer'));
        } else {
            //$this->Session->setFlash(__('パスワードのリセットができない'));
            $this->Session->setFlash(__('パスワードのリセットができない'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'
            ));
            $this->redirect(array('action' => 'manage_lecturer'));
        }
    }

    public function reset_verifycode_lecturer($id_lecturer, $init_verifycode) {
        //echo $id;
        //echo $init_password;
        $this->loadModel('Lecturer');
        $sql = "UPDATE Lecturers SET current_verifycode = '$init_verifycode' WHERE Lecturers.id = '$id_lecturer'";
        if (!$this->User->query($sql)) {
            //$this->Session->setFlash(__('verifycodeのリセットが成功された'));
            $this->Session->setFlash(__('verifycodeのリセットが成功された'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
            $this->redirect(array('action' => 'manage_lecturer'));
        } else {
            //$this->Session->setFlash(__('verifycodeのリセットができない'));
            $this->Session->setFlash(__('verifycodeのリセットができない'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'
            ));
            $this->redirect(array('action' => 'manage_lecturer'));
        }
    }

    public function delete_lecturer($id_lecturer) {
        $this->loadModel('Lecturer');
        if ($this->Lecturer->delete($id_lecturer)) {
            //$this->Session->setFlash(__('アカウントの削除が成功された'));        
            $this->Session->setFlash(__('アカウントの削除が成功された'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
            //$this->redirect(array('action'=>'manage_lecturer'));
        } else {
            //$this->Session->setFlash(__('アカウントの削除ができない'));
            //$notify ="アカウントの削除ができない";
            $this->Session->setFlash(__('アカウントの削除ができない'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'
            ));
        }

        $this->redirect(array('action' => 'manage_lecturer'));
    }

//以下は学生管理の機能だ
    public function manage_student() {
        $this->loadModel('User');
        $this->loadModel('Student');
        if ($this->request->is('post')) {
            $this->set('data', NULL);
            $username = $this->request->data["search"]["username"];
            //echo $username;
            //die();
            if ($username != NULL) {
                $sql1 = "SELECT * FROM Students, Users WHERE (Students.id = Users.id and 
                    Users.username = '$username')";
                //$sql = "SELECT * FROM users";
                $data = $this->Student->User->query($sql1);
                if ($data != NULL) {
                    $this->set('data', $data);
                    // $this->redirect(array('action'=>'manage_lecturer'));
                } else {
                    $this->Session->setFlash(__('見つけない'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-warning'
                    ));
                    //$this->redirect(array('action'=>'manage_lecturer'));
                }
            } else {
                $sql = "SELECT * FROM Students, Users WHERE (Students.id = Users.id and Users.role = 'student')";
                //$sql = "SELECT * FROM users";
                $data = $this->Student->User->query($sql);
                //$data = $this->Admin->printfStudent();
                if ($data == NULL) {
                    $this->Session->setFlash(__('ダータがない'));
                } else {
                    $this->set('data', $data);
                }
            }
        } else {
            $sql = "SELECT * FROM Students, Users WHERE (Students.id = Users.id and Users.role = 'student')";
            //$sql = "SELECT * FROM users";
            $data = $this->Student->User->query($sql);
            //$data = $this->Admin->printfStudent();
            if ($data == NULL) {
                $this->Session->setFlash(__('ダータがない'));
            } else {
                $this->set('data', $data);
            }
        }
    }

    public function view_infor_student($id_student) {
        //echo $id_lecturer;
        $this->loadModel('User');
        $this->loadModel('Students');
        $sql = "SELECT * FROM Students, Users WHERE (Students.id = Users.id and Users.id = '$id_student')";
        $infor = $this->Lecturer->User->query($sql);
        //echo "<pre>";
        //var_dump($infor);
        $this->set('infor', $infor);
    }

    public function unlock_student($id_student) {
        $this->loadModel('User');
        $sql = "UPDATE users SET actived = 1 WHERE users.id = '$id_student'";
        $result = $this->User->query($sql);
        $this->redirect(array('action' => 'manage_student'));
    }

    public function lock_student($id_student) {
        $this->loadModel('User');
        $sql = "UPDATE users SET actived = 0 WHERE users.id = '$id_student'";
        $result = $this->User->query($sql);
        $this->redirect(array('action' => 'manage_student'));
    }

    public function reset_password_student($id_student, $init_password) {
        //echo $id;
        //echo $init_password;
        $this->loadModel('User');
        $sql = "UPDATE users SET password = '$init_password' WHERE users.id = '$id_student'";
        if (!$this->User->query($sql)) {
            //$this->Session->setFlash(__('パスワードのリセットが成功された'));
            $this->Session->setFlash(__('パスワードのリセットが成功された'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
            $this->redirect(array('action' => 'manage_student'));
        } else {
            //$this->Session->setFlash(__('パスワードのリセットができない'));
            $this->Session->setFlash(__('パスワードのリセットが成功された'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'
            ));
            $this->redirect(array('action' => 'manage_student'));
        }
    }

    public function reset_verifycode_student($id_student, $init_verifycode) {
        //echo $id;
        //echo $init_password;
        $this->loadModel('Student');
        $sql = "UPDATE Students SET current_verifycode = '$init_verifycode' WHERE   Students.id = '$id_student'";
        if (!$this->User->query($sql)) {
            //$this->Session->setFlash(__('verifycodeのリセットが成功された'));
            $this->Session->setFlash(__('verifycodeのリセットが成功された'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
            $this->redirect(array('action' => 'manage_student'));
        } else {
            //$this->Session->setFlash(__('verifycodeのリセットができない'));
            $this->Session->setFlash(__('verifycodeのリセットができない'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'
            ));
            $this->redirect(array('action' => 'manage_student'));
        }
    }

    public function delete_student($id_student) {
        $this->loadModel('Student');
        if ($this->Student->delete($id_student)) {
            //$this->Session->setFlash(__('アカウントの削除が成功された'));
            $this->Session->setFlash(__('アカウントの削除が成功された'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
        } else {
            //$this->Session->setFlash(__('アカウントの削除ができない'));
            $this->Session->setFlash(__('アカウントの削除ができない'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'
            ));
            //$this->redirect(array('action'=>'manage_student'));
        }
        $this->redirect(array('action' => 'manage_student'));
    }

//以下はシステム仕様の管理の機能だ    
    public function manage_parameter() {

        if ($this->request->is('post')) {
            $LESSON_COST = $this->request->data['parameter']['lesson_cost'];
            $LECTURER_MONEY_PERCENT = $this->request->data['parameter']['lecturer_money_percent'];
            $ENABLE_LESSON_TIME = $this->request->data['parameter']['enable_lesson_time'];
            $WRONG_PASSWORD_TIMES = $this->request->data['parameter']['wrong_password_times'];
            $LOCK_TIME = $this->request->data['parameter']['lock_time'];
            $SESSION_TIME = $this->request->data['parameter']['session_time'];
            $VIOLATIONS_TIMES = $this->request->data['parameter']['violations_times'];
            $error = "";
            $this->Session->setFlash($error);
            $flash = 1;
            $this->Parameter->set(array('value' => $LESSON_COST));
            if (!$this->Parameter->validates())
                $flash = 0;
            $this->Parameter->set(array('value' => $LECTURER_MONEY_PERCENT));
            if (!$this->Parameter->validates())
                $flash = 0;
            $this->Parameter->set(array('value' => $ENABLE_LESSON_TIME));
            if (!$this->Parameter->validates())
                $flash = 0;
            $this->Parameter->set(array('value' => $WRONG_PASSWORD_TIMES));
            if (!$this->Parameter->validates())
                $flash = 0;
            $this->Parameter->set(array('value' => $LOCK_TIME));
            if (!$this->Parameter->validates())
                $flash = 0;
            $this->Parameter->set(array('value' => $SESSION_TIME));
            if (!$this->Parameter->validates())
                $flash = 0;
            $this->Parameter->set(array('value' => $VIOLATIONS_TIMES));
            if (!$this->Parameter->validates())
                $flash = 0;
            if ($flash) {
                if ($LESSON_COST < 0) {
                    $error = $error . "課金の金額 >= 0\n";
                } else {
                    $this->Parameter->updateParameter('LESSON_COST', $LESSON_COST);
                }
                if ($LECTURER_MONEY_PERCENT > 100 || $LECTURER_MONEY_PERCENT < 0) {
                    $error = $error . "<br>100 >= 先生に支払った課金 >= 0</br>";
                } else {
                    $this->Parameter->updateParameter('LECTURER_MONEY_PERCENT', $LECTURER_MONEY_PERCENT);
                }
                if ($ENABLE_LESSON_TIME <= 0) {
                    $error = $error . "<br>受講可能の時間 > 0</br>";
                } else {
                    $this->Parameter->updateParameter('ENABLE_LESSON_TIME', $ENABLE_LESSON_TIME);
                }
                if ($WRONG_PASSWORD_TIMES <= 0) {
                    $error = $error . "<br>間違えるログインの回数 > =1</br>";
                } else {
                    $this->Parameter->updateParameter('WRONG_PASSWORD_TIMES', $WRONG_PASSWORD_TIMES);
                }
                if ($LOCK_TIME <= 0) {
                    $error = $error . "<br>ロック時間 > 0</br>";
                } else {
                    $this->Parameter->updateParameter('LOCK_TIME', $LOCK_TIME);
                }
                if ($SESSION_TIME <= 0) {
                    $error = $error . "<br>操作がない場合はセションが終了する時間 > 0</br>";
                } else {
                    $this->Parameter->updateParameter('SESSION_TIME', $SESSION_TIME);
                }
                if ($VIOLATIONS_TIMES <= 0) {
                    $error = $error . "<br>違犯時、アカウントを削除 >= 1</br>";
                } else {
                    $this->Parameter->updateParameter('VIOLATIONS_TIMES', $VIOLATIONS_TIMES);
                }
                //$this->Session->setFlash($error);
                $this->Session->setFlash($error, 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                ));
            } else {
                //$this->Session->setFlash(__('各仕様のタイプが数字だ'));
                $this->Session->setFlash(__('各仕様のタイプが数字だ'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                ));
                //$this->redirect($this->referer());
            }
        }



        $this->loadModel('Parameter');
        //$data = $this->Parameter->query("SELECT value FROM parameters WHERE name = 'LESSON_COST'");
        //$_LESSON_COST = $data[0]['parameters']['value'];
        $this->set('_LESSON_COST', $this->Parameter->getLessonCost());

        $this->set('_LECTURER_MONEY_PERCENT', $this->Parameter->getLecturerMoneyPercent());
        $this->set('_ENABLE_LESSON_TIME', $this->Parameter->getEnableLessonTime());
        $this->set('_WRONG_PASSWORD_TIMES', $this->Parameter->getWrongPasswordTimes());
        $this->set('_LOCK_TIME', $this->Parameter->getLockTime());
        $this->set('_SESSION_TIME', $this->Parameter->getSessionTime());
        $this->set('_VIOLATIONS_TIMES', $this->Parameter->getViolationsTimes());
    }

    //tha
    public function add_admin() {
        $this->uses = array('User', 'Admin');
        if ($this->request->is('post')) {
            $this->request->data["User"]["role"] = "admin";
            $this->request->data["User"]["actived"] = 1;
            if ($this->User->saveAll($this->request->data)) {

                $this->Session->setFlash(__('新しい管理者が追加された'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('controller' => 'admins', 'action' => 'add_admin'));
            }
            $this->Session->setFlash(__('新しい管理者を追加できない'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'
            ));
        }
    }

    public function remove_admin() {
        $this->uses = array('User', 'Admin');
        $this->paginate = array(
            'limit' => 10,
            'fields' => array(),
            'conditions' => array(
                'User.id !=' => $this->Auth->user('id'),
                "User.role" => "admin")
        );

        $this->Paginator->settings = $this->paginate;
        $res = $this->Paginator->paginate("User");
        $this->set('res', $res);
        //debug($res);
    }

    public function remove_admin_process($id) {
        if (!isset($id))
            $this->redirect(array("action" => "remove_admin"));
        $this->uses = array('User', 'Admin');
        if ($this->User->delete($id))
            $this->Session->setFlash(__('管理者が削除された'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
        $this->redirect(array("action" => "remove_admin"));
    }

    public function view_violation() {

        $this->uses = array('Violate');

        $this->paginate = array(
            'limit' => 1,
            'fields' => array(),
            'group' => array('Violate.document_id'),
        );


        $this->Paginator->settings = $this->paginate;
        $res = $this->Paginator->paginate("Violate");

        $this->set('res', $res);
    }

    public function view_violation_content($id) {

        $this->uses = array('Violate');
        $this->paginate = array(
            'limit' => 1,
            'fields' => array(),
            'conditions' => array(
                "Violate.document_id" => $id)
        );


        $this->Paginator->settings = $this->paginate;

        $res = $this->Violate->find('all', array('conditions' => array('Violate.document_id' => $id),
        ));
        $this->set('result', $res);
        $this->set('document_id', $id);
    }

    public function view_violation_content_process() {
        $this->uses = array('Report');
        $document_id = $this->params['named']['id'];
        if ($this->checkContainKey($this->data, "accept")) {
            $data['Report']['document_id'] = $document_id;
            $data['Report']['state'] = 1;
            $this->Report->saveAll($data);
            $this->uses = array('Violate');
            $this->Violate->deleteAll(array('Violate.document_id' => $document_id), false);

            if ($this->checkDeleteDocument($document_id) == 1) {
                $this->uses = array('Document');
                $this->Document->delete($document_id);
            }
        } else {
            $this->uses = array('Violate');
            $this->Violate->deleteAll(array('Violate.document_id' => $this->params['named']['id']), false);
        }
    }

    public function fee_manager() {
//        // $date = date('Y-m-d');
//        $month = "0";
//        $year = "1970";
//        if ($this->request->is('post')) {
//            echo "dmmm";
//           // debug($this->data);
//            $month = $this->data['Fee']['month'];
//            $year = $this->data['Fee']['year'] + 1970;
//            echo "month =" . $month;//
//            $this->uses = array('LessonMembership');
//            $res = $this->LessonMembership->find('all');
//            $this->uses = array('Student');
//            $student_list = $this->Student->find('all', array('condition' => array('actived' => 1)));
//            $this->uses = array('LessonMembership'); //$student['Student']['id'];
//            foreach ($student_list as $student) {
//                $count = 0;
//                $result = $this->LessonMembership->find('all', array('conditions' => array('LessonMembership.student_id' => $student['Student']['id'])));
//
//                foreach ($result as $res) {
//                    $date = $res['LessonMembership']['days_attended'];
//                    if ($this->getYear($date) == $year && $this->getMonth($date) == $month)
//                        $count++;
//                }
//                $counts[$student['Student']['id']] = $count;
//            }
//            $this->set('student_list', $student_list);
//            $this->set('counts', $counts);
//            $this->paginate = array(
//                'limit' => 2,
//                'fields' => array(),
//            );
//            $this->Paginator->settings = $this->paginate;
//            $this->set("year", $year);
//            $this->set("month", $month);
//        }
//        echo "why";
        
        
        date_default_timezone_set('Asia/Saigon');
         $date = date('Y:m:d H:i:s');
        $month = $this->getMonth($date);
        $year = $this->getYear($date);
        if($month == 1) 
        {
            $month = 12;$year--;
        }
        else
            $month--;
        
        $this->set("year", $year);
        $this->set("month", $month);
        if($month<10)$month = "0".$month;
        $name = "ELS-UBT-".$year . "-".$month.".tsv";
        $File = "tsv\\fee\\" . $name;
        $this->set('exit',file_exists($File));
        $bool = file_exists($File);
        if($bool == true)$this->Session->setFlash(__($year.'年'.$month.'月のTSVを作成しました'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
        
       // debug($this->Auth->user);
         $this->getStudentFee($year,$month);
       
    }
    
    private function getStudentFee($year,$month)
    {        
            $this->uses = array('LessonMembership');
            $res = $this->LessonMembership->find('all');
            $this->uses = array('Student');
            $student_list = $this->Student->find('all', array('condition' => array('actived' => 1)));
            $this->uses = array('LessonMembership'); //$student['Student']['id'];
            
            $student_list_ = array();
            $i = 0 ;
            foreach ($student_list as $student) {
                $count = 0;
                $result = $this->LessonMembership->find('all', array('conditions' => array('LessonMembership.student_id' => $student['Student']['id'])));

                foreach ($result as $res) {
                    $date = $res['LessonMembership']['days_attended'];
                    if ($this->getYear($date) == $year && $this->getMonth($date) == $month)
                        $count++;
                }
                $student['count'] = $count;
                $student_list_[$i] = $student;
                $i++;
            }
            $this->set('student_list', $student_list);            
            $this->paginate = array(
                'limit' => 2,
                'fields' => array(),
            );
        //debug($student_list_);    
        return $student_list_;
        
    }
    
    
    private function getLecturerFee($year,$month)
    {        
            $this->uses = array('LessonMembership');
            $res = $this->LessonMembership->find('all');
            $this->uses = array('Lecturer');
            $lecturer_list = $this->Lecturer->find('all', array('condition' => array('actived' => 1)));
            $this->uses = array('LessonMembership'); //$student['Student']['id'];
            
            $lecturer_list_ = array();
            $i = 0 ;
           // debug($lecturer_list);
            foreach ($lecturer_list as $lecturer) {
               // echo "id =".$lecturer['Lecturer']['id'];
                $count = 0; 
                $this->uses = array('Lesson');
                $lesson_list = $this->Lesson->find('all', array('condition' => array('Lesson.lecturer_id' => 11)));
                //debug($lesson_list);
                $this->uses = array('LessonMembership');
                $result = $this->LessonMembership->find('all');                
                foreach ($result as $res) {
                    $date = $res['LessonMembership']['days_attended'];
                    if ($this->getYear($date) == $year && $this->getMonth($date) == $month)
                        foreach($lesson_list as $lesson)
                    {
                        if($lesson['Lesson']['id'] == $res['Lesson']['id'])
                        $count++;
                    }
                        
                }
                $lecturer['count'] = $count;
                $lecturer_list_[$i] = $lecturer;
                $i++;
            }
            $this->set('$lecturer_list', $lecturer_list_);            
            $this->paginate = array(
                'limit' => 2,
                'fields' => array(),
            );
            
                $this->uses = array('Lesson');
                $lesson_list = $this->Lesson->find('all', array('conditions' => array('Lesson.lecturer_id' => 10)));
                debug($lesson_list);
            
            
        //debug($student_list_);    
        //debug($lecturer_list_);
        return $lecturer_list_;
        
    }
    

    private function getCurrentYear() {

        $date = date('Y-m-d');
        $years = array();
        for ($i = 1970; $i < $date; $i++) {
            $years[$i - 1970] = $i;
        }
       // debug($years);
        return $years;
    }

    private function getMonthSet() {
        $months = array();
        $months[0] = "January";
        $months[1] = "February";
        $months[2] = "March";
        $months[3] = "April";
        $months[4] = "May";
        $months[5] = "June";
        $months[6] = "July";
        $months[7] = "August";
        $months[8] = "September";
        $months[9] = "October";
        $months[10] = "November";
        $months[11] = "December";


        debug($months);
        return $months;
    }

    public function generate_tsv($year, $month) {
        $this->set('year',$year);
        $this->set('month',$month);
        
        
        date_default_timezone_set('Asia/Saigon');
        $date = date('Y:m:d H:i:s');
        echo $date;
        mb_internal_encoding('UTF-8');
        header('Content-Type: text/html;charset=utf-8');        
        if($month<10)$month = "0".$month;
        $name = "ELS-UBT-".$year . "-".$month.".tsv";
        $File = "tsv\\fee\\" . $name;
        $Handle = fopen($File, "w");
        $data = "".mb_convert_kana("ELS-UBT-GWK5M78", "rnaskhcv")."\t";
        echo "<br>".$data;
        $data.= mb_convert_kana($year, "rnaskhcv")."\t";
        if($month<10)$data.= mb_convert_kana("0".$month, "rnaskhcv")."\t";
            else $data.= mb_convert_kana($month, "rnaskhcv")."\t";
            
        $data.= mb_convert_kana($this->getYear($date), "rnaskhcv")."\t";
        $data.= mb_convert_kana($this->getMonth($date), "rnaskhcv")."\t";
        $data.= mb_convert_kana($this->getDate($date), "rnaskhcv")."\t";
        $data.= mb_convert_kana($this->getHour($date), "rnaskhcv")."\t";
        $data.= mb_convert_kana($this->getMinus($date), "rnaskhcv")."\t";
        $data.= mb_convert_kana($this->getSecond($date), "rnaskhcv")."\t";
        $student_list = $this->getStudentFee($year, $month);
        
        foreach($student_list as $student)
        {
        $data.= "\n";
        $data.= mb_convert_kana($student['Student']['id'], "rnaskhcv")."\t";   
        $data.= mb_convert_kana($student['Student']['full_name'], "rnaskhcv")."\t"; 
        $data.= mb_convert_kana($student['count']*20000, "rnaskhcv")."\t";
       
        $data.= mb_convert_kana($student['Student']['address'], "rnaskhcv")."\t"; 
        $phone_number= mb_convert_kana($student['Student']['phone_number'], "rnaskhcv")."\t"; 
        $phone_number = substr_replace($phone_number,'-', 3, 0);
        $phone_number = substr_replace($phone_number,'-', 7, 0);
        $data.= $phone_number."\t";
        $data.= mb_convert_kana("18", "rnaskhcv")."\t";
        $credit_number = mb_convert_kana($student['Student']['credit_card_number'], "rnaskhcv")."\t";
        $credit_number = substr_replace($credit_number,'-', 3, 0);
        $credit_number = substr_replace($credit_number,'-', 8, 0);
        $credit_number = substr_replace($credit_number,'-', 13, 0);
        $credit_number = substr_replace($credit_number,'-', 18, 0);
        $credit_number = substr_replace($credit_number,'-', 23, 0);
        $data.= $credit_number."\t";
        }
        $data.= "\n";
        $this->getLecturerFee($year, $month);
        
        
        $data.= mb_convert_kana("END___END___END", "rnaskhcv")."\t";
        // $data.= "END___END___END\t";
        $data.= mb_convert_kana($year, "rnaskhcv")."\t";
        if($month<10)$data.= mb_convert_kana("0".$month, "rnaskhcv")."\t";
            else $data.= mb_convert_kana($month, "rnaskhcv")."\t";
        
                
        fprintf($Handle,$data );
        fclose($Handle);        
        return $this->redirect(array('controller' => 'admins', 'action' => 'fee_manager'));
    }

   
    private function monthToString($month) {
        if ($month == "January")
            return 1;
        if ($month == "February")
            return 2;
        if ($month == "March")
            return 3;
        if ($month == "April")
            return 4;
        if ($month == "May")
            return 5;
        if ($month == "June")
            return 6;
        if ($month == "July")
            return 7;
        if ($month == "August")
            return 8;
        if ($month == "September")
            return 9;
        if ($month == "October")
            return 10;
        if ($month == "November")
            return 11;
        if ($month == "December")
            return 12;
        return 1;
    }

    public function ranking_lecturer() {

        if ($this->request->is('post')) {
            // debug($this->data);
            $ranking = $this->data['ranking']['choose_ranking'];
            $this->set('ranking', $ranking);
        }

        $this->uses = array('LessonMembership');
        $lessons = $this->LessonMembership->find('all', array('conditions' => array('LessonMembership.baned' => false,
                'LessonMembership.liked' => true)));
        ;
        //debug($lessons);
        // $less[] += $lessions
        $this->uses = array('User', 'Lecturer');
        $lecturers = $this->User->find('all', array('conditions' => array('User.role' => 'lecturer', 'User.actived' => 1)
        ));
        ;
        $i = 0;
        $teachers = array();
        foreach ($lecturers as $lecturer) {
            $teachers[$i]['id'] = $lecturer['User']['id'];
            $teachers[$i]['name'] = $lecturer['User']['username'];
            $this->uses = array('Lesson');
            $lessons_ = $this->Lesson->find('all', array('conditions' => array('Lesson.lecturer_id' => $teachers[$i]['id'])
            ));

            $teachers[$i]['count_like'] = $this->countLesson($lessons_, $lessons);

            //debug($teachers[$i]);
            $i++;
        }

                
        $teachers = $this->array_sort($teachers, 'count_like', SORT_DESC);
         if(!isset($ranking)||$ranking == 0 )
         {
             
             
         }
             
        $this->set('lessons', $this->getRankingLesson());     
        $this->set('teachers', $teachers);
        
    }

    private function countLesson($lessons, $all_lessons) {
        $count = 0;

        foreach ($lessons as $lesson) {
            foreach ($all_lessons as $all_lesson) {
                if ($lesson['Lesson']['id'] == $all_lesson['Lesson']['id'])
                    $count++;
            }
        }
        return $count;
    }

    private function countLikeLesson($lesson, $all_lessons) {


        $count = 0;

        foreach ($all_lessons as $all_lesson) {
            if ($lesson['Lesson']['id'] == $all_lesson['Lesson']['id'])
                $count++;
        }
        return $count;
    }

    private function getRankingLesson() {
        $this->uses = array('Lesson');
        $lessons = $this->Lesson->find('all');

        $this->uses = array('LessonMembership');
        $all_lessons = $this->LessonMembership->find('all', array('conditions' => array('LessonMembership.baned' => false,
                'LessonMembership.liked' => true)));

        $lessons_data = array();
        $i = 0;
        foreach ($lessons as $lesson) {
            $lessons_data[$i]['count_like'] = $this->countLikeLesson($lesson, $all_lessons);
            $lessons_data[$i]['id'] = $lesson['Lesson']['id'];
            $lessons_data[$i]['name'] = $lesson['Lesson']['name'];
            $i++;
        }

        $lessons = $this->array_sort($lessons_data, 'count_like', SORT_DESC);
        // debug($lessons);        
        return $lessons;
    }

    private function array_sort($array, $on, $order = SORT_ASC) {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }
            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

    private function getYear($date) {

        $year = "";

        for ($i = 0; $i < 4; $i++) {
            $year = $year . $date[$i];
        }

        return $year;
    }

    private function getMonth($date) {
        $month = "";

        for ($i = 5; $i < 7; $i++) {
            $month = $month . $date[$i];
        }

        return $month;
    }
    
    private function getDate($date) {
        $date_ = "";

        for ($i = 8; $i < 10; $i++) {
            $date_ = $date_ . $date[$i];
        }

        return $date_;
    }
    
    private function getSecond($date) {

        $second = "";

        for ($i = 17; $i < 19; $i++) {
            $second = $second . $date[$i];
        }

        return $second;
    }

    private function getHour($date) {
        $hour = "";

        for ($i = 11; $i < 13; $i++) {
            $hour = $hour . $date[$i];
        }

        return $hour;
    }
    
    private function getMinus($date) {
        $minus = "";

        for ($i = 14; $i < 16; $i++) {
            $minus = $minus . $date[$i];
        }

        return $minus;
    }

    private function checkSecond($param, $_key) {

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

    private function checkDeleteDocument($document_id) {
        $res = $this->Checkviolate->find('all', array('conditions' => array('Checkviolate.document_id' => $document_id),
        ));
        $count = 0;
        foreach ($res as $record) {
            $count++;
        }

        echo "count = " . $count;
        if ($count >= 3)
            return 1;
        return 0;
    }


    public function database_manager(){
    $dir = new Folder(WWW_ROOT.'files/db');
    $dir->chmod(WWW_ROOT.'files/db',0777, true, array());
    $files = $dir->find('.*\.sql');
    $files_info = array();
    foreach ($files as $file_name) {
        $file = new File($dir->pwd().DS.$file_name);
        $info = $file->info();
        $info['created_date'] = date('H:i:s - d/m/Y ', $file->lastChange());
        $info['created_time'] = $file->lastChange();
        array_push($files_info, $info);   
    }
     $price = array();
    foreach ($files_info as $key => $row)
    {
    $price[$key] = $row['created_time'];
    }
    array_multisort($price, SORT_DESC, $files_info);
    $this->set(compact('files_info'));
    }

    public function backup_database() {
    $this->autoRender = false;
    $databaseName = 'elearning';
    $fileName = WWW_ROOT.'files/db/'.$databaseName . '-backup-' . date('Y-m-d_H-i-s') . '.sql';
    echo exec('whoami && which mysqldump');

    $command = "/home/action/.parts/bin/mysqldump --opt --skip-extended-insert --complete-insert --host=localhost --user=root --password=tuananh elearning > ".$fileName;
    exec($command);
    $this->Session->setFlash(__('Database has been backuped'), 'alert', array(
        'plugin' => 'BoostCake',
        'class' => 'alert-success'));
    $this->redirect(array('controller' => 'admins', 'action' => 'database_manager'));
    }

    public function delete_file(){
        $this->autoRender = false;
        if(isset($this->params['named']['file'])){
            $source = WWW_ROOT.'files/db/'.$this->params['named']['file'];
            var_dump($source);
            unlink($source);
        }
        $this->Session->setFlash(__('The backup have been deleted'), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-warning'));
        $this->redirect(array('controller' => 'admins', 'action' => 'database_manager'));
    }   

    public function delete_all(){

    $this->autoRender = false;
    $dir = new Folder(WWW_ROOT.'files/db');
    $dir->chmod(WWW_ROOT.'files/db',0777, true, array());
         $files = $dir->find('.*\.sql');
         foreach ($files as $file) {
            unlink($dir->pwd().DS.$file);
         }
         $this->Session->setFlash(__('All The backup have been deleted'), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-warning'));
         $this->redirect(array('controller' => 'admins', 'action' => 'database_manager'));
    }

    public function restore_database() {
        $this->autoRender = false;
        if(isset($this->params['named']['file'])){
            $mysql_host = 'localhost';
            $mysql_username = 'root';
            $mysql_password = 'tuananh';
            $db_name = 'elearning';
            $source = WWW_ROOT.'files/db/'.$this->params['named']['file'];
            $command ="/home/action/.parts/bin/mysql -u root -ptuananh elearning < ".$source ;
            var_dump($command);
            exec($command);
        }
        $this->redirect(array('controller' => 'admins', 'action' => 'database_manager'));
    }

}
?>


