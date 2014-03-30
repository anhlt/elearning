<?php $this->LeftMenu->leftMenuStudent();?>
<div class = 'col-xs-13 col-md-9 '>
<?php 
echo " <h1> 勉強の歴史</h1>";
echo " <h3> 受講した授業</h3>"; 
echo "<table class = 'table table-bordered'>";
echo $this->Html->tableHeaders(array("登録の日", "授業の名前", "ステータス"));
foreach($history as $row){
    $studentsLesson = $row['StudentsLesson'];
    $lesson = $row['Lesson'];
    echo $this->Html->tableCells(array($studentsLesson['days_attended'], $lesson['name'], "勉強中"));     
} 
echo "</table>";
echo "<h3> 受けたテスト</h2>"; 
echo "<table class = 'table table-border'>";

echo "</table>";
?>
</div>

