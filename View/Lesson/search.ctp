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
    margin-left: 150px;
    height: 21px;  
}
</style>
<script>
$(document).ready(function(){
    $("#searchbt").click(function(){
        tag_value = $("#myinput").val();
        window.location = "/lesson/search?search_value="+tag_value; 
    });
}); 
</script>
<?php $this->LeftMenu->leftMenuStudent(); ?>

    <div class="col-xs-13 col-md-9">
<?php
echo $this->Form->create("Lessons");
?>
<?php
echo $this->Form->input("rank", array('id'=>'searchOption', "label"=>"並ぶ方", "options"=>array("先生の名前", "授業の名前", "タグの名前"), "class"=>"inline"));
echo $this->Form->submit("リスト", array('id'=>'listBt'));
?>
<?php
echo $this->Form->end();
?>
<!-- Phan button search -->
<br>
<label> タグで検索</label>
<input size = 20p id = 'myinput'></input>
<button style="margin-left:60px" id = 'searchbt'>検索</button>
<br>
</br>
<?php
//Show result
echo "<table class='table table-bordered'>";
if (isset($rank_stt)){
    if(isset($lessons)){
        if ($rank_stt == RANK_BY_LECTURER){ //rank by lecturer's name 
            $flag =0;
            foreach($lessons as $row){
                $lesson = $row['Lesson'];
                $lecturer = $row['Lecturer'];
                $user = $row['Lecturer']['User'];
                if ($flag==0){
                    echo $this->Html->tableHeaders(array("id", "先生", "授業の名前",  "アップロードの日")); 
                    $flag = 1;
                }
                echo $this->Html->tableCells(array($lesson['id'], $user['username'],  $this->Html->link($lesson['name'], array("controller"=>"lesson", "action"=>"show", $lesson['id'])),  $lesson['update_date'])); 
            }
        }else if ($rank_stt==RANK_BY_LESSON){
            $flag =0;
            foreach($lessons as $row){
                $lesson = $row['Lesson'];
                $lecturer = $row['Lecturer'];
                $user = $row['Lecturer']['User'];
                if ($flag==0){
                    echo $this->Html->tableHeaders(array("id",  "授業の名前", "先生 のユーザ名", "先生の名前", "アップロードの日"));  
                    $flag = 1;
                }
                echo $this->Html->tableCells(array($lesson['id'],  $this->Html->link($lesson['name'], array("controller"=>"lesson", "action"=>"show",  $lesson['id'])), $user['username'], $lecturer['full_name'],  $lesson['update_date'])); 
            }

        }else if ($rank_stt==RANK_BY_TAG){
            $flag =0;
            foreach($lessons as $row){
                $lesson = $row['Lesson'];
                $lecturer = $row['Lecturer'];
                $user = $row['User'];
                $tag = $row['Tag'];
                if ($flag==0){
                    echo $this->Html->tableHeaders(array("id",  "タグ", "授業の名前", "先生のユーザ名", "先生の名前", "アップロードの日"));  
                    $flag = 1;
                }
                echo $this->Html->tableCells(array($lesson['id'], $tag['name'],  $this->Html->link($lesson['name'], array("controller"=>"lesson", "action"=>"show",  $lesson['id'])), $user['username'], $lecturer['full_name'],  $lesson['update_date'])); 
            }
        }
    }
}else if (isset($lessons)){ //Truong hop search 
    $flag =0;
    foreach($lessons as $row){
        $lessons = $row['Lesson'];
        $tag = $row['Tag'];
        foreach ($lessons as $lesson) {
            if ($flag==0){
                echo $this->Html->tableHeaders(array("id", "タグ",   "授業の名前", "先生 のユーザ名", "先生の名前", "アップロードの日"));  
                $flag = 1;
            }
            echo $this->Html->tableCells(array($lesson['id'],$tag['name'],   $this->Html->link($lesson['name'], array("controller"=>"lesson", "action"=>"show",$lesson['id'] )), $lesson['Lecturer']['User']['username'], $lesson['Lecturer']['full_name'],  $lesson['update_date'])); 
        
        }
    }
}
?>
</div>
