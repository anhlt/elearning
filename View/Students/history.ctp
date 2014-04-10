<?php $this->LeftMenu->leftMenuStudent(STUDENT_STUDY_HISTORY);?>
<div class = 'col-xs-13 col-md-9 '>
<?php 
echo " <h1> 勉強の歴史</h1>";
echo " <h3> 受講した授業</h3>"; 
echo "<table class = 'table table-bordered'>";
echo $this->Html->tableHeaders(array("登録の日", "授業の名前", "ステータス"));
$lessons = $student['Lesson'];
foreach($lessons as $lesson){
    $lesson_name = $lesson['name'];
    $days_attended = $lesson['StudentsLesson']['days_attended'];
    $lesson_id = $lesson['id'];
    $learn_state = "<font color=blue>勉強中</font>";
    $date = $this->Util->calDate($days_attended);
    if ($date > MAX_LEARN_DAY)
        $learn_state = "<font color=red>勉強した</font>";
    echo $this->Html->tableCells(array($days_attended, $this->Html->link($lesson_name, "/lesson/learn/".$lesson_id), $learn_state));     
} 
echo "</table>";
echo "<h3> 受けたテスト</h2>"; 
echo "<table class = 'table table-border'>";

echo "</table>";
?>
</div>

