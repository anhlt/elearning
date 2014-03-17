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
    	<?php echo $this->Form->input('username', array(  
			'placeholder' => 'Email',  
			'style' => 'width:180px;',
			'label' => 'Username',
		)); ?>  
	</div>
	<div class="form-group">
	  <?php echo $this->Form->input('password', array(  
		'placeholder' => 'Password',  
		'style' => 'width:180px;',
		'label' => 'Password'
	  )); ?>  
	</div>	
	<div class="form-group">
	  <?php echo $this->Form->input('full_name', array(  
		'placeholder' => 'Name',  
		'style' => 'width:180px;',
		'label' => 'Name'
	  )); ?>  
	</div>	
	<div class="form-group">
	  <?php echo $this->Form->input('credit_card_number', array(  
		'placeholder' => 'Creadit Card Number',  
		'style' => 'width:180px;',
		'label' => 'Creadit Card Number'
	  )); ?>  
	</div>

	<div class="form-group">
	  <?php echo $this->Form->input('Lecturer.email', array(  
		'placeholder' => 'Email',  
		'style' => 'width:180px;',
		'label' => 'Email'
	  )); ?>  
	</div>
	<div class="form-group">
		<?php echo $this->Form->input('Lecturer.date_of_birth', array(  
		'placeholder' => 'Birthday',  
		'style' => 'width:100px;',
		'label' => 'Birthday',
		'class' => 'inline'
		)); ?>  
	</div>	
	<div class="form-group">
		<?php echo $this->Form->input('Lecturer.address', array(  
		'placeholder' => 'Address',  
		'style' => 'width:180px;',
		'label' => 'Address',
		)); ?>  
	</div>	
	<div class="form-group">
		<?php echo $this->Form->input('Lecturer.phone_number', array(  
		'placeholder' => 'Phone',  
		'style' => 'width:180px;',
		'label' => 'Phone',
		)); ?>  
	</div>
	<div class="form-group">
		<?php echo $this->Form->input('Lecturer.question_verifycode_id', array(    
		'style' => 'width:180px;',
		'label' => 'Question',
		'options' => $droplist,
		)); ?>  
	</div>
	<div class="form-group">
	  <?php echo $this->Form->input('Lecturer.current_verifycode', array(  
		'placeholder' => 'Answer',  
		'style' => 'width:180px;',
		'label' => 'Answer'
	  )); ?>  
	</div>
	<?php echo $this->Form->submit('Sign up', array(  
	'div' => false,  
	'class' => 'btn btn-default'  
	)); ?>  

	<?php echo $this->Form->end(); ?>  
	  
	</div>  
</div>  
