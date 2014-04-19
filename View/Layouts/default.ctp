<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>
        <?php echo __('CakePHP: the rapid development php framework:'); ?>
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
$(document).ready(function(){
    $("#searchip").keypress(function(e){
        if (e.which==13){
            tag_value = $("#searchip").val();
            window.location = "/students/search?search_value="+tag_value; 
        }
    });
}); 
</script>
    <style>
    body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
    }
    </style>
    <?php echo $this->Html->css('bootstrap-responsive.min'); ?>
    <?php echo $this->Html->css('docs.min'); ?>
    <?php echo $this->Html->css('tagmanager'); ?>	

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

	<?php
	echo $this->fetch('meta');
	echo $this->fetch('css');
	?>
</head>
<body>
    <div class="navbar navbar-default navbar-fixed-top">	
    <div class="container">
    <div class="navbar-header">
    <a class="navbar-brand" href=<?php echo $this->webroot; ?>>Home</a>
    <ul class="nav navbar-nav">
        <?php if(AuthComponent::user('role')=='lecturer'):?>
            <li><a href='/lecturer/'>Lecturer</a></li>
        <?php elseif (AuthComponent::user('role')=='student'): ?>
            <li><a href='/students/'>Student</a></li>
        <?php elseif (AuthComponent::user('role')=='admin'): ?>
            <li><a href='/Admins/'>Admins</a></li>
        <?php endif?>
    </ul>
</div>

    <div class="navbar-collapse collapse" id="navbar-main">
    <ul class="nav navbar-nav navbar-right">
        <?php if ($this->Session->read('Auth.User')):?>
                <?php if (AuthComponent::user('role')=='student'):?>
                <li class="navbar-form" role="search">
               <!--      <div class="input-group">
                        <input type="text" class="form-control" placeholder="smart search" name="srch-term" id = 'searchip'>
                        <div class="input-group-btn">
                            <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                        </div>
                    </div> -->
                </li>
                <?php endif ?>
            <li><a href='/users/changepassword'>Change Password</a></li>
            <li><a href='/users/logout'>Logout</a></li>
        <?php else:?>
            <li class='dropdown'>
                <a href='#' class='dropdown-toggle' data-toggle='dropdown'>Sign Up <b class='caret'></b></a>
                <ul class='dropdown-menu'>
                <li><a href='/students/register'>Student</a></li>
                <li><a href='/lecturer/add'>Teacher</a></li>
                </ul>
            </li>
            <li class='dropdown'>
                <a href='#' class='dropdown-toggle' data-toggle='dropdown'>Login <b class='caret'></b></a>
                <ul class='dropdown-menu'>
                <li><a href='/admins/login'>Login as admin</a></li>
                <li><a href='/users/login'>Login as user</a></li>
                </ul>
            </li>
        <?php endif ?>
    </ul>
    </div><!--/.nav-collapse -->
    </div>
    </div>
    <div class="container">
    <?php echo $this->fetch('content'); ?>
    <?php echo $this->element('sql_dump');?>
    </div> <!-- /container -->
    <!-- Le javascript
    ================================================== -->
	<?php echo $this->Html->script('bootstrap.min'); ?>
	<?php echo $this->Html->script('tagmanager'); ?>
	<?php echo $this->Js->writeBuffer(); ?>
	<?php echo $this->Html->script('jquery.smooth-scroll.js'); ?>
</body>
</html>

