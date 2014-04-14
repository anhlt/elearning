<div class="row">
	<?php echo $this->Session->flash(); ?>
	<div class="col-xs-5 col-md-3">
		<ul class="nav nav-pills nav-stacked" id="myTab">
			<li><a href="/lecturer/">Class Manager</a></li>
			<li class="active"><a href="/lecturer/lesson">New Class</a></li>
			<li><a href="/lecturer/edit">Edit Info</a></li>
		</ul>
	</div>
	<div class="col-xs-13 col-md-9">
		<?php echo $this->Form->create('Lesson',array(
			'inputDefaults' => array(  
				'div' => false,  
				'label' => false,  
				'wrapInput' => false,  
				'class' => 'form-control'  
				),  
			'class' => 'well',
		    'url' => array('controller' => 'Lesson', 'action' => 'add')
			)); ?>

		<div class="form-group">
			<?php echo $this->Form->input('id', array(  
				'placeholder' => 'Class name',  
				'style' => 'width:300px;',
				'label' => 'Class name',
				'type'  => 'hidden',
			)); ?>  
		</div>

		<div class="form-group">
			<?php echo $this->Form->input('name', array(  
				'placeholder' => 'Class name',  
				'style' => 'width:300px;',
				'label' => 'Class name',
			)); ?>  
		</div>

		<div class="form-group">
			<?php echo $this->Form->text('summary', array(  
				'placeholder' => 'Description',  
				'style' => 'width:300px;',
				'label' => 'Description',
			)); ?>  
		</div>

		<div class="form-group">
			<?php echo $this->Form->text('Tag.name', array(  
				'placeholder' => 'Tags',  
				'style' => 'width:300px;',
				'label' => 'Tags',
				'class' => 'tm-input'
			)); ?>
		</div>
		<?php echo $this->Form->submit('Add Class', array(  
		'div' => false,  
		'class' => 'btn btn-default'  
		)); ?>  


		<?php echo $this->Form->end(); ?>  
	</div>

	<script type="text/javascript">    
	window.onload = function WindowLoad(event) {
		jQuery(".tm-input").tagsManager();
		console.debug("aa");
	}
	</script>
</div>
