<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>
        <?php echo $title_for_layout; ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	<?php
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('style');
	    echo $this->Html->script('jquery-1.11.0.js');
	    echo $this->Html->css('bootstrap-responsive.min');
	    echo $this->Html->css('bootstrap-responsive.min');
	    echo $this->Html->css('tagmanager');
    ?>
    <!-- Le styles -->
<script>
var timeSecond = 20;
function timeLogout() {
    if(timeSecond > 0) {
        timeSecond -= 1;
        setTimeout("timeLogout()",1000);
    } else {
        window.location.assign("/users/logout");
    }
}

$(document).ready(function(){
    $("#searchip").keypress(function(e){
        if (e.which==13){
            tag_value = $("#searchip").val();
            window.location = "/students/search?search_value="+tag_value; 
        }
    });

    session_time = $("#session_time").text();
    if (session_time > 0){
        timeSecond = session_time
        timeLogout();
    }
}); 
</script>
    <style>
    body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
    }
    .navbar-form{max-width: 250px;}

    </style>
    <?php echo $this->Html->css('bootstrap-responsive.min'); ?>
    <?php echo $this->Html->css('docs.min'); ?>
    <?php echo $this->Html->css('tagmanager'); ?>	

	<?php
	echo $this->fetch('meta');
	echo $this->fetch('css');
	?>
</head>
<body>
    <div class="navbar navbar-default navbar-fixed-top">	
    <div class="container">
    <div class="navbar-header">
    <a class="navbar-brand" href=<?php echo $this->webroot; ?>>ホーム</a>
    <ul class="nav navbar-nav">
        <?php if(AuthComponent::user('role')=='lecturer'):?>
            <li><a href='/lecturer/'>先生</a></li>
<!--             <li><a href='/lecturer/violate'>Reports <span class="badge alert-danger">42</span></a></li>
 -->        <?php elseif (AuthComponent::user('role')=='student'): ?>
            <li><a href='/students/'>学生</a></li>
        <?php elseif (AuthComponent::user('role')=='admin'): ?>
            <li><a href='/Admins/'>管理者</a></li>
        <?php endif?>
    </ul>
</div>
    <?php if (AuthComponent::user('id')){
        echo "<div id = 'session_time' style='display:none'>".SESSION_TIME."</div>";
    }else {
        echo "<div id = 'session_time' style='display:none'>-1</div>";
    }
    ?>
    <div class="navbar-collapse collapse" id="navbar-main">
    <ul class="nav navbar-nav navbar-right">
        <?php if ($this->Session->read('Auth.User')):?>
                <?php if (AuthComponent::user('role')=='student'):?>
                <li class="navbar-form" role="search">
                    <div class="input-group .col-md-4">
                        <input type="text" class="form-control" placeholder="スマート 検索" name="srch-term" id = 'searchip'>
                        <div class="input-group-btn">
                            <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>   
                        </div>
                    </div>
                </li>
                <?php endif ?>
            <li><a href='/users/changepassword'>パスワードの更新</a></li>
            <li><a href='/users/logout'>ログアウト</a></li>
        <?php else:?>
            <li class='dropdown'>
                <a href='#' class='dropdown-toggle' data-toggle='dropdown'>登録<b class='caret'></b></a>
                <ul class='dropdown-menu'>
                <li><a href='/students/register'>学生</a></li>
                <li><a href='/lecturer/add'>先生</a></li>
                </ul>
            </li>
            <li class='dropdown'>
                <a href='#' class='dropdown-toggle' data-toggle='dropdown'>ロクイン <b class='caret'></b></a>
                <ul class='dropdown-menu'>
                <li><a href='/admins/login'>管理者としてロクイン</a></li>
                <li><a href='/users/login'>ユーザとしてロクイン</a></li>
                </ul>
            </li>
        <?php endif ?>
    </ul>
    </div><!--/.nav-collapse -->
    </div>
    </div>
    <div class="container">
    <?php echo $this->fetch('content'); ?>
    </div> <!-- /container -->
    <!-- Le javascript
    ================================================== -->
	<?php echo $this->Html->script('bootstrap.min'); ?>
	<?php echo $this->Html->script('tagmanager'); ?>
	<?php echo $this->Js->writeBuffer(); ?>
	<?php echo $this->Html->script('jquery.smooth-scroll.js'); ?>
</body>
</html>

