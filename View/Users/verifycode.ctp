<div class="row">

<?php echo $this->Session->flash(); ?>
<div class="col-md-8 col-md-offset-2">  
<?php echo $this->Form->create(array(  
  'inputDefaults' => array(  
    'div' => false,  
    'label' => false,  
    'wrapInput' => false,  
    'class' => 'form-control',  
  ),  
  'class' => 'well',  
)); ?>  
  <?php echo $this->Form->input('username', array(  
    'placeholder' => 'ユーザ名',  
    'style' => 'width:180px;'
  )); ?>  
  <?php echo $this->Form->input('password', array(  
    'placeholder' => 'パスワード',  
    'style' => 'width:180px;' 
  )); ?> 
  <div class="form-group">
    <?php echo $this->Form->input('Lecturer.question_verifycode_id', array(    
    'style' => 'width:180px;',
    'label' => '質問',
    'options' => $droplist,
    )); ?>  
  </div>
  <div class="form-group">
    <?php echo $this->Form->input('Lecturer.current_verifycode', array(  
    'placeholder' => '答え',  
    'style' => 'width:180px;',
    'label' => '答え'
    )); ?>  
  </div>
  <?php echo $this->Form->submit('ロクイン', array(  
    'div' => false,  
    'class' => 'btn btn-default'  
  )); ?>  
<?php echo $this->Form->end(); ?>   
</div>    
</div>  
