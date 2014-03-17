<script type="text/javascript">
function upcomment(event, lesson_id){
    if (event.keyCode==13){
        console.log("gia tri comment"+lesson_id);
        content = $("#commentIp").val();
        window.location = "/lesson/comment/"+lesson_id+"/"+content;
    }
}
</script>
<style>
#username{
    margin-top :10px; 
    border : solid 2px red;
    padding: 5px;
    background: orange;
}
</style>

<?php $this->LeftMenu->leftMenuStudent();?>
    <div class="col-xs-13 col-md-9 well">  
<?php
foreach($lessons as $row){
    $lesson = $row['Lesson'];
    $lesson_id = $lesson['id'];
    echo "<h1>". $lesson['name']. "</h1>"; 
    echo "<br>まとめ</br>";
    echo $lesson['summary'];
}
//hien thi tai lieu
echo "<br>";
echo "資料";
echo "<br>";
foreach($documents as $row){
    $document = $row['Document'];
    $link = $document['link'];
    echo $this->Html->link($document['title'], array("controller"=>"documents", "action"=>"show", $document['id'] ));
    if (stripos(strrev($link),strrev(PDF))===0) echo "[pdf]";
    if (stripos(strrev($link), strrev(TSV))===0) echo "[tsv]";
    echo $this->Html->link("[違反レポート]", array("controller"=>"documents", "action"=>"report", $lesson_id, $document['id']));
    echo "<br>";  
}

?>
<br> 
<?php if (isset($liked) && $liked == 1) {
    echo "ありがとう";  
    echo "<div class = 'btn btn-warning'>";
    echo $this->Html->link("悪いね", array("controller"=>"lessons", "action"=>"dislike", $lesson_id));
    echo "</div>";
}else {
    echo"<div class = 'btn btn-warning'>";
    echo $this->Html->link("いいね", array("controller"=>"lessons", "action"=>"like", $lesson_id));
    echo "</div>";
}
?>
<br>
<br>
<ul class="nav nav-tabs">
  <li class="active"><a href="#hyouka" data-toggle="tab">評価</a></li>
  <li><a href="#comment" data-toggle="tab">コメント</a></li>
</ul>
<div class="tab-content" style='padding:10px;'>
  <div class="tab-pane active" id="hyouka">
<?php 
echo "<br>この授業に参加する人の数: ".$learnedPeople."人</br>";
echo "<br>いいね:".$likePeople."人<br>";
foreach($comments as $row){
    $comment = $row['Comment'];
    $username = $row['User']['username'];
    echo "<p class = 'btn btn-info' style = 'margin:5px'> ".$username." </p> ".$comment['content'];
    echo "<br>";
}
echo "<input id = 'commentIp' type ='text' size = '70' placeholder
    = 'あなたのコメント'style='margin-top:10px;' class='form-control'  onkeypress = 'upcomment(event, ".$lesson_id.")' /> "; 
?> </div>
  <div class="tab-pane" id="comment">
<?php 
?></div>
</div>
</div>
