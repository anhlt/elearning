<div class="row">

		 
	<div class="col-xs-5 col-md-3">
		<ul class="nav nav-pills nav-stacked" id="myTab">
			<li ><a href="/lecturer/">Class Manager</a></li>
			<li><a href="/lecturer/lesson">New Class</a></li>
			<li class="active"><a href="/lecturer/edit">Edit Info</a></li>
		</ul>
	</div>
	<?php echo $this->Session->flash(); ?>		
	<div class="col-xs-13 col-md-9">
		<?php echo $this->Form->create('Lecturer', array(  
		  'inputDefaults' => array(  
			'div' => false,  
			'label' => false,  
			'wrapInput' => false,  
			'class' => 'form-control'
		  ),  
		  'class' => 'well'  
		)); ?>
		<div class="form-group">
		  <?php echo $this->Form->input('Lecturer.id', array(  
			'placeholder' => 'Email',  
			'style' => 'width:180px;',
			'label' => 'Email'
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
		  <?php echo $this->Form->input('Lecturer.full_name', array(  
			'placeholder' => 'Name',  
			'style' => 'width:180px;',
			'label' => 'Name'
		  )); ?>  
		</div>	
		<div class="form-group">
		  <?php echo $this->Form->input('Lecturer.credit_card_number', array(  
			'placeholder' => 'Creadit Card Number',  
			'style' => 'width:180px;',
			'label' => 'Creadit Card Number'
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
		<?php echo $this->Form->submit('save', array(  
		'div' => false,  
		'class' => 'btn btn-default'  
		)); ?>  
		<?php echo $this->Form->end(); ?>  
		</div>
</div>  