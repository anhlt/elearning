<?php $this->LeftMenu->leftMenuStudent(STUDENT_CHOOSE_COURSE, "勉強");?>
<div class = 'col-xs-10 col-md-9 well'>
    <h1>管理者に違反レポートを送る</h1>
<?php
if (isset($message)){
    echo $message; 
}else {
    $fileName = $document['Document']['title'];
    echo "<h3>「".$fileName. "」のファイルに対する</h3>";
    echo $this->Form->create("report");
    echo $this->Form->textarea("content", array("rows"=>10, "cols"=>80, "placeholder"=>"ここに")) ;
    echo $this->Form->input("送る", array("type"=>"submit", "label"=>""));
    echo $this->Form->end();
}
?>
</div>
