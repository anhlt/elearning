<?php 
class LeftMenuHelper extends AppHelper{
    public function leftMenu(){
        echo " 
            <div class='col-xs-5 col-md-3'>
            <ul class='nav nav-pills nav-stacked'>
            <li><a href='/lecturer/'>Class Manage </a></li>
            <li class='active'><a href='#'>New Class</a></li>
            <li><a href='#'>Messages</a></li>
            </ul>
            </div>"; 
    }
    public function leftMenuStudent(){
        echo " 
            <div class='col-xs-5 col-md-3'>
            <ul class='nav nav-pills nav-stacked'>
            <li><a href='/students/profile'> プロファイル管理 </a></li>
            <li class='active'><a href='/lesson/search'>授業を選択</a></li>
            <li><a href='/students/history'>勉強の歴史</a></li>
            </ul>
            </div>"; 
    }


}
?>
