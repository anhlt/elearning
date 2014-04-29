<?php

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::import('Controller', 'Lesson'); // mention at top

class AdminsController extends AppController {

    public $components = array('Paginator', 'Util', 'RequestHandler', 'Message');
    public $helpers = array('Js');
    var $uses = array('Admin', 'IpAdmin', 'Lecturer', 'User', 'Student', 'Parameter', 'Question', 'Document', 'Violate', 'Lesson', 'Ihan', 'Test', 'Comment');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('login');
        if ($this->Auth->loggedIn() && $this->Auth->user('role') != 'admin')
            $this->redirect(array('controller' => 'users', 'action' => 'permission'));
    }

    public function index() {
        
    }

    public function login() {
        if ($this->Auth->loggedIn()) {
            $this->redirect('/');
        }
        if ($this->request->is('post')) {
            $data = ($this->request->data);
            $user = $this->User->findByUsername($data['User']['username']);
            if ($user['User']['role'] != 'admin') {
                $this->Session->setFlash(__('ユーザ�?��?��?�画�?��?�ロクイン�?��??�?��?�'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                ));
                $this->redirect(array('controller' => 'admins', 'action' => 'login'));
            }

            if ($this->Auth->login()) {
                $id = $this->Auth->user('id');
                $this->User->recursive = 3;
                $user = $this->User->findById($id);
                $has_ip = 0;
                foreach ($user['Admin']['IpAdmin'] as $ip) {
                    if ($this->request->ClientIp() == $ip['ip_address'])
                        $has_ip = 1;
                };

                if ($has_ip == 0) {
                    $this->Session->setFlash(__('IPアドレスが違う'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-warning'
                    ));
                    $this->Auth->logout();
                    $this->redirect(array('controller' => 'admins', 'action' => 'login'));
                }

                $this->redirect(array('controller' => 'Admins'));
            }
            else
                $this->Session->setFlash(__('ユーザ名、パスワードが正しくない'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                ));
        }
    }

    public function add_ip_address() {
        $id = $this->Auth->user('id');
        if ($this->request->is('post')) {
            //echo $this->Session->read('id');
            $this->loadModel('IpAdmin');
            //echo $this->request->data['add']['ip_address'];
            $ip_address = $this->request->data['add']['ip_address'];
            $this->IpAdmin->set(array('ip_address' => $ip_address));
            //check empty
            if ($ip_address == NULL) {
                $this->Session->setFlash(__('IPアドレス�?�空�?��?�'));
                //check exist
            } else if ($this->IpAdmin->query("SELECT * FROM ip_admins WHERE admin_id = '$id' and ip_address = '$ip_address'") != NULL) {
                //$this->Session->setFlash(__('IPアドレス�?�存在�?��?�'));
                $this->Session->setFlash(__('IPアドレス�?�存在�?��?�'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                ));
                //check format
            } else if ($this->IpAdmin->validates()) {
                $sql = "INSERT INTO ip_admins(admin_id,ip_address) VALUES('$id','$ip_address')";
                $this->IpAdmin->query($sql);
            } else {
                $this->Session->setFlash(__('IPアドレス�?�フォーマット�?�正�?��??�?��?�'), 'alert', array(
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
                //$this->Session->setFlash(__('IPアドレス�?�存在�?��?�'));
                $this->Session->setFlash(__('IPアドレス�?�存在�?��?�'), 'alert', array(
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
                //$this->Session->setFlash(__('IPアドレス�?�フォーマット�?�正�?��??�?��?�'));
                $this->Session->setFlash(__('IPアドレス�?�フォーマット�?�正�?��??�?��?�'), 'alert', array(
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

//以下�?�先生管�?��?�機能�?�
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
                    users.username like '%$username%')";


                $data = $this->Lecturer->User->query($sql1);
                if ($data != NULL) {
                    $this->set('data', $data);
                    // $this->redirect(array('action'=>'manage_lecturer'));
                } else {
                    $this->Session->setFlash(__('見�?��?��?��?�'), 'alert', array(
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
                    $this->Session->setFlash(__('ダータ�?��?��?�'));
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
                $this->Session->setFlash(__('ダータ�?��?��?�'));
            } else {
                $this->set('data', $data);
            }
        }
    }

    public function view_infor_lecturer($lecturer_id) {
        if (empty($this->request->data)) {
            $this->request->data = $this->Lecturer->findById($lecturer_id);
            $this->request->data['Lecturer']['question_verifycode'] = base64_decode($this->request->data['Lecturer']['question_verifycode']);
            $this->request->data['Lecturer']['current_verifycode'] = base64_decode($this->request->data['Lecturer']['current_verifycode']);
        } else {
            $this->request->data['Lecturer']['id'] = $lecturer_id;
            if ($this->Lecturer->save($this->request->data)) {
                $this->Session->setFlash(__('セーブ�?�れ�?�'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
            } else {
                $this->Session->setFlash(__('セーブ�?��??�?��?��?も�?�一度�?�願�?�'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                ));
            }
            return $this->redirect(array('action' => 'manage_lecturer'));
        }
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
        if (isset($id_lecturer) && isset($init_password)) {
            $this->loadModel('User');
            $sql = "UPDATE users SET password = '$init_password' WHERE users.id = '$id_lecturer'";
            $this->User->query($sql);
            //$this->Session->setFlash(__('パスワード�?�リセット�?��?功�?�れ�?�'));
            $this->Session->setFlash(__('パスワード�?�リセット�?��?功�?�れ�?�'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
            $this->redirect(array('action' => 'manage_lecturer'));
        } else {
            $this->Session->setFlash(__('パスワードのリセットができない'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'
            ));
            $this->redirect(array('action' => 'manage_lecturer'));
        }
    }

    public function reset_verifycode_lecturer($id_lecturer, $init_verifycode, $init_question) {
        if (isset($id_lecturer) && isset($init_verifycode) && isset($init_question)) {
            $this->loadModel('Lecturer');

            $sql = "UPDATE lecturers SET current_verifycode = '$init_verifycode', question_verifycode = '$init_question' WHERE lecturers.id = '$id_lecturer'";

            $this->User->query($sql);
            //$this->Session->setFlash(__('verifycodeのリセットが成功された'));
            $this->Session->setFlash(__('verifycodeのリセットが成功された'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
            $this->redirect(array('action' => 'manage_lecturer'));
        } else {
            $this->Session->setFlash(__('verifycodeのリセットができない'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'
            ));

            $this->redirect(array('action' => 'manage_lecturer'));
        }
    }

    public function delete_lecturer($id_lecturer) {
        $this->uses = array('Lecturer', 'User');
        if ($this->User->delete($id_lecturer)) {


            //$this->Session->setFlash(__('アカウントの削除が成功された'));
            $this->Session->setFlash(__('アカウントの削除が成功された'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
            //$this->redirect(array('action'=>'manage_lecturer'));
        } else {
            //$this->Session->setFlash(__('アカウント�?�削除�?��?��??�?��?�'));
            //$notify ="アカウント�?�削除�?��?��??�?��?�";
            $this->Session->setFlash(__('アカウント�?�削除�?��?��??�?��?�'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'
            ));
        }

        $this->redirect(array('action' => 'manage_lecturer'));
    }

//以下�?�学生管�?��?�機能�?�
    public function manage_student() {
        $this->loadModel('User');
        $this->loadModel('Student');
        if ($this->request->is('post')) {
            $this->set('data', NULL);
            $username = $this->request->data["search"]["username"];
            if ($username != NULL) {
                $sql1 = "SELECT * FROM students, users WHERE (students.id = users.id and
                    users.username like '%$username%')";
                $data = $this->Student->User->query($sql1);
                if ($data != NULL) {
                    $this->set('data', $data);
                } else {
                    $this->Session->setFlash(__('見�?��?��?��?�'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-warning'
                    ));
                }
            } else {
                $sql = "SELECT * FROM students, users WHERE (students.id = users.id and users.role = 'student')";
                $data = $this->Student->User->query($sql);
                if ($data == NULL) {
                    $this->Session->setFlash(__('ダータ�?��?��?�'));
                } else {
                    $this->set('data', $data);
                }
            }
        } else {
            $sql = "SELECT * FROM students, users WHERE (students.id = users.id and users.role = 'student')";
            $data = $this->Student->User->query($sql);
            if ($data == NULL) {
                $this->Session->setFlash(__('ダータ�?��?��?�'));
            } else {
                $this->set('data', $data);
            }
        }
    }

    public function view_infor_student($id_student) {

        $questions = $this->Question->find('all');
        $droplist = array();
        foreach ($questions as $question) {
            $droplist[$question['Question']['id']] = $question['Question']['question'];
        }
        $this->set('droplist', $droplist);
        if (empty($this->request->data)) {
            //$lecturer_id = $this->Auth->user('id');
            $this->request->data = $this->Student->findById($id_student);
            //var_dump($this->request->data);
        } else {
            $this->request->data['Student']['id'] = $id_student;
            //var_dump($this->request->data);
            if ($this->Student->save($this->request->data)) {
                $this->Session->setFlash(__('セーブ�?�れ�?�'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                //$this->redirect(array('controller' => 'Admin', 'action' => 'manage_lecturer'));
            } else {
                $this->Session->setFlash(__('セーブ�?��??�?��?��?も�?�一度�?�願�?�'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                ));
            }
        }
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
        if (isset($id_student) && isset($init_password)) {
            $this->loadModel('User');
            $sql = "UPDATE users SET password = '$init_password' WHERE users.id = '$id_student'";
            $this->User->query($sql);
            //$this->Session->setFlash(__('パスワード�?�リセット�?��?功�?�れ�?�'));
            $this->Session->setFlash(__('パスワード�?�リセット�?��?功�?�れ�?�'), 'alert', array(
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
        if (isset($id_student) && isset($init_verifycode)) {
            $this->loadModel('Student');
            $sql = "UPDATE students SET current_verifycode = '$init_verifycode' WHERE   students.id = '$id_student'";
            $this->User->query($sql);
            //$this->Session->setFlash(__('verifycode�?�リセット�?��?功�?�れ�?�'));
            $this->Session->setFlash(__('verifycode�?�リセット�?��?功�?�れ�?�'), 'alert', array(
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
        $this->uses = array('Student', 'User');
        if ($this->User->delete($id_student)) {

            //$this->Session->setFlash(__('アカウント�?�削除�?��?功�?�れ�?�'));
            $this->Session->setFlash(__('アカウント�?�削除�?��?功�?�れ�?�'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
        } else {
            //$this->Session->setFlash(__('アカウント�?�削除�?��?��??�?��?�'));
            $this->Session->setFlash(__('アカウント�?�削除�?��?��??�?��?�'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'
            ));
            //$this->redirect(array('action'=>'manage_student'));
        }
        $this->redirect(array('action' => 'manage_student'));
    }

//以下はシステム仕様の管理の機能だ

    public function manage_parameter() {
        //$this->Session->setFlash(NULL);

        if ($this->request->is('post')) {
            $LESSON_COST = $this->request->data['parameter']['lesson_cost'];
            $LECTURER_MONEY_PERCENT = $this->request->data['parameter']['lecturer_money_percent'];
            $ENABLE_LESSON_TIME = $this->request->data['parameter']['enable_lesson_time'];
            $WRONG_PASSWORD_TIMES = $this->request->data['parameter']['wrong_password_times'];
            $LOCK_TIME = $this->request->data['parameter']['lock_time'];
            $SESSION_TIME = $this->request->data['parameter']['session_time'];
            $VIOLATIONS_TIMES = $this->request->data['parameter']['violations_times'];
            $BACKUP_TIME = $this->request->data['parameter']['backup_time'];
            $error = "";
            //$this->Session->setFlash($error);
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
            $this->Parameter->set(array('value' => $BACKUP_TIME));
            if (!$this->Parameter->validates())
                $flash = 0;
            if ($flash) {
                if ($LESSON_COST < 0) {
                    $error = $error . "1回�?��?�講料 >= 0\n";
                } else {
                    $this->Parameter->updateParameter('LESSON_COST', $LESSON_COST);
                }
                if ($LECTURER_MONEY_PERCENT > 100 || $LECTURER_MONEY_PERCENT < 0) {
                    $error = $error . "<br>100％ >= 報酬％ >= 0％</br>";
                } else {
                    $this->Parameter->updateParameter('LECTURER_MONEY_PERCENT', $LECTURER_MONEY_PERCENT);
                }
                if ($ENABLE_LESSON_TIME <= 0) {
                    $error = $error . "<br>�?�講�?�能時間 > 0</br>";
                } else {
                    $this->Parameter->updateParameter('ENABLE_LESSON_TIME', $ENABLE_LESSON_TIME);
                }
                if ($WRONG_PASSWORD_TIMES <= 0) {
                    $error = $error . "<br>ログイン誤り回数 > =1</br>";
                } elseif (!ctype_digit($WRONG_PASSWORD_TIMES)) {
                    $error = $error . "<br>ログイン誤り回数は整数だ</br>";
                } else {
                    $this->Parameter->updateParameter('WRONG_PASSWORD_TIMES', $WRONG_PASSWORD_TIMES);
                }
                if ($LOCK_TIME <= 0) {
                    $error = $error . "<br>ロック時間 > 0</br>";
                } else {
                    $this->Parameter->updateParameter('LOCK_TIME', $LOCK_TIME);
                }
                if ($SESSION_TIME <= 0) {
                    $error = $error . "<br>自動セション終了時間 > 0</br>";
                } else {
                    $this->Parameter->updateParameter('SESSION_TIME', $SESSION_TIME);
                }
                if ($VIOLATIONS_TIMES <= 0) {

                    $error = $error . "<br>違犯の最大回数 >= 1　</br>";
                } elseif (!ctype_digit($VIOLATIONS_TIMES)) {
                    $error = $error . "<br>違犯の最大回数は整数だ</br>";
                } else {

                    $this->Parameter->updateParameter('VIOLATIONS_TIMES', $VIOLATIONS_TIMES);
                }
                if ($BACKUP_TIME <= 0) {
                    $error = $error . "バックアップ時刻 >０</br>";
                } else {
                    $this->Parameter->updateParameter('BACKUP_TIME', $BACKUP_TIME);
                }

                if ($error != '') {
                    $this->Session->setFlash($error, 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-warning'
                    ));
                } else {
                    $this->loadModel('Parameter');

                    $this->set('_LESSON_COST', $this->Parameter->getLessonCost());
                    $this->set('_LECTURER_MONEY_PERCENT', $this->Parameter->getLecturerMoneyPercent());
                    $this->set('_ENABLE_LESSON_TIME', $this->Parameter->getEnableLessonTime());
                    $this->set('_WRONG_PASSWORD_TIMES', $this->Parameter->getWrongPasswordTimes());
                    $this->set('_LOCK_TIME', $this->Parameter->getLockTime());
                    $this->set('_SESSION_TIME', $this->Parameter->getSessionTime());
                    $this->set('_VIOLATIONS_TIMES', $this->Parameter->getViolationsTimes());
                    $this->set('_BACKUP_TIME', $this->Parameter->getBackupTime());
                    $this->Session->setFlash(__('セーブされた'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-success'
                    ));
                }
            } else {

                //$this->Session->setFlash(__('各仕様のタイプが数字だ'));
                $this->Session->setFlash(__('各仕様のタイプが整数だ'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                ));
                //$this->redirect($this->referer());
            }
        }

        $this->loadModel('Parameter');

        $this->set('_LESSON_COST', $this->Parameter->getLessonCost());
        $this->set('_LECTURER_MONEY_PERCENT', $this->Parameter->getLecturerMoneyPercent());
        $this->set('_ENABLE_LESSON_TIME', $this->Parameter->getEnableLessonTime());
        $this->set('_WRONG_PASSWORD_TIMES', $this->Parameter->getWrongPasswordTimes());
        $this->set('_LOCK_TIME', $this->Parameter->getLockTime());
        $this->set('_SESSION_TIME', $this->Parameter->getSessionTime());
        $this->set('_VIOLATIONS_TIMES', $this->Parameter->getViolationsTimes());
        $this->set('_BACKUP_TIME', $this->Parameter->getBackupTime());
    }

    //tha
    public function add_admin() {
        $this->uses = array('User', 'Admin', 'IpAdmin');

        if ($this->request->is('post')) {
            $this->request->data["User"]["role"] = "admin";
            $this->request->data["User"]["actived"] = 1;
            $admins = $this->Admin->find();
            if ($this->User->saveAll($this->request->data)) {
                $this->request->data['IpAdmin']['admin_id'] = $this->User->id;
                $this->request->data['IpAdmin']['ip_address'] = $this->request->data['Admin']['ip_address'];
                $this->IpAdmin->save($this->request->data);

                $this->Session->setFlash(__('新�?��?�管�?�者�?�追加�?�れ�?�'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('controller' => 'admins', 'action' => 'add_admin'));
            } else {
                $this->Session->setFlash(__('新�?��?�管�?�者を追加�?��??�?��?�'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                ));
            }
        }
    }

    public function remove_admin() {
        $this->uses = array('User', 'Admin');
        $this->paginate = array(
            'limit' => 10,
            'fields' => array(),
            'conditions' => array(
                // 'User.id !=' => $this->Auth->user('id'),
                "User.role" => "admin")
        );

        $this->Paginator->settings = $this->paginate;
        $res = $this->Paginator->paginate("User");

        //  debug($res);
        for ($i = 0; $i < count($res); $i++) {
            // debug($row);
            $res[$i]['User']['status'] = $this->Util->checkUserLogin($res[$i]['User']['id']);
        }
        // debug($res);

        $this->set('res', $res);
        $this->set('flag', $this->Auth->user('id'));
        //debug($flag);
    }

    public function remove_admin_process($id) {
        if (!isset($id))
            $this->redirect(array("action" => "remove_admin"));
        $this->uses = array('User', 'Admin');


        if ($this->Util->checkUserLogin($id) == false) {
            if ($this->User->delete($id))
                $this->Session->setFlash(__('管理者が削除された'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
            $this->redirect(array("action" => "remove_admin"));
        }else {
            $this->Session->setFlash(__('この管理者はログインしている。削除できない'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
            $this->redirect(array("action" => "remove_admin"));
        }
    }

    public function edit_admin_process($id_admin) {
        if (empty($this->request->data)) {
            $this->request->data = $this->Admin->findById($id_admin);
        } else {
            $this->request->data['User']['id'] = $id_admin;
            if ($this->Admin->save($this->request->data) && $this->User->save($this->request->data)) {
                foreach ($this->request->data['IpAdmin'] as $key => $value) {
                    $this->request->data['IpAdmin'][$key]['admin_id'] = $id_admin;
                }

                if ($this->IpAdmin->saveAll($this->request->data['IpAdmin']))
                    $this->Session->setFlash(__('セーブされた'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-success'
                    ));
            } else {
                $this->Session->setFlash(__('セーブ�?��??�?��?��?も�?�一度�?�願�?�'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                ));
            }
        }
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

    public function downloadtsv($year, $month) {
        
    }

    public function fee_manager($year_ = null, $month_ = null) {

        $this->uses = array('Parameter');
        $result = $this->Parameter->find('all', array('conditions' => array('Parameter.name' => "LESSON_COST")));
        $lesson_cost = $result[0]["Parameter"]["value"];
        $result = $this->Parameter->find('all', array('conditions' => array('Parameter.name' => "LECTURER_MONEY_PERCENT")));
        $lecturer_money_percent = $result[0]["Parameter"]["value"];
        $this->set('lesson_cost', $lesson_cost * 10000);
        $this->set('lecturer_money_percent', $lecturer_money_percent / 100);
        if ($this->request->is('post')) {

            $month = $this->data['Fee']['month'];
            $year = $this->data['Fee']['year'];
            $month++;
            if ($month < 10)
                $month = "0" . $month;
            $year = $year + 1970;
            $this->set('month', $month);
            $this->set('year', $year);
            $this->set('months', $this->getMonthSet());
            $this->set('years', $this->getYearSet());
            $object = $this->data['Fee']['choose'];
            $this->set('object', $object);
            if ($object == 1) {
                $this->getStudentFee($year, $month);
            } else {
                $this->getLecturerFee($year, $month);
            }
            $name = "ELS-UBT-" . $year . "-" . $month . ".tsv";
            $File = "tsv\\fee\\" . $name;
            $this->set('exit', file_exists($File));
            $this->set("checkyearover", $this->checkYearOver($year, $month));
            if (file_exists($File)) {
                $this->Session->setFlash(__($year . '年' . $month . '月�?�TSV�?�作�?�?�れ�?��?��?�'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
            } else {
                if ($this->checkYearOver($year, $month))
                    $this->Session->setFlash(__($year . '年' . $month . '月�?�TSV�?�作�?�?��??�?��?�ん'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-warning'
                    ));
            }
        } else {

            date_default_timezone_set('Asia/Saigon');
            $date = date('Y:m:d H:i:s');
            $month = $this->getMonth($date);
            $year = $this->getYear($date);
            if (isset($year_) || isset($month_)) {
                $month = $month_;
                $year = $year_;
            }
            $this->set('month', $month);
            $this->set('year', $year);
            $this->set('months', $this->getMonthSet());
            $this->set('years', $this->getYearSet());
            $this->getStudentFee($year, $month);
            $this->getLecturerFee($year, $month);
            //if ($month < 10)
            //  $month = "0" . $month;
            $name = "ELS-UBT-" . $year . "-" . $month . ".tsv";
            $File = "tsv\\fee\\" . $name;
            $this->set('exit', file_exists($File));
            $bool = file_exists($File);
            if (file_exists($File)) {
                $this->Session->setFlash(__(($year) . '年' . ($month) . '月�?�TSV�?�作�?�?�れ�?��?��?�'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
            }
            // debug($this->Auth->user);
        }
    }

//    private function getStudentFee($year, $month) {
//        $this->uses = array('LessonMembership');
//        $res = $this->LessonMembership->find('all');
//        $this->uses = array('Student');
//        $student_list = $this->Student->find('all', array('condition' => array('actived' => 1)));
//        $this->uses = array('LessonMembership'); //$student['Student']['id'];
//        $student_list_ = array();
//        $i = 0;
//        foreach ($student_list as $student) {
//            $fee = 0;
//            $count = 0;
//            $result = $this->LessonMembership->find('all', array('conditions' => array('LessonMembership.student_id' => $student['Student']['id'])));
//            foreach ($result as $res) {
//                $date = $res['LessonMembership']['days_attended'];
//                if ($this->getYear($date) == $year && $this->getMonth($date) == $month) {
//                    $count++;
//                    $fee += $res['LessonMembership']['price']*10000;
//                }
//            }
//            // $student['count'] = $count;
//            $student_list_[$i] = $student;
//            $student_list_[$i]['count'] = $count;
//            $student_list_[$i]['fee'] = $fee;
//            $student_list_[$i]['Student']['phone_number'] = $this->getPhoneNumberFormat($student_list_[$i]['Student']['phone_number']);
//
//            $i++;
//        }
//        $this->set('student_list', $student_list_);
//        return $student_list_;
//    }


    private function getStudentFee($year, $month) {

        $this->uses = array('History');
        $res = $this->History->find('all', array(
            'recursive' => -1,
            // 'fields' => 'History.student_username',            
            'group' => array('History.student_username', 'History.student_account',
                'History.student_address', 'History.student_fullname', 'History.student_phone_number')
        ));

        $student_list_ = array();
        $i = 0;
        foreach ($res as $student_username) {

            $fee = 0;
            $this->uses = array('History');
            $students = $this->History->find('all', array('conditions' => array('student_username' => $student_username['History']['student_username'])));

            foreach ($students as $student) {
                $date = $student['History']['days_attended'];
                if ($this->getYear($date) == $year && $this->getMonth($date) == $month) {
                    $fee += $student['History']['price'] * 10000;
                }
            }
            if ($fee > 0) {
                $student_list_[$i] = $student;
                $student_list_[$i]['fee'] = $fee;
                $student_list_[$i]['History']['student_phone_number'] = $this->getPhoneNumberFormat($student_list_[$i]['History']['student_phone_number']);
                $student_list_[$i]['History']["student_account"] =
                   $this->getBankStudentAcountFormat($student_list_[$i]['History']["student_account"]);
                $i++;
            }
        }

        $this->set('student_list', $student_list_);
        return $student_list_;
    }

    private function getLecturerFee($year, $month) {
        $this->uses = array('History');
        $res = $this->History->find('all', array(
            'recursive' => -1,
            'group' => array('History.lecturer_username', 'History.lecturer_account',
                'History.lecturer_address', 'History.lecturer_fullname', 'History.lecturer_phone_number')
        ));

        $lecturer_list_ = array();
        $i = 0;
        // debug($res);  
        foreach ($res as $lecturer_username) {


            $fee = 0;
            $this->uses = array('History');
            $lecturers = $this->History->find('all', array('conditions' => array('lecturer_username' => $lecturer_username['History']['lecturer_username'])));


            foreach ($lecturers as $lecturer) {

                $date = $lecturer['History']['days_attended'];
                if ($this->getYear($date) == $year && $this->getMonth($date) == $month) {

                    $fee += $lecturer['History']['price'] * $lecturer['History']['percent_for_teacher'] * 100;
                }
            }

            //echo "fee =" . $fee . " i= " . $i . "<br>";

            if ($fee > 0) {
                $i++;
                $lecturer_list_[$i] = $lecturer;
                $lecturer_list_[$i]['fee'] = $fee;
                $lecturer_list_[$i]['History']['lecturer_phone_number'] = $this->getPhoneNumberFormat($lecturer_list_[$i]['History']['lecturer_phone_number']);
                 $lecturer_list_[$i]['History']["lecturer_account"] =
                   $this->getBankLecturerAcountFormat($lecturer_list_[$i]['History']["lecturer_account"]);
                
            }

            // echo "dmm";
        }

//        debug($lecturer_list_);
//        die();
        $this->set('lecturer_list', $lecturer_list_);
        return $lecturer_list_;
    }

//    private function getLecturerFee($year, $month) {
//        // $this->uses = array('Lesson');
//        //$res = $this->LessonMembership->find('all');
//        $this->uses = array('Lecturer');
//        //$lecturer_list = $this->Lecturer->find('all', array('condition' => array('actived' => 1)));
//        $lecturer_list = $this->Lecturer->find('all');
//        $this->uses = array('LessonMembership'); //$student['Student']['id'];
//
//        $lecturer_list_ = array();
//        $i = 0;
//        // debug($lecturer_list);
//        foreach ($lecturer_list as $lecturer) {
//            // echo "id =".$lecturer['Lecturer']['id'];
//
//            $fee = 0;
//
//            $count = 0;
//            $this->uses = array('Lesson');
//            $lesson_list = $this->Lesson->find('all', array('conditions' => array('Lesson.lecturer_id' => $lecturer['Lecturer']['id'])));
//            //debug($lesson_list);
//
//            $this->uses = array('LessonMembership');
//            $result = $this->LessonMembership->find('all');
//            foreach ($result as $res) {
//                $date = $res['LessonMembership']['days_attended'];
//                if ($this->getYear($date) == $year && $this->getMonth($date) == $month)
//                    foreach ($lesson_list as $lesson) {
//                        if ($lesson['Lesson']['id'] == $res['Lesson']['id'])
//                            $count++;
//                        $fee += $res['LessonMembership']['percent_for_teacher'] * $res['LessonMembership']['price'] * 100;
//                    }
//            }
//            $lecturer_list_[$i] = $lecturer;
//            $lecturer_list_[$i]['count'] = $count;
//            $lecturer_list_[$i]['fee'] = $fee;
//            $lecturer_list_[$i]['Lecturer']['phone_number'] = $this->getPhoneNumberFormat($lecturer_list_[$i]['Lecturer']['phone_number']);
//            $lecturer_list_[$i]['Lecturer']["credit_card_number"] =
//                    $this->getBankLecturerAcountFormat($lecturer_list_[$i]['Lecturer']["credit_card_number"]);
//            $i++;
//        }
//        $this->set('lecturer_list', $lecturer_list_);
//        // $this->uses = array('Lesson');
//        //$lesson_list = $this->Lesson->find('all', array('conditions' => array('Lesson.lecturer_id' => 10)));
//        return $lecturer_list_;
//    }

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
        //  debug($months);
        return $months;
    }

    private function getYearSet() {
        date_default_timezone_set('Asia/Saigon');
        $date = date('Y:m:d H:i:s');
        $month = $this->getMonth($date);
        $year = $this->getYear($date);
        if ($month == 1) {
            $month = 12;
            $year--;
        }
        else
            $month--;

        $years = array();
        for ($i = 1970; $i <= $year; $i++)
            $years[$i - 1970] = $i;
        // debug($years);
        return $years;
    }

    private function checkYearOver($year_, $month_) {
        date_default_timezone_set('Asia/Saigon');
        $date = date('Y:m:d H:i:s');
        $month = $this->getMonth($date);
        $year = $this->getYear($date);
        if ($year < $year_)
            return true;
        if ($year == $year_ && $month < $month_)
            return true;
        return false;
    }

    public function generate_tsv($year, $month) {
        $this->set('year', $year);
        $this->set('month', $month);
        date_default_timezone_set('Asia/Saigon');
        $date = date('Y:m:d H:i:s');
        // echo $date;
        mb_internal_encoding('UTF-8');
        header('Content-Type: text/html;charset=utf-8');
        // if ($month < 10)
        //    $month = "0" . $month;
        $name = "ELS-UBT-" . $year . "-" . $month . ".tsv";
        $data = "" . mb_convert_kana("ELS-UBT-GWK5M78", "rnaskhcv") . "\t";
        // echo "<br>" . $data;
        $data.= mb_convert_kana($year, "rnaskhcv") . "\t";
        if ($month < 10)
            $data.= mb_convert_kana("0" . $month, "rnaskhcv") . "\t";
        else
            $data.= mb_convert_kana($month, "rnaskhcv") . "\t";

        $data.= mb_convert_kana($this->getYear($date), "rnaskhcv") . "\t";
        $data.= mb_convert_kana($this->getMonth($date), "rnaskhcv") . "\t";
        $data.= mb_convert_kana($this->getDate($date), "rnaskhcv") . "\t";
        $data.= mb_convert_kana($this->getHour($date), "rnaskhcv") . "\t";
        $data.= mb_convert_kana($this->getMinus($date), "rnaskhcv") . "\t";
        $data.= mb_convert_kana($this->getSecond($date), "rnaskhcv") . "\t";
        $result = $this->Parameter->find('all', array('conditions' => array('Parameter.name' => "LESSON_COST")));
        $lesson_cost = $result[0]["Parameter"]["value"];
        $result = $this->Parameter->find('all', array('conditions' => array('Parameter.name' => "LECTURER_MONEY_PERCENT")));
        $lecturer_money_percent = $result[0]["Parameter"]["value"];

        $student_list = $this->getStudentFee($year, $month);
        foreach ($student_list as $student) {
            $data.= "\n";
            $data.= mb_convert_kana($student['History']['student_username'], "rnaskhcv") . "\t";
            $data.= mb_convert_kana($student['History']['student_fullname'], "rnaskhcv") . "\t";
            $data.= mb_convert_kana($student['fee'], "rnaskhcv") . "\t";
            $data.= mb_convert_kana($student['History']['student_address'], "rnaskhcv") . "\t";
            $phone_number = mb_convert_kana($student['History']['student_phone_number'], "rnaskhcv") . "\t";
            $data.= $phone_number . "\t";
            $data.= mb_convert_kana("18", "rnaskhcv") . "\t";
            $credit_number = mb_convert_kana($student['History']['student_account'], "rnaskhcv") . "\t";
            
            $data.= $credit_number . "\t";
        }

        $lecturer_list = $this->getLecturerFee($year, $month);
        foreach ($lecturer_list as $lecturer) {
            $data.= "\n";
            $data.= mb_convert_kana($lecturer['History']['lecturer_username'], "rnaskhcv") . "\t";
            $data.= mb_convert_kana($lecturer['History']['lecturer_fullname'], "rnaskhcv") . "\t";
            $data.= mb_convert_kana($lecturer['fee'], "rnaskhcv") . "\t";
            $data.= mb_convert_kana($lecturer['History']['lecturer_address'], "rnaskhcv") . "\t";
            $phone_number = mb_convert_kana($lecturer['History']['lecturer_phone_number'], "rnaskhcv") . "\t";
            $data.= $phone_number . "\t";
            $data.= mb_convert_kana("54", "rnaskhcv") . "\t";
            $credit_number = mb_convert_kana($lecturer['History']['lecturer_account'], "rnaskhcv") . "\t";
            $data.= $credit_number . "\t";
        }
        $data.= "\n";
        $data.= mb_convert_kana("END___END___END", "rnaskhcv") . "\t";
        $data.= mb_convert_kana($year, "rnaskhcv") . "\t";
        $data.= mb_convert_kana($month, "rnaskhcv") . "\t";
        $this->response->body($data);
        $this->response->type('tsv');
        $this->response->download("ELS-UBT-" . $year . "-" . $month . ".tsv");
        return $this->response;
    }

    private function getPhoneNumberFormat($phone) {
        $n = strlen($phone);
        $result = "";
        for ($i = 0; $i < $n; $i++) {
            $result.=$phone[$i];
            if (($i + 1) % 3 == 0 && $i != $n - 1)
                $result.="-";
        }
        return $result;
    }
    
     private function getBankStudentAcountFormat($bank_acount) {
        $n = strlen($bank_acount);
        $result = "";
        for ($i = 0; $i < $n; $i++) {
            $result.=$bank_acount[$i];

            if ($i == 7)
                $result.="-";
            if ($i == 11)
                $result.= "-";
            if ($i == 15)
                $result.="-";
            if ($i == 19)
                $result.="-";
        }
        return $result;
    }

    private function getBankLecturerAcountFormat($bank_acount) {
        $n = strlen($bank_acount);
        $result = "";
        for ($i = 0; $i < $n; $i++) {
            $result.=$bank_acount[$i];

            if ($i == 3)
                $result.="-" . "0";
            if ($i == 6)
                $result.= ("-" . "000");
            if ($i == 7)
                $result.= ("-");
        }
        return $result;
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
        if (!isset($ranking) || $ranking == 0) {
            
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

    public function database_manager() {
        $dir = new Folder(WWW_ROOT . 'files/db');
        $dir->chmod(WWW_ROOT . 'files/db', 0777, true, array());
        $files = $dir->find('.*\.sql');
        $files_info = array();
        foreach ($files as $file_name) {
            $file = new File($dir->pwd() . DS . $file_name);
            $info = $file->info();
            $info['created_date'] = date('H:i:s - d/m/Y ', $file->lastChange());
            $info['created_time'] = $file->lastChange();
            array_push($files_info, $info);
        }
        $price = array();
        foreach ($files_info as $key => $row) {
            $price[$key] = $row['created_time'];
        }
        array_multisort($price, SORT_DESC, $files_info);
        $this->set(compact('files_info'));
    }

    public function backup_database() {
        $this->autoRender = false;
        $databaseName = 'elearning';
        $fileName = WWW_ROOT . 'files/db/' . $databaseName . '-backup-' . date('Y-m-d_H-i-s') . '.sql';
        echo exec('whoami && which mysqldump');

        $command = "/home/action/.parts/bin/mysqldump --opt --skip-extended-insert --complete-insert --host=localhost --user=root --password=tuananh elearning > " . $fileName;
        exec($command);
        $this->Session->setFlash(__('データベース�?��?ックアップ�?�れ�?�'), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-success'));
        $this->redirect(array('controller' => 'admins', 'action' => 'database_manager'));
    }

    public function delete_file() {
        $this->autoRender = false;
        if (isset($this->params['named']['file'])) {
            $source = WWW_ROOT . 'files' . DS . 'db' . DS . $this->params['named']['file'];
            unlink($source);
        }
        $this->Session->setFlash(__('�?ックアップ�?�ファイル�?�削除�?�れ�?�'), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-warning'));
        $this->redirect(array('controller' => 'admins', 'action' => 'database_manager'));
    }

    public function delete_all() {

        $this->autoRender = false;
        $dir = new Folder(WWW_ROOT . 'files/db');
        $dir->chmod(WWW_ROOT . 'files/db', 0777, true, array());
        $files = $dir->find('.*\.sql');
        foreach ($files as $file) {
            unlink($dir->pwd() . DS . $file);
        }
        $this->Session->setFlash(__('全部�?��?ックアップ�?�ファイル�?�削除�?�れ�?�'), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-warning'));
        $this->redirect(array('controller' => 'admins', 'action' => 'database_manager'));
    }

    public function restore_database() {
        $this->autoRender = false;
        if (isset($this->params['named']['file'])) {
            $mysql_host = 'localhost';
            $mysql_username = 'root';
            $mysql_password = 'tuananh';
            $db_name = 'elearning';
            $source = WWW_ROOT . 'files/db/' . $this->params['named']['file'];
            $command = "/home/action/.parts/bin/mysql -u root -ptuananh elearning < " . $source;
            var_dump($command);
            exec($command);
        }
        $this->redirect(array('controller' => 'admins', 'action' => 'database_manager'));
    }

    public function manage_document() {

        $sql = "SELECT *
                FROM  documents, lessons, users WHERE documents.lesson_id = lessons.id AND lessons.lecturer_id = users.id";
        $datas = $this->Document->query($sql);

        if ($datas) {

            for ($i = 0; $i <= count($datas) - 1; $i++) {
                $tmp = $datas[$i]['documents']['id'];
                //debug($tmp);
                $sql_1 = "SELECT COUNT(id)
                FROM  `violates`
                WHERE document_id = '$tmp'
                GROUP BY (document_id)";
                $d = $this->Violate->query($sql_1);
                if ($d) {
                    $datas[$i]['documents']['count'] = $d[0][0]['COUNT(id)'];
                } else {
                    $datas[$i]['documents']['count'] = 0;
                }
            }
            //debug($datas);
            $this->set('datas', $datas);
        } else {
            $this->Session->setFlash(__('データがない'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'));
        }
    }

    public function see_document($document_id) {
        $datas = $this->Document->find('first', $document_id);
        $this->redirect(array('controller' => 'Document', 'action' => 'show', $document_id));
    }

    public function see_violate_document($document_id) {
        $datas = $this->Violate->find('all', array('conditions' => array('document_id' => $document_id)));
        //debug($datas);
        if ($datas) {
            $this->set('datas', $datas);
        } else {
            $this->Session->setFlash(__('違犯報告がない'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'));
        }
        //$this->redirect(array('controller' => 'admins', 'action' => 'manage_document'));
    }

    public function ban_document($document_id) {
        if (!$this->request->is('post')) {
            
        } else {

            $Document = $this->Document->findById($document_id);
            $Document ["Document"]['baned'] = 1;

            //メッセージの情報
            $user_id = $id = $this->Auth->user('id');
            $recipient_id = $Document["Lesson"]['lecturer_id'];
            $type = 'Block';
            $content = $this->request->data["Message"]["content"];
            $object_id = $document_id;
            $object_type = 'Document';

            if ($this->Document->save($Document) && $this->Message->Sent($user_id, $recipient_id, $type, $content, $object_id, $object_type)) {
                $this->Session->setFlash(__('ドキュメントが禁止された。そして、先生にメッセージを送った。'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'));
            } else {
                $this->Session->setFlash(__('禁止できない'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'));
            }
            $this->redirect(array('controller' => 'admins', 'action' => 'manage_document'));
        }
    }

    public function delete_ban_document($document_id) {

        if (!$this->request->is('post')) {
            
        } else {
            $Document = $this->Document->findById($document_id);
            $Document ["Document"]['baned'] = 0;
            //var_dump($Document);
            //メッセージの情報
            $user_id = $id = $this->Auth->user('id');
            $recipient_id = $Document["Lesson"]['lecturer_id'];
            $type = 'Unblock';
            $content = $this->request->data["Message"]["content"];
            $object_id = $document_id;
            $object_type = 'Document';

            if ($this->Document->save($Document) && $this->Message->Sent($user_id, $recipient_id, $type, $content, $object_id, $object_type)) {
                $sql_detele = "DELETE FROM violates WHERE document_id = '$document_id'";
                $this->Violate->query($sql_detele);
                $this->Session->setFlash(__('ドキュメントの禁止を削除した。そして、先生にメッセージを送った。'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'));
            } else {
                $this->Session->setFlash(__('禁止を削除できない'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'));
            }
            $this->redirect(array('controller' => 'admins', 'action' => 'manage_document'));
        }
    }

    public function delete_violate($document_id, $id) {
        $this->Violate->delete($id);
        $this->redirect(array('controller' => 'admins', 'action' => 'see_violate_document', $document_id));
    }

    public function delete_document($document_id) {

        if (!$this->request->is('post')) {
            
        } else {
            $Document = $this->Document->find('first', $document_id);
            $name = $Document['Document']['link'];

            //メッセージの情報
            $user_id = $id = $this->Auth->user('id');
            $recipient_id = $Document["Lesson"]['lecturer_id'];
            $type = 'Delete';
            $content = $this->request->data["Message"]["content"];
            $object_id = $document_id;
            $object_type = 'Document';
            //ドキュメントの削除
            if ($this->Document->delete($document_id) && $this->Message->Sent($user_id, $recipient_id, $type, $content, $object_id, $object_type)) {
                unlink(WWW_ROOT . 'files' . DS . $name);
                $this->Session->setFlash(__('ドキュメントが削除された。そして、先生にメッセージを送った。'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
            } else {
                $this->Session->setFlash(__('削除できない'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'));
            }
            $this->redirect(array('controller' => 'admins', 'action' => 'manage_document'));
        }
    }

    public function look_infor_student($id_student) {

        $questions = $this->Question->find('all');
        $droplist = array();
        foreach ($questions as $question) {
            $droplist[$question['Question']['id']] = $question['Question']['question'];
        }
        $this->set('droplist', $droplist);
        if (empty($this->request->data)) {
            //$lecturer_id = $this->Auth->user('id');
            $this->request->data = $this->Student->findById($id_student);
            //var_dump($this->request->data);
        } else {
            $this->request->data['Student']['id'] = $id_student;
            //var_dump($this->request->data);
            if ($this->Student->save($this->request->data)) {
                $this->Session->setFlash(__('セーブ�?�れ�?�'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                //$this->redirect(array('controller' => 'Admin', 'action' => 'manage_lecturer'));
            } else {
                $this->Session->setFlash(__('セーブ�?��??�?��?��?も�?�一度�?�願�?�'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                ));
            }
        }
    }

    public function manage_lesson() {
        $sql_0 = "SELECT *
                FROM  lessons, users WHERE lessons.lecturer_id = users.id ";
        $datas = $this->Lesson->query($sql_0);
        //debug($datas);
        if ($datas) {
            for ($i = 0; $i <= count($datas) - 1; $i++) {
                $tmp = $datas[$i]['lessons']['id'];
                //debug($tmp);
                $sql_1 = "SELECT COUNT(id)
                FROM  `ihans`
                WHERE lesson_id = '$tmp'
                GROUP BY (lesson_id)";
                $d = $this->Ihan->query($sql_1);
                if ($d) {
                    $datas[$i]['lessons']['count'] = $d[0][0]['COUNT(id)'];
                } else {
                    $datas[$i]['lessons']['count'] = 0;
                }
            }
            $this->set('datas', $datas);
        } else {
            $this->Session->setFlash(__('データがない'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'));
        }
    }

    public function see_violate_lesson($lesson_id) {
        $sql_0 = "SELECT * FROM lessons, lecturers, users WHERE lecturers.id = users.id AND lecturers.id = lessons.lecturer_id AND lessons.id = '$lesson_id'";
        $lesson = $this->Lesson->query($sql_0);
        $this->set('lesson', $lesson);
        //debug($lesson);
        $sql_1 = "SELECT * FROM ihans WHERE ihans.lesson_id = '$lesson_id'";
        $ihan = $this->Ihan->query($sql_1);
        $this->set('ihan', $ihan);
        //debug($ihan);
    }

    public function ban_lesson($lesson_id) {
        if (!$this->request->is('post')) {
            
        } else {
            $Lesson = $this->Lesson->findById($lesson_id);
            $Lesson ["Lesson"]['baned'] = 1;
            //var_dump($Lesson);
            //メッセージの情報
            $user_id = $id = $this->Auth->user('id');
            $recipient_id = $Lesson["Lesson"]['lecturer_id'];
            $type = 'Block';
            $content = $this->request->data["Message"]["content"];
            $object_id = $lesson_id;
            $object_type = 'Lesson';

            if ($this->Lesson->save($Lesson) && $this->Message->Sent($user_id, $recipient_id, $type, $content, $object_id, $object_type)) {
                $this->Session->setFlash(__('授業が禁止された。そして、先生にメッセージを送った。'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'));
            } else {
                $this->Session->setFlash(__('禁止できない'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'));
            }
            $this->redirect(array('controller' => 'admins', 'action' => 'manage_lesson'));
        }
    }

    public function delete_ban_lesson($lesson_id) {
        if (!$this->request->is('post')) {
            
        } else {
            $Lesson = $this->Lesson->findById($lesson_id);
            $Lesson ["Lesson"]['baned'] = 0;
            //var_dump($Lesson);
            //メッセージの情報
            $user_id = $id = $this->Auth->user('id');
            $recipient_id = $Lesson["Lesson"]['lecturer_id'];
            $type = 'Unblock';
            $content = $this->request->data["Message"]["content"];
            $object_id = $lesson_id;
            $object_type = 'Lesson';

            if ($this->Lesson->save($Lesson) && $this->Message->Sent($user_id, $recipient_id, $type, $content, $object_id, $object_type)) {
                $sql_detele = "DELETE FROM ihans WHERE lesson_id = '$lesson_id'";
                $this->Ihan->query($sql_detele);
                $this->Session->setFlash(__('授業の禁止を削除した。そして、先生にメッセージを送った。'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'));
            } else {
                $this->Session->setFlash(__('禁止を削除できない'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'));
            }
            $this->redirect(array('controller' => 'admins', 'action' => 'manage_lesson'));
        }
    }

    public function __delete_lesson($lesson_id) {
        $result = $this->Lesson->findById($lesson_id);
        if ($result != NULL) {
            $docs = $this->Document->find('all', array('conditions' => array('Document.lesson_id' => $lesson_id)));
            foreach ($docs as $doc) {
                $link = $doc['Document']['link'];
                if ($this->Document->delete($doc['Document']['id'])) {
                    unlink(WWW_ROOT . 'files' . DS . $link);
                }
            }
            $tests = $this->Test->find('all', array('conditions' => array('Test.lesson_id' => $lesson_id)));
            foreach ($tests as $test) {
                $link = $test['Test']['link'];
                if ($this->Test->delete($test['Test']['id'])) {
                    unlink(WWW_ROOT . 'tsv' . DS . $link);
                }
            }
            if ($this->Lesson->delete($lesson_id)) {
                $this->Comment->deleteAll(array('Comment.lesson_id' => $lesson_id), false);
                return true;
            }
        }
        else
            return false;
    }

    public function delete_lesson($lesson_id) {
        if (!$this->request->is('post')) {
            
        } else {
            $Lesson = $this->Lesson->findById($lesson_id);
            $user_id = $id = $this->Auth->user('id');
            $recipient_id = $Lesson["Lesson"]['lecturer_id'];
            $type = 'Delete';
            $content = $this->request->data["Message"]["content"];
            $object_id = $lesson_id;
            $object_type = 'Lesson';
            if ($this->__delete_lesson($lesson_id) && $this->Message->Sent($user_id, $recipient_id, $type, $content, $object_id, $object_type)) {
                $this->Session->setFlash(__('授業が削除された。そして、先生にメッセージを送った。'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
            } else {
                $this->Session->setFlash(__('削除できない'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'));
            }
            $this->redirect(array('controller' => 'admins', 'action' => 'manage_lesson'));
        }
    }

    public function delete_violate_lesson($id, $lesson_id) {
        $this->Ihan->delete($id);
        $this->redirect(array('controller' => 'admins', 'action' => 'see_violate_lesson', $lesson_id));
    }

    public function accept_violate($document_id, $violate_id) {
        $Violate = $this->Violate->findById($violate_id);
        $Violate["Violate"]['accepted'] = 1;
        if ($this->Violate->save($Violate)) {
            
        } else {
            $this->Session->setFlash(__('賛成できない'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'));
        }
        $this->redirect(array('controller' => 'admins', 'action' => 'see_violate_document', $document_id));
    }

    public function un_accept_violate($document_id, $violate_id) {
        $Violate = $this->Violate->findById($violate_id);
        $Violate["Violate"]['accepted'] = 0;
        if ($this->Violate->save($Violate)) {
            
        } else {
            $this->Session->setFlash(__('賛成を削除できない'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'));
        }
        $this->redirect(array('controller' => 'admins', 'action' => 'see_violate_document', $document_id));
    }

}
?>


