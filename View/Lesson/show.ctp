<?php $this->LeftMenu->leftMenuStudent();?>
    <div class="col-xs-13 col-md-9 well">  
<?php
foreach($lessons as $row){
    $lesson = $row['Lesson'];
    $lesson_id = $lesson['id'];
    echo "<div class = 'btn btn-warning'>". $this->Html->link(" 登録", array("controller"=>"lessons", "action"=>"register",$lesson_id))."</div>"; 
    echo "<br>タイトル</br>";
    echo $lesson['name']; 
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
  echo "<br>";  
}

?>

<div>
