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
  'class' => 'well form-inline',  
)); ?>  
  <?php echo $this->Form->input('User.username', array(  
    'placeholder' => 'ユーザ名',  
    'style' => 'width:180px;'
  )); ?>  
  <?php echo $this->Form->input('User.password', array(  
    'placeholder' => 'パスワード',  
    'style' => 'width:180px;' 
  )); ?> 
  <?php echo $this->Form->submit('ロクイン', array(  
    'div' => false,  
    'class' => 'btn btn-default'  
  )); ?>  
<?php echo $this->Form->end(); ?>   
</div>    
</div>  
