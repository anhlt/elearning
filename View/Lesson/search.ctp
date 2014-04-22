<style>
#myinput{
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
    -webkit-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
}

label {
    display: inline;
    width: 20%;
    float: left;
}
textarea {
    width: 76%;
    display: inline;
}
form div.submit {
    display: inline;
}
#searchOption{
    float: left; 
}
#listBt{

}
</style>
<script>
$(document).ready(function(){
    $("#searchbt").click(function(){
        tag_value = $("#myinput").val();
        window.location = "/lesson/search?search_value="+tag_value; 
    });

    // $("#myinput").keypress(function(e){
    //     if (e.which==13){
    //         tag_value = $("#myinput").val();
    //         window.location = "/lesson/search?search_value="+tag_value; 
    //     }
    // })
}); 
</script>
<?php 

if (AuthComponent::user('role')=="student") {
    $this->LeftMenu->leftMenuStudent(STUDENT_CHOOSE_COURSE); 
}
else{
    echo'
    <div class="col-xs-3 col-md-3">
    <a class="btn btn-info" href="javascript:history.go(-1)">戻る</a>
    </div>
    ';
}
?>
<div class="col-xs-13 col-md-9 well">
<?php
echo $this->Form->create("Lessons");
?>
<?php
echo $this->Form->input("searchOption", array('id'=>'searchOption', "label"=>"部分", 'class'=>'form-control', 'style'=>'width:20%;margin-right:30px', "options"=>array("先生の名前", "授業の名前", "カテゴリの名前")));
echo $this->Form->input("rankOption", array('id'=>'rankOption', 'label'=>'並ぶ方',  'class'=>'form-control', 'style'=>'width:20%', "options"=>array("ASC", "DESC")));
echo "<br>";
echo $this->Form->input("keyword", array('style'=>'width:20%',  'class'=>'form-control', 'label'=>'キーワードで検索'));
echo " <ul>
        <li>AND条件は＋で</li>
        <li>OR条件は｜で</li>
    </ul>";
echo $this->Form->submit("検索", array('id'=>'listBt', "class"=>"btn btn-success"));
echo $this->Form->end();
?>
</div>
<div class="col-xs-13 col-md-9 well" style = 'float:right'>
<?php
//Show result
echo "<table class='table table-bordered'>";
echo "<h2>結果</h2>";
if (isset($rank_stt)){
    if(isset($lessons)){
        if ($rank_stt == RANK_BY_LECTURER){ //rank by lecturer's name 
            $count = 0; 
            echo $this->Html->tableHeaders(array("id",'先生のユーザ名',  '先生の名前', "授業の名前",  "アップロードの日"));
            foreach($lessons as $row){
                $lesson = $row['Lesson'];
                $lecturer = $row['Lecturer'];
                $user = $row['Lecturer']['User'];         
                if (isset($user_truoc) && $user_truoc != $user['username']){
                    $count = $count + 1;
                }
                if ($count%2==0) {
                    echo $this->Html->tableCells(array($lesson['id'],$user['username'],  $lecturer['full_name'],  $this->Html->link($lesson['name'], array("controller"=>"lesson", "action"=>"learn", $lesson['id'])),  $lesson['update_date'])); 
                }else {
                    echo $this->Html->tableCells(array($lesson['id'],$user['username'],  $lecturer['full_name'],  $this->Html->link($lesson['name'], array("controller"=>"lesson", "action"=>"learn", $lesson['id'])),  $lesson['update_date']), array('class'=>'danger')); 
                }
                $user_truoc = $user['username'];
            }
        }else if ($rank_stt==RANK_BY_LESSON){
            $count = 0; 
            echo $this->Html->tableHeaders(array("id",  "授業の名前", "先生 のユーザ名", "先生の名前", "アップロードの日"));  
            foreach($lessons as $row){ 
                $lesson = $row['Lesson'];
                $lecturer = $row['Lecturer'];
                $user = $row['Lecturer']['User'];
                if (isset($lesson_truoc) && $lesson_truoc != $lesson['id']){
                     $count = $count + 1;
                }
                if ($count%2==0){
                    echo $this->Html->tableCells(array($lesson['id'],  $this->Html->link($lesson['name'], array("controller"=>"lesson", "action"=>"learn",  $lesson['id'])), $user['username'], $lecturer['full_name'],  $lesson['update_date'])); 
                }else {
                    echo $this->Html->tableCells(array($lesson['id'],  $this->Html->link($lesson['name'], array("controller"=>"lesson", "action"=>"learn",  $lesson['id'])), $user['username'], $lecturer['full_name'],  $lesson['update_date']), array('class'=>'danger')); 
                }
                $lesson_truoc = $lesson['id'];
            }

        }else if ($rank_stt==RANK_BY_TAG){
            echo $this->Html->tableHeaders(array("id",  "カテゴリタグ", "授業の名前", "先生のユーザ名", "先生の名前", "アップロードの日"));  
            $count = 0;
            foreach($lessons as $row){
                $tag = $row['Tag'];
                $lesson = $row['Lesson'];                 
                $lecturer = $lesson['Lecturer'];
                $user = $lecturer['User'];
                if (isset($tag_truoc) && $tag_truoc!=$tag['id']){
                    $count = $count + 1;    
                }
               // debug($count); 
                if ($count %2 ==0) {
                //    debug("flag");
                    echo $this->Html->tableCells(array($lesson['id'], $tag['name'],  $this->Html->link($lesson['name'], array("controller"=>"lesson", "action"=>"learn",  $lesson['id'])), $user['username'], $lecturer['full_name'],  $lesson['update_date'])); 
                }else {
                    echo $this->Html->tableCells(array($lesson['id'], $tag['name'],  $this->Html->link($lesson['name'], array("controller"=>"lesson", "action"=>"learn",  $lesson['id'])), $user['username'], $lecturer['full_name'],  $lesson['update_date']), array('class'=>'danger')); 
                }
                $tag_truoc = $tag['id'];
               // echo $tag['id'];
            }
        }
    }
}
?>
</div>
