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
    width:95%;
    height:95%;
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

if(AuthComponent::user('role')!='admin'){
    echo $this->Html->link('違反レポート', '/document/report/'.$document['id'], array('class'=>'floatRight label label-default'));
}
$link = $this->html->url('/', true) . 'files' . DS . $document['link'];
$path_parts = pathinfo($link);
$extension = $path_parts['extension'];
$extension = strtolower($extension);
if ($extension == 'pdf'){
    if ($learnable==LEARNABLE) {
        echo $this->Html->image('icon/trans.png', array("class"=>"transimage"));
    }else {
        echo $this->Html->image('icon/trans.png', array("class"=>"transimageBig"));
    }
    // echo "<iframe id = 'frame' style = 'z-index:-1' src='".$link."'></iframe>";
    echo "<embed src=".$link." id = 'frame'>";
    echo "</div>";
}else if ( $extension=='mp3' || $extension == 'wav'){

    echo "<div oncontextmenu='return false;'>".$this->Html->media($link,  array("controls", "autoplay", "id"=>"frameMusic"))."</div>";

}else if ($extension == 'mp4'){
    echo "<div oncontextmenu='return false;'>".$this->Html->media($link,  array("controls", "autoplay", "id"=>"frameClip"))."</div>";
}else if ($extension == 'gif'|| $extension == 'jpg'|| $extension == 'png'){
    echo "<div oncontextmenu='return false;'>".$this->Html->image($link,  array('width'=>'670', 'height'=>'690'))."</div>";
}
?>
</div>
