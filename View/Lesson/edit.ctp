<div class="col-xs-13 col-md-9">
		<?php echo $this->Form->create('Lesson',array(
			'inputDefaults' => array(  
				'div' => false,  
				'label' => false,  
				'wrapInput' => false,  
				'class' => 'form-control'  
				),	
			'class' => 'well'
			)); ?>
		<div class="form-group">
			<?php echo $this->Form->input('id', array(  
				'placeholder' => 'ID',  
				'style' => 'width:300px;',
				'label' => 'ID',
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
		<?php echo $this->Form->submit('Save', array(  
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