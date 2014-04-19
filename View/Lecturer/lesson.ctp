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
				'placeholder' => 'クラスコード',  
				'style' => 'width:300px;',
				'label' => 'クラスコード',
				'type'  => 'hidden',
			)); ?>  
		</div>

		<div class="form-group">
			<?php echo $this->Form->input('name', array(  
				'placeholder' => 'クラス名',  
				'style' => 'width:300px;',
				'label' => 'クラス名',
			)); ?>  
		</div>

		<div class="form-group">
			<?php echo $this->Form->text('summary', array(  
				'placeholder' => '説明',  
				'style' => 'width:300px;',
				'label' => '説明',
			)); ?>  
		</div>

		<div class="form-group">
			<?php echo $this->Form->text('Tag.name', array(  
				'placeholder' => 'タグ',  
				'style' => 'width:300px;',
				'label' => 'タグ',
				'class' => 'tm-input'
			)); ?>
		</div>
		<?php echo $this->Form->submit('追加', array(  
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
