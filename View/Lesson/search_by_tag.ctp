<?php 
$this->LeftMenu->leftMenuStudent(SEARCH_BY_TAG, "");
?>

<div class = 'col-xs-13 col-md-9 well'>
<h4>カテゴリのリスト</h4>
<?php 
foreach($tag_list as $row){
    $tag = $row['Tag'];
    echo $this->Html->link($tag['name'], '/lesson/searchByTag/'.$tag['name']). "|"; 
}

?>

</div>
<div class = 'col-xs-13 col-md-9 well' style='float:right'>
<?php 
    echo $this->Form->create("rankWay");
echo $this->Form->input ("rankStt", array("label"=>"並ぶ方", "class"=>"form-control", "options"=>array("いいねの数で", "受講学生の数で", "先生の名前", "先生のユーザ名"), 'style'=>"width:30%")); 
echo "<br>"; 
    echo $this->Form->submit("整理", array("class"=>"btn btn-success"));
?>
</div>
<div class = 'col-xs-13 col-md-9 well' style='float:right'>
<h2>結果</h2>
<?php
        echo "<table class = 'table table-bordered'>";
       echo $this->Html->tableHeaders(array("授業の名前", "先生のユーザ名", "先生の名前", "いいねの数", "受講学生の数"));  
            
       foreach($res as $row){
           
                echo $this->Html->tableCells(array($row['lessons']['name'], $row['users']['username'], $row['lecturers']['full_name'],$row[0]['liked_number'],  $row[0]['student_number'])); 
            }
        
        echo "</table"; 
?>
</div>
