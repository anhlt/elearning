<div class="row">
<?php echo $this->element('admin_menus');?>
<div class="col-xs-13 col-md-9"> 
<div class="row">
    <h2>学生の情報の更新</h2>    
	<?php echo $this->Session->flash(); ?>		
	<div class="col-xs-13 col-md-9">
		<?php echo $this->Form->create('Student', array(  
		  'inputDefaults' => array(  
			'div' => false,  
			'label' => false,  
			'wrapInput' => false,  
			'class' => 'form-control'
		  ),  
		  'class' => 'well'  
		)); ?>
            	<div class="form-group">
		  <?php echo $this->Form->input('Student.full_name', array(  
			'placeholder' => '氏名',  
			'style' => 'width:180px;',
			'label' => '氏名'
		  )); ?>  
		</div>	
		<div class="form-group">
		  <?php echo $this->Form->input('Student.email', array(  
			'placeholder' => 'メール',  
			'style' => 'width:180px;',
			'label' => 'メール'
		  )); ?>  
		</div>
		<div class="form-group">
		  <?php echo $this->Form->input('Student.credit_card_number', array(  
			'placeholder' => '銀行口座情報',  
			'style' => 'width:180px;',
			'label' => '銀行口座情報'
		  )); ?>  
		</div>
		<div class="form-group">
			<?php echo $this->Form->input('Student.date_of_birth', array(  
			'placeholder' => '生年月日',  
			'style' => 'width:100px;',
			'label' => '生年月日',
			'class' => 'inline'
			)); ?>  
		</div>	
		<div class="form-group">
			<?php echo $this->Form->input('Student.address', array(  
			'placeholder' => 'アドレス',  
			'style' => 'width:180px;',
			'label' => 'アドレス',
			)); ?>  
		</div>	
		<div class="form-group">
			<?php echo $this->Form->input('Student.phone_number', array(  
			'placeholder' => '電話番号',  
			'style' => 'width:180px;',
			'label' => '電話番号',
			)); ?>  
		</div>
		<div class="form-group">
			<?php echo $this->Form->input('Student.question_verifycode_id', array(    
			'style' => 'width:180px;',
			'label' => '質問',
			'options' => $droplist,
			)); ?>  
		</div>
		<div class="form-group">
		  <?php echo $this->Form->input('Student.current_verifycode', array(  
			'placeholder' => '答え',  
			'style' => 'width:180px;',
			'label' => '答え'
		  )); ?>  
		</div>
		<?php echo $this->Form->submit('セーブ', array(  
		'div' => false,  
		'class' => 'btn btn-default'  
		)); ?>  
		<?php echo $this->Form->end(); ?>  
		</div>
</div>    
</div>
</div>