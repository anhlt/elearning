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
			'placeholder' => 'Password',  
			'style' => 'width:180px;',
			'label' => 'Current Password',
			'type'  => 'password'
		  )); ?>
		</div>

		<div class="form-group">
		  <?php echo $this->Form->input('password', array(  
			'placeholder' => 'New Password',  
			'style' => 'width:180px;',
			'label' => 'New Password',
			'value' => ''
		  )); ?>
		</div>		
		<div class="form-group">
		  <?php echo $this->Form->input('password_retype', array(  
			'placeholder' => 'New Password Confirm',  
			'style' => 'width:180px;',
			'label' => 'New Password Confirm',
			'value' => '',
			'type'  => 'password'

		  )); ?>
		</div>

		<?php echo $this->Form->submit('Change', array(  
			'div' => false,  
			'class' => 'btn btn-default'  
		)); ?>  

		<?php echo $this->Form->end(); ?>  
	  
	</div>
</div>