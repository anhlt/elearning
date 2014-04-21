<?php $this->LeftMenu->leftMenuStudent(STUDENT_CHOOSE_COURSE); ?>

<div class="col-xs-13 col-md-9">
    <h2>検索の結果</h2>
<?php 
echo "<table class = 'table table-bordered'>";
foreach ($documents as $row){
    $document = $row['Document'];
   // $document_title = str_replace($keyword, "<b>".$keyword."</b>", $document['title']);
    $document_title = $this->Util->changeString($document['title'], $keyword);
    echo $this->Html->tableCells(array($document_title, "資料", $this->Html->link("表示", "/document/show/".$document['id'])));
}

foreach ($lessons as $row){
    $lesson = $row['Lesson'];
    if (strpos($lesson['name'], $keyword)!= false){
     //   $lesson_name = str_replace($keyword, "<b>".$keyword."</b>", $lesson['name']);
        $document_title = $this->Util->changeString($lesson['name'], $keyword);
        echo $this->Html->tableCells(array($lesson_name, "授業", $this->Html->link("表示", "/lesson/learn/".$lesson['id'])));
    }else {
        $lesson_summary = str_replace($keyword, "<b>".$keyword."</b>", $lesson['summary']);
        echo $this->Html->tableCells(array($lesson_summary, "授業", $this->Html->link("表示", "/lesson/learn/".$lesson['id'])));
    }
}

foreach ($lecturers as $row){
    $lecturer = $row['Lecturer'];
    if (strpos($lecturer['full_name'], $keyword)!= false){
        //$lecturer_fullname = str_replace($keyword, "<b>".$keyword."</b>", $lecturer['full_name']);
        $document_title = $this->Util->changeString($lecturer['full_name'], $keyword);
        echo $this->Html->tableCells(array($lecturer_fullname, "先生", $this->Html->link("表示", "/lecturer/profile/".$lecturer['id'])));
    }else {
        $lecturer_username = str_replace($keyword, "<b>".$keyword."</b>", $row['User']['username']);
        echo $this->Html->tableCells(array($lecturer_username, "先生", $this->Html->link("表示", "/lecturer/profile/".$lecturer['id'])));
    }
}

foreach ($students as $row){
    $student = $row['Student'];
    if (strpos($student['full_name'], $keyword)!= false){
    //    $student_fullname = str_replace($keyword, "<b>".$keyword."</b>", $student['full_name']);
         $document_title = $this->Util->changeString($student['full_name'], $keyword);
        echo $this->Html->tableCells(array($student_fullname, "学生", $this->Html->link("表示", "/students/profile/".$student['id'])));
    }else {
      //  $student_username = str_replace($keyword, "<b>".$keyword."</b>", $row['User']['username']);
        $document_title = $this->Util->changeString($row['User'], $keyword);
        echo $this->Html->tableCells(array($student_username, "学生", $this->Html->link("表示", "/students/profile/".$student['id'])));
    }
}
echo "</table>";
?>
</div>

