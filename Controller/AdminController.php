<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class AdminController extends AppController{
    public $components = array('RequestHandler');
    public $helpers = array('Js');
    
    var $uses = array('Admin','IpAdmin','Lecturer', 'User', 'Student');   
    
    public function menu_manage(){
        
    }
//以下はipアドレス管理の機能だ    
    public function add_ip_address(){
        $this->Session->write('id', '911');
        $id = $this->Session->read('id');
        echo $id;
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
                $this->Session->setFlash(__('IPアドレスが存在した'));
            //check format    
            }else if($this->IpAdmin->validates()){
                $sql = "INSERT INTO ip_admins VALUES('$id','$ip_address')";
                $this->IpAdmin->query($sql);      
            }else{
                $this->Session->setFlash(__('IPアドレスのフォーマットが正しくない'));
            }
        }
        $datas = $this->IpAdmin->findAllByAdmin_id($id);
        $this->set('data',$datas); // truyen du lieu cho view tung ung voi ten function
    }
    
    public function edit_ip_address(){
        $this->Session->write('id', '911');
        $id = $this->Session->read('id');
        $this->loadModel('IpAdmin');
        if($this->request->is('post')){
            $old_ip_address =$this->request->data['edit']['old_ip_address'];
            $new_ip_address =$this->request->data['edit']['new_ip_address'];
            $this->IpAdmin->set(array('ip_address'=>$new_ip_address));
            //debug($this->IpAdmin);die;
            //check exist
            if($this->IpAdmin->query("SELECT * FROM ip_admins WHERE admin_id = '$id' and ip_address = '$new_ip_address'") != NULL){
                $this->Session->setFlash(__('IPアドレスが存在した'));
            //check format    
            }else if($this->IpAdmin->validates()){
                //echo $old_ip_address;
                //echo $new_ip_address;
                $sql = "UPDATE ip_admins SET ip_address = '$new_ip_address' WHERE admin_id = '$id' and ip_address = '$old_ip_address' ";
                //echo "ok";
                if(!$this->IpAdmin->query($sql)){
                    $this->redirect(array('controller'=>'admin','action'=>'add_ip_address')); // chuyen ve View/Admin/add_ip_address.ctp
                } 
            }else{
                //echo "loi";
                $this->Session->setFlash(__('IPアドレスのフォーマットが正しくない'));
            }
        }
    }
    public function delete_ip_address($ip_address){
        $this->Session->write('id', '911');
        $id = $this->Session->read('id');
        $sql = "DELETE FROM ip_admins WHERE (admin_id = '$id' and ip_address = '$ip_address')";
        $this->IpAdmin->query($sql);
        $this->redirect(array('action'=>'add_ip_address')); // chuyen ve View/Admin/add_ip_address.ctp
    }
    
//以下は先生管理の機能だ
    public function manage_lecturer(){
        $this->loadModel('User');
        $this->loadModel('Lecturer');
        $sql = "SELECT * FROM Lecturers, Users WHERE (Lecturers.id = Users.id and Users.role = 'lecturer')";
        //$sql = "SELECT * FROM users";
        $data = $this->Lecturer->User->query($sql);
        //var_dump($data);
        $this->set('data', $data);
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
            $this->Session->setFlash(__('パスワードのリセットが成功された'));
            $this->redirect(array('action'=>'manage_lecturer'));
        }else{
            $this->Session->setFlash(__('パスワードのリセットができない'));
            $this->redirect(array('action'=>'manage_lecturer'));
        }
    }
    
   public function reset_verifycode_lecturer($id_lecturer, $init_verifycode){
        //echo $id;
        //echo $init_password;
        $this->loadModel('Lecturer');
        $sql = "UPDATE Lecturers SET current_verifycode = '$init_verifycode' WHERE Lecturers.id = '$id_lecturer'";
        if(!$this->User->query($sql)){
            $this->Session->setFlash(__('verifycodeのリセットが成功された'));
            $this->redirect(array('action'=>'manage_lecturer'));
        }else{
            $this->Session->setFlash(__('verifycodeのリセットができない'));
            $this->redirect(array('action'=>'manage_lecturer'));
        }
    }
    
    public function delete_lecturer($id_lecturer){
        $this->loadModel('Lecturer');
        if(!$this->Lecturer->delete($id_lecturer)){
            $this->Session->setFlash(__('アカウントの削除が成功された'));
            $this->redirect(array('action'=>'manage_lecturer'));
        }else{
            $this->Session->setFlash(__('アカウントの削除ができない'));
            $this->redirect(array('action'=>'manage_lecturer'));
        }
    }
    
//以下は学生管理の機能だ
    public function manage_student(){
        $this->loadModel('User');
        $this->loadModel('Student');
        $sql = "SELECT * FROM Students, Users WHERE (Students.id = Users.id and Users.role = 'student')";
        //$sql = "SELECT * FROM users";
        $data = $this->Student->User->query($sql);
        //$data = $this->Admin->printfStudent();
        //var_dump($data);
        $this->set('data', $data);
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
            $this->Session->setFlash(__('パスワードのリセットが成功された'));
            $this->redirect(array('action'=>'manage_student'));
        }else{
            $this->Session->setFlash(__('パスワードのリセットができない'));
            $this->redirect(array('action'=>'manage_student'));
        }
    }
    
   public function reset_verifycode_student($id_student, $init_verifycode){
        //echo $id;
        //echo $init_password;
        $this->loadModel('Student');
        $sql = "UPDATE Students SET current_verifycode = '$init_verifycode' WHERE   Students.id = '$id_student'";
        if(!$this->User->query($sql)){
            $this->Session->setFlash(__('verifycodeのリセットが成功された'));
            $this->redirect(array('action'=>'manage_student'));
        }else{
            $this->Session->setFlash(__('verifycodeのリセットができない'));
            $this->redirect(array('action'=>'manage_student'));
        }
    }
    
    public function delete_student($id_student){
        $this->loadModel('Student');
        if(!$this->Student->delete($id_student)){
            $this->Session->setFlash(__('アカウントの削除が成功された'));
            $this->redirect(array('action'=>'manage_student'));
        }else{
            $this->Session->setFlash(__('アカウントの削除ができない'));
            $this->redirect(array('action'=>'manage_student'));
        }
    }    
    
}
?>
