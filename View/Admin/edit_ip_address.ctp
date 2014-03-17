<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$old_ip_address =  $_GET['ip_address'];
//echo $old_ip_address;
?>
<div class="row">
 
<div class="col-md-8 col-md-offset-2">  
<?php echo $this->Form->create('edit',array(  
  'inputDefaults' => array(  
    'div' => false,  
    'label' => false,  
    'wrapInput' => false,  
    'class' => 'form-control',  
  ),  
  'class' => 'well form-inline',  
)); ?>  
    <h2>IPアドレスの更新</h2>   
  <table>  
      <tr>
          <td><h4>古いIPアドレス</h4></td>
          <td><h4><?php echo $this->Form->input('old_ip_address', array(
              'readonly'=>'readonly',
              'style' => 'width:180px;', 'value'=>$old_ip_address)); ?></h4></td>
      </tr>

      <tr>
          <td><h4>新しいIPアドレス</h4></td>
          <td>  <?php echo $this->Form->input('new_ip_address', array('style' => 'width:180px;')); ?></td>
      </tr>
      <tr>
          <td></td>
          <td><?php echo $this->Form->submit('更新', array('div' => false,  'class' => 'btn btn-default')); ?> </td>
      </tr>

  </table>    
<?php echo $this->Form->end(); ?>
<?php echo $this->Session->flash(); ?>    
</div>    
</div>  
