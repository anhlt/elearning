<div class="row">
	<?php echo $this->Session->flash(); ?>
	<div class="col-md-8 col-md-offset-2"> 
		<?php echo $this->Form->create('User', array(  
		  'inputDefaults' => array(  
			'div' => false,  
			'label' => false,  
			'wrapInput' => false,  
			'class' => 'form-control'  
		  ),  
		  'class' => 'well'  
		)); ?> 
		<div class="form-group">
	    	<?php echo $this->Form->input('id'); ?>  
		</div>

		<div class="form-group">
		  <?php echo $this->Form->input('current_password', array(  
			'placeholder' => '現在のパスワード',  
			'style' => 'width:180px;',
			'label' => '現在のパスワード',
			'type'  => 'password'
		  )); ?>
		</div>

		<div class="form-group">
		  <?php echo $this->Form->input('password', array(  
			'placeholder' => '新しいパスワード',  
			'style' => 'width:180px;',
			'label' => '新しいパスワード',
			'value' => ''
		  )); ?>
		</div>		
		<div class="form-group">
		  <?php echo $this->Form->input('password_retype', array(  
			'placeholder' => '再ド新しいパスワード',  
			'style' => 'width:180px;',
			'label' => '再ド新しいパスワード',
			'value' => '',
			'type'  => 'password'

		  )); ?>
		</div>

		<?php echo $this->Form->submit('更新', array(  
			'div' => false,  
			'class' => 'btn btn-default'  
		)); ?>  

		<?php echo $this->Form->end(); ?>  
	  
	</div>
</div>