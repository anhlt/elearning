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

    $("#myinput").keypress(function(e){
        if (e.which==13){
            tag_value = $("#myinput").val();
            window.location = "/lesson/search?search_value="+tag_value; 
        }
    })
}); 
</script>
<?php 

if (AuthComponent::user('role')=="student") {
    $this->LeftMenu->leftMenuStudent(STUDENT_CHOOSE_COURSE); 
}
else{
    echo "<div class='col-xs-5 col-md-3'>
        <ul class='nav nav-pills nav-stacked' id='myTab'>
            <li><a href='/lesson/search'>検索</a></li>
            <li><a href='/lecturer/'>授業管理</a></li>
            <li><a href='/lecturer/lesson'>新しい従業</a></li>
            <li><a href='/lecturer/edit'>情報を更新</a></li>
            <li><a href='/lecturer/delete'>アクアウートを削除</a></li>
        </ul>
    </div>";
}
?>
<div class="col-xs-13 col-md-9 well">
<?php
echo $this->Form->create("Lessons");
?>
<?php
echo $this->Form->input("searchOption", array('id'=>'searchOption', "label"=>"並ぶ方", 'class'=>'form-control', 'style'=>'width:20%', "options"=>array("先生の名前", "授業の名前", "カテゴリの名前")));
echo $this->Form->input("rankOption", array('id'=>'rankOption', 'label'=>'',  'class'=>'form-control', 'style'=>'width:20%', "options"=>array("ASC", "DESC")));
echo "<br>";
echo $this->Form->submit("リスト", array('id'=>'listBt', "class"=>"btn btn-success"));
?>
<?php
echo $this->Form->end();
?>
    <!-- Phan button search -->
</div>
<div class="col-xs-13 col-md-9 well" style = 'float:right'>
    <label>キーワードで検索</label>
    <input size = 20p id = 'myinput'></input>
    <button style="margin-left:60px" id = 'searchbt' class = 'btn btn-success'>検索</button>
    <br>
    <ul>
        <li>AND条件は＋で</li>
        <li>OR条件は|で</li>
    </ul>

</div>
<div class="col-xs-13 col-md-9 well" style = 'float:right'>
<?php
//Show result
echo "<table class='table table-bordered'>";
if (isset($rank_stt)){
    if(isset($lessons)){
        if ($rank_stt == RANK_BY_LECTURER){ //rank by lecturer's name 
            $flag =0;
            $count = 0; 
            foreach($lessons as $row){
                $count = $count + 1;
                $lesson = $row['Lesson'];
                $lecturer = $row['Lecturer'];
                $user = $row['Lecturer']['User'];
                if ($flag==0){
                    echo $this->Html->tableHeaders(array("id",'先生のユーザ名',  '先生の名前', "授業の名前",  "アップロードの日")); 
                    $flag = 1;
                }
                if ($count%2==0) {
                    echo $this->Html->tableCells(array($lesson['id'],$user['username'],  $lecturer['full_name'],  $this->Html->link($lesson['name'], array("controller"=>"lesson", "action"=>"learn", $lesson['id'])),  $lesson['update_date'])); 
                }else {
                    echo $this->Html->tableCells(array($lesson['id'],$user['username'],  $lecturer['full_name'],  $this->Html->link($lesson['name'], array("controller"=>"lesson", "action"=>"learn", $lesson['id'])),  $lesson['update_date']), array('class'=>'danger')); 
                }
            }
        }else if ($rank_stt==RANK_BY_LESSON){
            $flag =0;
            $count = 0; 
            foreach($lessons as $row){
                $count = $count + 1; 
                $lesson = $row['Lesson'];
                $lecturer = $row['Lecturer'];
                $user = $row['Lecturer']['User'];
                //    $tags = $row['Tag']; 
                if ($flag==0){
                    echo $this->Html->tableHeaders(array("id",  "授業の名前", "先生 のユーザ名", "先生の名前", "アップロードの日"));  
                    $flag = 1;
                }
                if ($count%2==0){
                    echo $this->Html->tableCells(array($lesson['id'],  $this->Html->link($lesson['name'], array("controller"=>"lesson", "action"=>"learn",  $lesson['id'])), $user['username'], $lecturer['full_name'],  $lesson['update_date'])); 
                }else {
                    echo $this->Html->tableCells(array($lesson['id'],  $this->Html->link($lesson['name'], array("controller"=>"lesson", "action"=>"learn",  $lesson['id'])), $user['username'], $lecturer['full_name'],  $lesson['update_date']), array('class'=>'danger')); 
                }
            }

        }else if ($rank_stt==RANK_BY_TAG){
            echo $this->Html->tableHeaders(array("id",  "カテゴリタグ", "授業の名前", "先生のユーザ名", "先生の名前", "アップロードの日"));  
            $count = 0;
            foreach($lessons as $row){
                $tag = $row['Tag'];
                $lesson_r = $row['Lesson'];
                $count = $count + 1; 
                echo $count; 
                foreach($lesson_r as $lesson) {
                    $lecturer = $lesson['Lecturer'];
                    $user = $lecturer['User'];
                    if ($count %2 ==0) {
                        echo $this->Html->tableCells(array($lesson['id'], $tag['name'],  $this->Html->link($lesson['name'], array("controller"=>"lesson", "action"=>"learn",  $lesson['id'])), $user['username'], $lecturer['full_name'],  $lesson['update_date'])); 
                    }else {
                        echo $this->Html->tableCells(array($lesson['id'], $tag['name'],  $this->Html->link($lesson['name'], array("controller"=>"lesson", "action"=>"learn",  $lesson['id'])), $user['username'], $lecturer['full_name'],  $lesson['update_date']), array('class'=>'danger')); 
                    }
                }
            }
        }
    }
    // }else if (isset($tags)){ //Truong hop search 
    //    // debug($tags);
    //      echo $this->Html->tableHeaders(array("id", "タグ",   "授業の名前", "先生 のユーザ名", "先生の名前", "アップロードの日"));  
    //     foreach($tags as $row){
    //         $lessons = $row['Lesson'];
    //         $tag = $row['Tag'];
    //         foreach ($lessons as $lesson) {
    //             echo $this->Html->tableCells(array($lesson['id'],$tag['name'],   $this->Html->link($lesson['name'], array("controller"=>"lesson", "action"=>"learn",$lesson['id'] )), $lesson['Lecturer']['User']['username'], $lesson['Lecturer']['full_name'],  $lesson['update_date'])); 

    //         }
    //     }
    // }
}else if (isset($search_value)){
    if (strpos($search_value, "＋")){
        $keyword_r =  explode("＋", $search_value);
    }else {
         $keyword_r =  explode("｜", $search_value);
    }
    echo $this->Html->tableHeaders(array("id", "授業の名前", "先生 のユーザ名", "先生の名前", "アップロードの日")) ;  
    foreach($lesson_search as $row){
        $lesson = $row['Lesson'];
        echo $this->Html->tableCells(array($lesson['id'], $this->Html->link($this->Util->changeStringHasKeyword($lesson['name'], $keyword_r), array("controller"=>"lesson", "action"=>"learn",$lesson['id'] ), array("escape"=>false)), $row['Lecturer']['User']['username'], $this->Util->changeStringHasKeyword($row['Lecturer']['full_name'], $keyword_r),  $lesson['update_date'])); 
    }
}
?>
</div>
