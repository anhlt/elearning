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
    width:90%;
    height:100%;
}
.transimageBig{
    position:absolute;
    top:0px;
    left:0px;
    z-index:3;
    width:100%;
    height:100%;
}
.floatRight{
    float:right;
}
</style>
<script>
$(document).ready(function(){
    $(document).keypress(function(e){
         e.preventDefault();
        //console.log(e.which)
    });
    $("#frameClip").mousedown(function(e){
        console.log("abc")
        switch(e.which){
            case 1:
            console.log("chuot trai")
            break;
            case 2:
            console.log("middle")
            break;
            case 3:
            console.log("phai")
         //   alert("abc");
            break
        }
    });
});
</script>
<?php if(AuthComponent::user('role')=='student') $this->LeftMenu->leftMenuStudent(STUDENT_CHOOSE_COURSE, "勉強");?>

<div class = "col-xs col-md-9 well "  >
<?php

echo "<h1 style='margin-top:0px'>".$document['title']."</h1>"; 
echo $this->Html->link('違反レポート', '/document/report/'.$document['id'], array('class'=>'floatRight label label-default'));
//echo "<input id = 'frame' type = 'text' oncontextmenu = 'return false' />"; 
$link = $document['link'];
if (stripos(strrev(strtolower($link)), strrev(PDF))===0){
    if ($learnable==1) {
        echo $this->Html->image('icon/trans.png', array("class"=>"transimage"));
    }else {
        echo $this->Html->image('icon/trans.png', array("class"=>"transimageBig"));
    }
    echo "<iframe id = 'frame' style = 'z-index:-1' src='/files/".$link."'></iframe>";
    echo "</div>";
}else if (stripos(strrev(strtolower($link)),strrev(MP3)) ||stripos(strrev(strtolower($link)),strrev(WAV))===0){
 //   echo "<div oncontextmenu='return false;'>"."<iframe id = 'frameMusic' src='/files/".$link."'></iframe>"."</div>";
     echo "<div oncontextmenu='return false;'>".$this->Html->media('/files/'.$link,  array("controls", "autoplay", "id"=>"frameMusic"))."</div>";
}else if (stripos(strrev(strtolower($link)),strrev(MP4))===0){
    echo "<div oncontextmenu='return false;'>".$this->Html->media('/files/'.$link,  array("controls", "autoplay", "id"=>"frameClip"))."</div>";
}else if (stripos(strrev(strtolower($link)),strrev(GIF))===0  || stripos(strrev(strtolower($link)),strrev(JPG))===0 || stripos(strrev(strtolower($link)),strrev(PNG))===0){
    echo "<div oncontextmenu='return false;'>".$this->Html->image('/files/'.$link,  array('width'=>'670', 'height'=>'690'))."</div>";
}
?>
</div>
