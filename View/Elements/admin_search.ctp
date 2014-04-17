<div>  
<?php echo $this->Form->create('search',array(  
  'inputDefaults' => array(  
    'div' => false,  
    'label' => false,  
    'wrapInput' => false,  
    'class' => 'form-control',  
  ),  
  'class' => 'well form-inline',  
)); ?>  
  <?php echo $this->Form->input('username', array(  
    'placeholder' => 'ユーザ名',  
    'style' => 'width:180px;'
  )); ?>  
  <?php echo $this->Form->submit('検索', array( 
    'div' => false,  
    'class' => 'btn btn-default'  
  )); ?> 
<?php echo $this->Session->flash(); ?>
<?php echo $this->Form->end(); ?>
</div> 