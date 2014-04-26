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
    $("#bigBt").click(function(){
        $("#anh").attr('height', 600); 
        $("#anh").attr('width', 600);

    });

     $("#normalBt").click(function(){
        $("#anh").attr('height', 420); 
        $("#anh").attr('width', 420);

    });

    $("#smallBt").click(function(){
        $("#anh").attr('height', 300); 
        $("#anh").attr('width', 300);

    });

});
</script>

<div class = "col-xs col-md-9 col-md-offset-2">
<h1 style='margin-top:0px'><?php echo $document['title']?></h1>
    <?php
    if(AuthComponent::user('role')=='student'){
        echo $this->Html->link('違反レポート', '/document/report/'.$document['id'], array('class'=>'floatRight btn btn-danger'));
    }
    $link = $this->html->url('/', true) . 'files' . DS . urlencode($document['link']);
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
  //      echo "<div >".$this->Html->media($link,  array(controls, "autoplay", "id"=>"frameClip"))."</div>";
  
        echo "<video id = 'video1' width = 420 oncontextmenu='return false;'>";
        echo "<source src='$link'>";
        echo "</video>";
        ?>
        <br>
        <button class='btn' onclick="playPause()">Play/Pause</button> 
        <button class = 'btn' onclick="stop()">Stop</button>
        <button class = 'btn' onclick="makeBig()">Big</button>
        <button class = 'btn' onclick="makeSmall()">Small</button>
        <button class = 'btn' onclick="makeNormal()">Normal</button>
        <?php
    }else if ($extension == 'gif'|| $extension == 'jpg'|| $extension == 'png'){
        echo "<div oncontextmenu='return false;'>".$this->Html->image($link,  array('width'=>'420', 'height'=>'420', 'id'=>'anh'))."</div>";
        ?>
        <br>
        <button class = 'btn' id ='bigBt'>Big</button>
        <button class = 'btn' id = 'smallBt'>Small</button>
        <button class = 'btn' id = 'normalBt'>Normal</button>
        <?php 
    }

    ?>
</div>

<script>

var myVideo=document.getElementById("video1"); 

function playPause()
{ 
    if (myVideo.paused) 
      myVideo.play(); 
  else 
      myVideo.pause(); 
} 

function makeBig()
{ 
    myVideo.width=560; 
} 

function makeSmall()
{ 
    myVideo.width=320; 
} 

function makeNormal()
{ 
    myVideo.width=420; 
} 

function stop(){
    myVideo.pause();
    myVideo.currentTime = 0;
}
</script>