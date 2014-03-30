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

	<!-- Le styles -->
	<?php echo $this->Html->css('bootstrap.min'); ?>
	<?php echo $this->Html->css('style'); ?>
    <?php echo $this->Html->script('jquery-1.11.0.js');?>
    <style>
	body {
		padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
	}
	</style>
	<?php echo $this->Html->css('bootstrap-responsive.min'); ?>
	<?php echo $this->Html->css('tagmanager'); ?>	

	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Le fav and touch icons -->
	<!--
	<link rel="shortcut icon" href="/ico/favicon.ico">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/ico/apple-touch-icon-144-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/ico/apple-touch-icon-114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/ico/apple-touch-icon-72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" href="/ico/apple-touch-icon-57-precomposed.png">
	-->
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
				</div>

        		<div class="navbar-collapse collapse" id="navbar-main">
          	<!--		<ul class="nav navbar-nav">
						<li class="active"><a href="/posts/">Student</a></li>
						<li><a href="/posts/add">Teacher</a></li>
					</ul> -->
					<ul class="nav navbar-nav navbar-right">
						<?php if ($this->Session->read('Auth.User')): ?>
						<li><a href="/users/logout">Logout</a></li>
						<?php  else : ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sign Up <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="/students/register">Student</a></li>
                                <li><a href="/lecturer/add">Teacher</a></li>
                            </ul>
						</li>
						<li><a href="/users/login">Login</a></li>
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
	<!-- Placed at the end of the document so the pages load faster -->
	<?php echo $this->Html->script('bootstrap.min'); ?>
	<?php echo $this->Html->script('tagmanager'); ?>
	<?php echo $this->Js->writeBuffer(); ?>
</body>
</html>
