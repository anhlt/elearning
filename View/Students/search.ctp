<?php $this->LeftMenu->leftMenuStudent(STUDENT_CHOOSE_COURSE); ?>

<div class="col-xs-13 col-md-9">
<?php 
echo "<table class = 'table table-bordered'>";
foreach ($documents as $row){
    $document = $row['Document'];
    $document_title = str_replace($keyword, "<b>".$keyword."</b>", $document['title']);
    echo $this->Html->tableCells(array($document_title, "Document", $this->Html->link("View", "/document/show/".$document['id'])));
}

foreach ($lessons as $row){
    $lesson = $row['Lesson'];
    if (strpos($lesson['name'], $keyword)!= false){
        $lesson_name = str_replace($keyword, "<b>".$keyword."</b>", $lesson['name']);
        echo $this->Html->tableCells(array($lesson_name, "Lesson", $this->Html->link("View", "/lesson/show/".$lesson['id'])));
    }else {
        $lesson_summary = str_replace($keyword, "<b>".$keyword."</b>", $lesson['summary']);
        echo $this->Html->tableCells(array($lesson_summary, "Lesson", $this->Html->link("View", "/lesson/show/".$lesson['id'])));
    }
}

foreach ($lecturers as $row){
    $lecturer = $row['Lecturer'];
    if (strpos($lecturer['full_name'], $keyword)!= false){
        $lecturer_fullname = str_replace($keyword, "<b>".$keyword."</b>", $lecturer['full_name']);
        echo $this->Html->tableCells(array($lecturer_fullname, "Lecturer", $this->Html->link("View", "/lecturer/profile/".$lecturer['id'])));
    }else {
        $lecturer_username = str_replace($keyword, "<b>".$keyword."</b>", $row['User']['username']);
        echo $this->Html->tableCells(array($lecturer_username, "Lecturer", $this->Html->link("View", "/lecturer/profile/".$lecturer['id'])));
    }
}

foreach ($students as $row){
    $student = $row['Student'];
    if (strpos($student['full_name'], $keyword)!= false){
        $student_fullname = str_replace($keyword, "<b>".$keyword."</b>", $student['full_name']);
        echo $this->Html->tableCells(array($student_fullname, "Student", $this->Html->link("View", "/students/profile/".$student['id'])));
    }else {
        $student_username = str_replace($keyword, "<b>".$keyword."</b>", $row['User']['username']);
        echo $this->Html->tableCells(array($student_username, "Student", $this->Html->link("View", "/students/profile/".$student['id'])));
    }
}
echo "</table>";
?>
</div>

