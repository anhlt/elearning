
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class AdminsController extends AppController{
    //public $components = array('RequestHandler');
    public $components = array('Paginator');
    public $helpers = array('Js');
    
    var $uses = array('Admin','IpAdmin','Lecturer', 'User', 'Student', 'Parameter');   
    
    public function beforeFilter() {
    $this->Auth->allow("add_admin");
       $this->Auth->allow("remove_admin");
       $this->Auth->allow("remove_admin_process");
       $this->Auth->allow("view_violation");
        $this->Auth->allow("view_violation_content");
        $this->Auth->allow("view_violation_content_process");
       
    }

    public function index() {
        
    }
    
 
//以下はipアドレス管理の機能だ    
//    
    public function add_ip_address(){
        $id = $this->Auth->user('id');
        //$this->Session->write('id', '911');
        //$id = $this->Session->read('id');
        //echo $id;
        if($this->request->is('post')){
            //echo $this->Session->read('id');
            $this->loadModel('IpAdmin');
            //echo $this->request->data['add']['ip_address'];
            $ip_address = $this->request->data['add']['ip_address'];
            $this->IpAdmin->set(array('ip_address'=>$ip_address));
            //check empty
            if($ip_address == NULL){
                $this->Session->setFlash(__('IPアドレスが空しい'));
            //check exist
            }else if($this->IpAdmin->query("SELECT * FROM ip_admins WHERE admin_id = '$id' and ip_address = '$ip_address'") != NULL){
                //$this->Session->setFlash(__('IPアドレスが存在した'));
                $this->Session->setFlash(__('IPアドレスが存在した'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
			));                 
            //check format    
            }else if($this->IpAdmin->validates()){
                $sql = "INSERT INTO ip_admins VALUES('$id','$ip_address')";
                $this->IpAdmin->query($sql);      
            }else{
                //$this->Session->setFlash(__('IPアドレスのフォーマットが正しくない'));
                $this->Session->setFlash(__('IPアドレスのフォーマットが正しくない'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
			));                
            }
        }
        $datas = $this->IpAdmin->findAllByAdmin_id($id);
        $this->set('data',$datas); // truyen du lieu cho view tung ung voi ten function
    }
    
    public function edit_ip_address(){
        $id = $this->Auth->user('id');
        $this->loadModel('IpAdmin');
        if($this->request->is('post')){
            $old_ip_address =$this->request->data['edit']['old_ip_address'];
            $new_ip_address =$this->request->data['edit']['new_ip_address'];
            $this->IpAdmin->set(array('ip_address'=>$new_ip_address));
            //debug($this->IpAdmin);die;
            //check exist
            if($this->IpAdmin->query("SELECT * FROM ip_admins WHERE admin_id = '$id' and ip_address = '$new_ip_address'") != NULL){
                //$this->Session->setFlash(__('IPアドレスが存在した'));
                $this->Session->setFlash(__('IPアドレスが存在した'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
			));  
            //check format    
            }else if($this->IpAdmin->validates()){
                //echo $old_ip_address;
                //echo $new_ip_address;
                $sql = "UPDATE ip_admins SET ip_address = '$new_ip_address' WHERE admin_id = '$id' and ip_address = '$old_ip_address' ";
                //echo "ok";
                if(!$this->IpAdmin->query($sql)){
                    $this->redirect(array('controller'=>'admins','action'=>'add_ip_address')); // chuyen ve View/Admin/add_ip_address.ctp
                } 
            }else{
                //echo "loi";
                //$this->Session->setFlash(__('IPアドレスのフォーマットが正しくない'));
                $this->Session->setFlash(__('IPアドレスのフォーマットが正しくない'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
			));                 
            }
        }
    }
    public function delete_ip_address($ip_address){
        $id = $this->Auth->user('id');
        $sql = "DELETE FROM ip_admins WHERE (admin_id = '$id' and ip_address = '$ip_address')";
        $this->IpAdmin->query($sql);
        $this->redirect(array('action'=>'add_ip_address')); // chuyen ve View/Admin/add_ip_address.ctp
    }
    
//以下は先生管理の機能だ
    public function manage_lecturer(){
        $this->loadModel('User');
        $this->loadModel('Lecturer');
        if ($this->request->is('post')) {
           $this->set('data', NULL);
           $username = $this->request->data["search"]["username"];
           //echo $username;
           //die();
           if($username != NULL){
                    $sql1 = "SELECT * FROM Lecturers, Users WHERE (Lecturers.id = Users.id and 
                    Users.username = '$username')";
                    //$sql = "SELECT * FROM users";
                    $data = $this->Lecturer->User->query($sql1);
                    if($data != NULL){
                    $this->set('data', $data);
                    // $this->redirect(array('action'=>'manage_lecturer'));
                    }else{
                        $this->Session->setFlash(__('見つけない'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
			));  
                        //$this->redirect(array('action'=>'manage_lecturer'));
                    }
              }else{
                    $sql2 = "SELECT * FROM Lecturers, Users WHERE (Lecturers.id = Users.id and Users.role = 'lecturer')";
                    //$sql = "SELECT * FROM users";
                    $data = $this->Lecturer->User->query($sql2);
                    //var_dump($data);
                    if($data == NULL){
                        $this->Session->setFlash(__('ダータがない'));
                    }else{
                        $this->set('data', $data);
                    }    
              }
               
        }else{
            $sql2 = "SELECT * FROM Lecturers, Users WHERE (Lecturers.id = Users.id and Users.role = 'lecturer')";
            //$sql = "SELECT * FROM users";
            $data = $this->Lecturer->User->query($sql2);
            //var_dump($data);
            $this->set('data', $data);
            if($data == NULL){
                $this->Session->setFlash(__('ダータがない'));
            }else{
                $this->set('data', $data);
            }   
        }
    }
    
    public function view_infor_lecturer($id_lecturer){
        //echo $id_lecturer;
        $this->loadModel('User');
        $this->loadModel('Lecturer');
        $sql = "SELECT * FROM Lecturers, Users WHERE (Lecturers.id = Users.id and Users.id = '$id_lecturer')";
        $infor = $this->Lecturer->User->query($sql);
        //echo "<pre>";
        //var_dump($infor);
        $this->set('infor', $infor);
    }
    
    public function unlock_lecturer($id_lecturer){
        $this->loadModel('User');
        $sql = "UPDATE users SET actived = 1 WHERE users.id = '$id_lecturer'";
        $result = $this->User->query($sql);
        $this->redirect(array('action'=>'manage_lecturer'));
    }
    
    public function lock_lecturer($id_lecturer){
        $this->loadModel('User');
        $sql = "UPDATE users SET actived = 0 WHERE users.id = '$id_lecturer'";
        $result = $this->User->query($sql);
        $this->redirect(array('action'=>'manage_lecturer'));
    }
    
    public function reset_password_lecturer($id_lecturer, $init_password){
        //echo $id;
        //echo $init_password;
        $this->loadModel('User');
        $sql = "UPDATE users SET password = '$init_password' WHERE users.id = '$id_lecturer'";
        if(!$this->User->query($sql)){
            //$this->Session->setFlash(__('パスワードのリセットが成功された'));
            $this->Session->setFlash(__('パスワードのリセットが成功された'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-success'
			));  
            $this->redirect(array('action'=>'manage_lecturer'));
        }else{
            //$this->Session->setFlash(__('パスワードのリセットができない'));
            $this->Session->setFlash(__('パスワードのリセットができない'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
			));              
            $this->redirect(array('action'=>'manage_lecturer'));
        }
    }
    
   public function reset_verifycode_lecturer($id_lecturer, $init_verifycode){
        //echo $id;
        //echo $init_password;
        $this->loadModel('Lecturer');
        $sql = "UPDATE Lecturers SET current_verifycode = '$init_verifycode' WHERE Lecturers.id = '$id_lecturer'";
        if(!$this->User->query($sql)){
            //$this->Session->setFlash(__('verifycodeのリセットが成功された'));
            $this->Session->setFlash(__('verifycodeのリセットが成功された'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-success'
			));             
            $this->redirect(array('action'=>'manage_lecturer'));
        }else{
            //$this->Session->setFlash(__('verifycodeのリセットができない'));
            $this->Session->setFlash(__('verifycodeのリセットができない'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
			));                
            $this->redirect(array('action'=>'manage_lecturer'));
        }
    }
    
    public function delete_lecturer($id_lecturer){
        $this->loadModel('Lecturer');
        if($this->Lecturer->delete($id_lecturer)){
            //$this->Session->setFlash(__('アカウントの削除が成功された'));        
                       $this->Session->setFlash(__('アカウントの削除が成功された'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-success'
			)); 
            //$this->redirect(array('action'=>'manage_lecturer'));
        }else{
            //$this->Session->setFlash(__('アカウントの削除ができない'));
            //$notify ="アカウントの削除ができない";
                    $this->Session->setFlash(__('アカウントの削除ができない'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
			));
        }
  
        $this->redirect(array('action'=>'manage_lecturer'));
    }
    
