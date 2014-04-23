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

<div class = "col-xs col-md-9 col-md-offset-2">
<h1 style='margin-top:0px'><?php echo $document['title']?></h1>
    <?php
    if(AuthComponent::user('role')=='student'){
        echo $this->Html->link('違反レポート', '/document/report/'.$document['id'], array('class'=>'floatRight btn btn-danger'));
    }
    $link = $this->html->url('/', true) . 'files' . DS . $document['link'];
    $path_parts = pathinfo($link);
    $extension = $path_parts['extension'];
    $extension = strtolower($extension);


    if ($extension == 'pdf'){
        echo "<div class='col-xs col-md-10'>";
        echo $this->Html->image('icon/trans.png', array("class"=>"transimage img-responsive", "oncontextmenu"=>'return false'));
        echo "<embed oncontextmenu='return false;' src=".$link." id = 'frame'>";
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
