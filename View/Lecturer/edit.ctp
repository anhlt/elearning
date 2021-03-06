<div class="row">

		 
	<div class="col-xs-5 col-md-3">
		<ul class="nav nav-pills nav-stacked" id="myTab">
			<li><a href="/lecturer/">従業管理</a></li>
			<li><a href="/lecturer/lesson">新しい従業</a></li>
			<li><a href="/lecturer/edit">情報を更新</a></li>
			<li><a href="/lecturer/delete">アクアウートを削除</a></li>

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
			'placeholder' => 'コード',  
			'style' => 'width:180px;',
			'label' => 'コード'
		  )); ?>  
		</div>
		<div class="form-group">
		  <?php echo $this->Form->input('Lecturer.email', array(  
			'placeholder' => 'メール',  
			'style' => 'width:180px;',
			'label' => 'メール'
		  )); ?>  
		</div>
		<div class="form-group">
		  <?php echo $this->Form->input('Lecturer.full_name', array(  
			'placeholder' => '氏名',  
			'style' => 'width:180px;',
			'label' => '氏名'
		  )); ?>  
		</div>	
		<div class="form-group">
		  <?php echo $this->Form->input('Lecturer.credit_card_number', array(  
			'placeholder' => '銀行口座情報',  
			'style' => 'width:180px;',
			'label' => '銀行口座情報'
		  )); ?>  
		</div>
		<div class="form-group">
			<?php echo $this->Form->input('Lecturer.date_of_birth', array(  
			'placeholder' => '生年月日',  
			'style' => 'width:100px;',
			'label' => '生年月日',
			'class' => 'inline'
			)); ?>  
		</div>	
		<div class="form-group">
			<?php echo $this->Form->input('Lecturer.address', array(  
			'placeholder' => 'アドレス',  
			'style' => 'width:180px;',
			'label' => 'アドレス',
			)); ?>  
		</div>	
		<div class="form-group">
			<?php echo $this->Form->input('Lecturer.phone_number', array(  
			'placeholder' => '電話番号',  
			'style' => 'width:180px;',
			'label' => '電話番号',
			)); ?>  
		</div>
		<div class="form-group">
			<?php echo $this->Form->input('Lecturer.question_verifycode', array(    
			'style' => 'width:180px;',
			'label' => '質問',
			)); ?>  
		</div>
		<div class="form-group">
		  <?php echo $this->Form->input('Lecturer.current_verifycode', array(  
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