//以下は学生管理の機能だ
    public function manage_student(){
        $this->loadModel('User');
        $this->loadModel('Student'); 
        if ($this->request->is('post')) {
           $this->set('data', NULL);
           $username = $this->request->data["search"]["username"];
           //echo $username;
           //die();
           if($username != NULL){
                    $sql1 = "SELECT * FROM Students, Users WHERE (Students.id = Users.id and 
                    Users.username = '$username')";
                    //$sql = "SELECT * FROM users";
                    $data = $this->Student->User->query($sql1);
                    if($data != NULL){
                    $this->set('data', $data);
                    // $this->redirect(array('action'=>'manage_lecturer'));
                    }else{
                        $this->Session->setFlash(__('見つけない'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
			));  
                        //$this->redirect(array('action'=>'manage_lecturer'));
                    }
           }else{
            $sql = "SELECT * FROM Students, Users WHERE (Students.id = Users.id and Users.role = 'student')";
            //$sql = "SELECT * FROM users";
            $data = $this->Student->User->query($sql);
            //$data = $this->Admin->printfStudent();
            if($data == NULL){
                        $this->Session->setFlash(__('ダータがない'));
                    }else{
                        $this->set('data', $data);
                    }   
           }      
        }else{
            $sql = "SELECT * FROM Students, Users WHERE (Students.id = Users.id and Users.role = 'student')";
            //$sql = "SELECT * FROM users";
            $data = $this->Student->User->query($sql);
            //$data = $this->Admin->printfStudent();
            if($data == NULL){
                        $this->Session->setFlash(__('ダータがない'));
                    }else{
                        $this->set('data', $data);
                    }   
        }
    
  }
    
    public function view_infor_student($id_student){
        //echo $id_lecturer;
        $this->loadModel('User');
        $this->loadModel('Students');
        $sql = "SELECT * FROM Students, Users WHERE (Students.id = Users.id and Users.id = '$id_student')";
        $infor = $this->Lecturer->User->query($sql);
        //echo "<pre>";
        //var_dump($infor);
        $this->set('infor', $infor);
    }
    
    public function unlock_student($id_student){
        $this->loadModel('User');
        $sql = "UPDATE users SET actived = 1 WHERE users.id = '$id_student'";
        $result = $this->User->query($sql);
        $this->redirect(array('action'=>'manage_student'));
    }
    
    public function lock_student($id_student){
        $this->loadModel('User');
        $sql = "UPDATE users SET actived = 0 WHERE users.id = '$id_student'";
        $result = $this->User->query($sql);
        $this->redirect(array('action'=>'manage_student'));
    }
    
    public function reset_password_student($id_student, $init_password){
        //echo $id;
        //echo $init_password;
        $this->loadModel('User');
        $sql = "UPDATE users SET password = '$init_password' WHERE users.id = '$id_student'";
        if(!$this->User->query($sql)){
            //$this->Session->setFlash(__('パスワードのリセットが成功された'));
            $this->Session->setFlash(__('パスワードのリセットが成功された'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-success'
			));            
            $this->redirect(array('action'=>'manage_student'));
        }else{
            //$this->Session->setFlash(__('パスワードのリセットができない'));
            $this->Session->setFlash(__('パスワードのリセットが成功された'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
			));            
            $this->redirect(array('action'=>'manage_student'));
        }
    }
    
   public function reset_verifycode_student($id_student, $init_verifycode){
        //echo $id;
        //echo $init_password;
        $this->loadModel('Student');
        $sql = "UPDATE Students SET current_verifycode = '$init_verifycode' WHERE   Students.id = '$id_student'";
        if(!$this->User->query($sql)){
            //$this->Session->setFlash(__('verifycodeのリセットが成功された'));
            $this->Session->setFlash(__('verifycodeのリセットが成功された'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-success'
			));             
            $this->redirect(array('action'=>'manage_student'));
        }else{
            //$this->Session->setFlash(__('verifycodeのリセットができない'));
            $this->Session->setFlash(__('verifycodeのリセットができない'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
			));             
            $this->redirect(array('action'=>'manage_student'));
        }
    }
    
    public function delete_student($id_student){
        $this->loadModel('Student');
        if($this->Student->delete($id_student)){
            //$this->Session->setFlash(__('アカウントの削除が成功された'));
            $this->Session->setFlash(__('アカウントの削除が成功された'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-success'
			));             
            
        }else{
            //$this->Session->setFlash(__('アカウントの削除ができない'));
            $this->Session->setFlash(__('アカウントの削除ができない'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
			));             
            //$this->redirect(array('action'=>'manage_student'));
        }
        $this->redirect(array('action'=>'manage_student'));
    }    
    
