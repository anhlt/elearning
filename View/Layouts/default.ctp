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
	    //echo $this->Html->script('nocopy');

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
</div>

    <div class="navbar-collapse collapse" id="navbar-main">
    <!--		<ul class="nav navbar-nav">
    <li class="active"><a href="/posts/">Student</a></li>
    <li><a href="/posts/add">Teacher</a></li>
    </ul> -->
    <ul class="nav navbar-nav navbar-right">
<?php if ($this->Session->read('Auth.User')){
    //  echo "<div class='input-group'>";
    //  echo "<span class = 'glyphicon-class'>iab</span>";
    if($this->Session->read("Auth.User.role") == "student"){
        echo "<li><a style = 'margin-right:420px'>";
        echo "<input id = 'searchip' class = 'form-control glyphicon-class' placeholder = 'smart search' style='height:25px'/>"; 
        echo "</a></li>";
    }
    echo "<li><a href='/users/logout'>Logout</a></li>";
} else{
    echo "<li class='dropdown'>
        <a href='#' class='dropdown-toggle' data-toggle='dropdown'>Sign Up <b class='caret'></b></a>
        <ul class='dropdown-menu'>
        <li><a href='/students/register'>Student</a></li>
        <li><a href='/lecturer/add'>Teacher</a></li>
        </ul>
        </li>";

    echo "<li><a href='/users/login'>Login</a></li>";
}
?>
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
	<?php echo $this->Html->script('jquery.smooth-scroll.js'); ?>
</body>
</html>

