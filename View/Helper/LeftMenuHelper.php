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
    public function leftMenuStudent($index, $nhanhcon=""){
        if ($index ==STUDENT_CHOOSE_COURSE) {
            echo " 
                <div class='col-xs-5 col-md-3'>
                <ul class='nav nav-pills nav-stacked'>
                <li><a href='/students/profile'> プロファイル管理 </a></li>";
            if ($nhanhcon == ""){
                echo "<li class='active'><a href='/lesson/search'>授業を選択</a></li>";
                
            }else {
                echo "<li class='active'><a href='/lesson/search'>授業を選択>".$nhanhcon."</a></li>";
            }
            echo "<li><a href='/lesson/searchByTag'>カテゴリで検索</a></li>";
            echo " <li><a href='/students/history'>勉強の歴史</a></li>
                </ul>
                </div>";
        }else if ($index ==STUDENT_PROFILE){
            echo " 
                <div class='col-xs-5 col-md-3'>
                <ul class='nav nav-pills nav-stacked'>";
            if ($nhanhcon == ""){
               echo  "<li class = 'active' ><a href='/students/profile'> プロファイル管理 </a></li>";
            }else {
               echo  "<li class = 'active' ><a href='/students/profile'> プロファイル管理>".$nhanhcon."</a></li>";
            }
            echo "<li><a href='/lesson/search'>授業を選択</a></li>
                <li><a href='/lesson/searchByTag'>カテゴリで検索</a></li>
                <li><a href='/students/history'>勉強の歴史</a></li>
                </ul>
                </div>";

        }else if ($index == STUDENT_STUDY_HISTORY){
            echo " 
                <div class='col-xs-5 col-md-3'>
                <ul class='nav nav-pills nav-stacked'>
                <li><a href='/students/profile'> プロファイル管理 </a></li>
                <li><a href='/lesson/search'>授業を選択</a></li>
                <li><a href='/lesson/searchByTag'>カテゴリで検索</a></li>
                <li class = 'active'><a href='/students/history'>勉強の歴史</a></li>
                </ul>
                </div>";

        }else if ($index == SEARCH_BY_TAG){
             echo " 
                <div class='col-xs-5 col-md-3'>
                <ul class='nav nav-pills nav-stacked'>
                <li><a href='/students/profile'> プロファイル管理 </a></li>
                <li><a href='/lesson/search'>授業を選択</a></li>
                <li class = 'active'><a href='/lesson/searchByTag'>カテゴリで検索</a></li>
                <li><a href='/students/history'>勉強の歴史</a></li>
                </ul>
                </div>";
 
        }
    }
}
?>