//以下はシステム仕様の管理の機能だ    
    public function manage_parameter(){
        
        
        if($this->request->is('post')){
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
            $this->Parameter->set(array('value'=>$LESSON_COST));
            if(!$this->Parameter->validates()) $flash = 0; 
            $this->Parameter->set(array('value'=>$LECTURER_MONEY_PERCENT));
            if(!$this->Parameter->validates()) $flash = 0;
            $this->Parameter->set(array('value'=>$ENABLE_LESSON_TIME));
            if(!$this->Parameter->validates()) $flash = 0;
            $this->Parameter->set(array('value'=>$WRONG_PASSWORD_TIMES));
            if(!$this->Parameter->validates()) $flash = 0;
            $this->Parameter->set(array('value'=>$LOCK_TIME));
            if(!$this->Parameter->validates()) $flash = 0;
            $this->Parameter->set(array('value'=>$SESSION_TIME));
            if(!$this->Parameter->validates()) $flash = 0;
            $this->Parameter->set(array('value'=>$VIOLATIONS_TIMES));
            if(!$this->Parameter->validates()) $flash = 0;
            if($flash){
                if($LESSON_COST < 0 ){
                    $error = $error."課金の金額 >= 0\n";
                }else{
                    $this->Parameter->updateParameter('LESSON_COST', $LESSON_COST);
                }
                if($LECTURER_MONEY_PERCENT > 100 || $LECTURER_MONEY_PERCENT <0 ){
                    $error = $error."<br>100 >= 先生に支払った課金 >= 0</br>";
                }else{
                    $this->Parameter->updateParameter('LECTURER_MONEY_PERCENT', $LECTURER_MONEY_PERCENT);
                }
                if($ENABLE_LESSON_TIME <= 0 ){
                    $error = $error."<br>受講可能の時間 > 0</br>";
                }else{
                    $this->Parameter->updateParameter('ENABLE_LESSON_TIME', $ENABLE_LESSON_TIME);
                }
                if($WRONG_PASSWORD_TIMES <= 0){
                    $error = $error."<br>間違えるログインの回数 > =1</br>";
                }else{
                     $this->Parameter->updateParameter('WRONG_PASSWORD_TIMES', $WRONG_PASSWORD_TIMES);
                }
                if($LOCK_TIME <= 0){
                    $error = $error."<br>ロック時間 > 0</br>";
                }else{
                    $this->Parameter->updateParameter('LOCK_TIME', $LOCK_TIME);
                }
                if($SESSION_TIME <=0){
                    $error = $error."<br>操作がない場合はセションが終了する時間 > 0</br>";
                }else{
                    $this->Parameter->updateParameter('SESSION_TIME', $SESSION_TIME);
                }
                if($VIOLATIONS_TIMES <=0){
                    $error = $error."<br>違犯時、アカウントを削除 >= 1</br>";
                }else{
                    $this->Parameter->updateParameter('VIOLATIONS_TIMES', $VIOLATIONS_TIMES);
                }
                //$this->Session->setFlash($error);
                $this->Session->setFlash($error, 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-warning'
			));        
            }else{
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
            $this->set('_LESSON_COST',$this->Parameter->getLessonCost());
            
            $this->set('_LECTURER_MONEY_PERCENT',$this->Parameter->getLecturerMoneyPercent());
            $this->set('_ENABLE_LESSON_TIME',$this->Parameter->getEnableLessonTime());
            $this->set('_WRONG_PASSWORD_TIMES',$this->Parameter->getWrongPasswordTimes());
            $this->set('_LOCK_TIME',$this->Parameter->getLockTime());
            $this->set('_SESSION_TIME',$this->Parameter->getSessionTime());
            $this->set('_VIOLATIONS_TIMES',$this->Parameter->getViolationsTimes());
            
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
            $this->Session->setFlash(__('管理者が削除された'), 'alert', array(
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
        //debug($res);
        $this->set('res', $res);
        //echo "11111111111";
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

    public function fee_manager()
    {
        
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

