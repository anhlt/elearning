<style>
#frame{
width: 660px;
height: 650px;
}
#frameMusic{
    width:600px;
    height:100px;
}
#frameClip{
    width: 600px;
    height:600px;
}
.transimage{
    position:absolute;
    top:0px;
    left:0px;
    z-index:3;
    width:660px;
    height:700px;
}
.transimageTo{
    position:absolute;
    top:0px;
    left:0px;
    z-index:3;
    width:700px;
    height:700px;
}
</style>
<?php $this->LeftMenu->leftMenuStudent(STUDENT_CHOOSE_COURSE, "勉強");?>

<div class = "col-xs col-md-9 well "  >
<?php

echo "<h1 style='margin-top:0px'>".$document['title']."</h1>";  
//echo "<input id = 'frame' type = 'text' oncontextmenu = 'return false' />"; 
$link = $document['link'];
if (stripos(strrev(strtolower($link)), strrev(PDF))===0){
    if ($learnable==1) {
        echo $this->Html->image('icon/trans.png', array("class"=>"transimage"));
    }else {
        echo $this->Html->image('icon/trans.png', array("class"=>"transimageTo"));
    }
    echo "<iframe id = 'frame' style = 'z-index:-1' src='/files/".$link."'></iframe>";
    echo "</div>";
}else if (stripos(strrev(strtolower($link)),strrev(MP3))===0){
    echo "<iframe id = 'frameMusic' src='/files/".$link."'></iframe>";
}else if (stripos(strrev(strtolower($link)),strrev(MP4))===0){
    echo $this->Html->media('/files/'.$link,  array("controls", "autoplay", "id"=>"frameClip"));
}
?>
</div>
