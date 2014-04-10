<style>
iframe{
width: 650px;
height: 650px;
}
</style>
<script>
</script>
<?php $this->LeftMenu->leftMenuStudent(STUDENT_CHOOSE_COURSE);?>

<div class = "col-xs col-md-9 well "  >
<?php

echo $document['title']."<br>";  
//echo "<input id = 'frame' type = 'text' oncontextmenu = 'return false' />"; 
$link = $document['link'];
if (stripos(strrev($link), strrev(PDF))===0){
echo "<iframe id = 'frame' oncontextmenu = 'return false'  src='http://elearning.com/pdf/1.pdf'></iframe>";
}else if (stripos(strrev($link),strrev(TSV))===0){
    echo $this->TsvReader->getTest($link);
//    debug($this->TsvReader->getViewTSV($link));
}
?>
</div>
