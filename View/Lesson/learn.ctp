<script type="text/javascript">
function upcomment(event, lesson_id){
    if (event.keyCode==13){
        console.log("gia tri comment"+lesson_id);
        content = $("#commentIp").val();
        window.location = "/lesson/comment/"+lesson_id+"/"+content;
    }
}
</script>
<script type='text/javascript'>
$(document).ready(function(){
    $.smoothScroll({
        scrollTarget: '#scroll'
    });
});
</script>
<style>
#username{
    margin-top :10px; 
    border : solid 2px red;
    padding: 5px;
    background: orange;
}
</style>
<?php
echo $this->Session->flash(); 
if (AuthComponent::user('role')=="student") {
    $this->LeftMenu->leftMenuStudent(STUDENT_CHOOSE_COURSE); 
}
else{
    // echo'
    // <div class="col-xs-3 col-md-3">
    // <a class="btn btn-info" href="/lecturer/">戻る</a>
    // </div>
    // ';
}?>    
<div class="col-xs-13 col-md-9 well">  
<?php
$lesson = $lessons['Lesson'];
$lesson_id = $lesson['id'];
echo "<h1 style='margin-top:0px'>".$lesson['name']."</h1>"; 
if ($learnable == LEARNABLE){
    if (isset($liked) && $liked == 1) {
        echo $this->Html->image("icon/unlike.png", array("url"=>"/lesson/dislike/".$student_lesson_id, "width"=>"20", "height"=>"20"));
    }else {
        echo $this->Html->image("icon/like.png", array("url"=>"/lesson/like/".$student_lesson_id, "width"=>"20", "height"=>"20"));
    }
}else {
    if ($learnable == BANED) {
        echo "<div class='alert alert-danger'>あなたは先生に禁止された</div>";
    }

    if ($learnable == OVER_DAY){
         echo "<div class='alert alert-danger'>勉強の時間は".MAX_LEARN_DAY."日以上だ</div>";
    }
}
$learnedPeople = $lessons['Student'];
$learnedPeopleNumber = count($learnedPeople); 
echo " <span class='label label-primary'>".$likePeople."人/".$learnedPeopleNumber."人いいねした</span>";
 echo "   <span class = 'label label-danger'>".$this->Html->link('違犯レポート', "/lesson/report_violate/".$lesson_id)."</span>";
if ($learnable==UNREGISTER){
    if (AuthComponent::user('role')=='student'){
        echo "   <span class = 'label label-danger'>".$this->Html->link('登録', "/lesson/register/".$lesson_id)."</span>";
    }//else{
    //     echo "   <span class = 'label label-danger'>".$this->Html->link('違犯レポート', "/lesson/report_violate/".$lesson_id)."</span>";
    // }
}else if ($learnable == OVER_DAY){
     echo "   <span class = 'label label-danger'>".$this->Html->link('再登録', "/lesson/register/".$lesson_id)."</span>";
}
echo "<br>";

echo "<div class ='bs-callout bs-callout-info'>".$lesson['summary']."</div>";
//hien thi tai lieu
echo "<div class = 'bs-callout bs-callout-info'>";
echo "<h3 style='margin-top:0px'>資料一覧</h3>";
$documents = $lessons['Document'];
foreach($documents as $row){
    $document = $row;
    $link = $document['link'];
    $path_parts = pathinfo($link);
    $extension = $path_parts['extension'];
    $extension = strtolower($extension);

    //$link_downcase = strtolower($link);
    if ($extension == PDF) echo $this->Html->image("icon/pdf.png", array("height"=>"20", "width"=>"20"))." ";
    if ($extension == MP3) echo $this->Html->image("icon/mp3.png", array("height"=>"20", "width"=>"20"))." ";
    if ($extension == MP4) echo $this->Html->image("icon/mp4.png", array("height"=>"20", "width"=>"20"))." ";
    if ($extension == PNG) echo $this->Html->image("icon/png.png", array("height"=>"20", "width"=>"20"))." ";
    if ($extension == JPG) echo $this->Html->image("icon/jpg.png", array("height"=>"20", "width"=>"20"))." ";
    if ($extension == GIF) echo $this->Html->image("icon/gif.png", array("height"=>"20", "width"=>"20"))." ";
    if ($extension == WAV) echo $this->Html->image("icon/wav.png", array("height"=>"20", "width"=>"20"))." ";
 

    if ($extension == PDF) {
        echo $this->Html->link($document['title'], array("controller"=>"document", "action"=>"show", $document['id'] ));
    }else if ($learnable != LEARNABLE){
        echo $document['title'];
    }else {
        echo $this->Html->link($document['title'], array("controller"=>"document", "action"=>"show", $document['id'] ));
    }
    echo "</br>";  
}
echo "</div>";
?>
<?php
//テストのTSVを表示する
echo "<div class = 'bs-callout bs-callout-info'>";
echo "<h3 style='margin-top:0px'>テスト一覧</h3>";
$tests = $lessons['Test'];
foreach($tests as $row){
    $test = $row;
    $link = $test['link'];
    if (stripos(strrev($link),strrev(TSV))===0) echo $this->Html->image("icon/tsv.png", array("height"=>"20", "width"=>"20"))." ";
    if ($learnable == LEARNABLE){ 
        echo $this->Html->link($test['title'], array("controller"=>"tests", "action"=>"show", $test['id'] ));
    }else {
        echo $test['title'];
    }
    echo "<br>";  
}
echo "</div>";
?>
<?php if (isset($scroll)) echo "<div id = 'scroll'></div>";?>
<?php if ($learnable == LEARNABLE){ ?>
<ul class="nav nav-tabs">
  <li class="active"><a href="#hyouka" data-toggle="tab">評価</a></li>
</ul>
<div class="tab-content" style='padding:10px;'>
  <div class="tab-pane active" id="hyouka">
<?php
$comments = $lessons['Comment'];
foreach($comments as $comment){
    $username = $comment['User']['username'];
    $user_id = $comment['User']['id'];
    //  echo $this->Html->image("icon/student.jpg", array("width"=>30, "height"=>30));
    echo "<span style='color:blue'>".$username."</span>:".$comment['content'];
    echo "<br>";
}
echo "<input id = 'commentIp' type ='text' size = '70' placeholder
    = 'あなたのコメント'style='margin-top:10px;' class='form-control'  onkeypress = 'upcomment(event, ".$lesson_id.")' /> "; 
?> </div>
  <div class="tab-pane" id="comment">
<?php
}
?></div>
</div>
</div>
