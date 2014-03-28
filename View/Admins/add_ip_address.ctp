
<div class="row">
 <?php echo $this->element('admin_menus');?>
<div class="col-xs-13 col-md-9">  
<h2>IPアドレスの管理</h2>    
<?php echo $this->Form->create('add',array(  
  'inputDefaults' => array(  
    'div' => false,  
    'label' => false,  
    'wrapInput' => false,  
    'class' => 'form-control',  
  ),  
  'class' => 'well form-inline',  
)); ?>  
  <?php echo $this->Form->input('ip_address', array(  
    'placeholder' => 'IP　アドレス',  
    'style' => 'width:180px;'
  )); ?>  
  <?php echo $this->Form->submit('追加', array( 
    'div' => false,  
    'class' => 'btn btn-default'  
  )); ?> 
<?php echo $this->Session->flash(); ?>
<?php echo $this->Form->end(); ?>
</div>    
  

<div class="col-xs-13 col-md-9"> 
<?php
if($data == NULL) echo '<h2>Data Empty</h2>';
else{
    ?>
<table class="table table-striped">
    <tr>
        <td>順番</td>
        <td>ＩＰアドレス</td>
        <td></td>
        <td></td>
    </tr>
<?php  
$i=1;
foreach($data as $item){
    echo "<tr>";
        echo"<td>".$i++."</td>";
        $ip_address = $item['IpAdmin']['ip_address'];
        echo"<td>".$ip_address."</td>";
        echo"<td><a href ='edit_ip_address?ip_address=$ip_address'>更新</a></td>";
        echo"<td>".$this->html->link('削除',array('controller'=>'admins','action'=>'delete_ip_address',$ip_address))."</td>";
    echo"</tr>";
    }
}
?>            
</table>
</div>
 

</div>