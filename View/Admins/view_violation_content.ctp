<div class="row">

 <?php echo $this->element('menu');?>    
<div class="col-xs-13 col-md-9">
<h2>ドキュメント管理</h2>    
    	<?php echo $this->Session->flash(); ?>

		<div class="well">
			
			<table class="table table-striped">
				<tr>					
					 <td  class="col-sm-3">Student_id</td>
                                        <td  class="col-sm-3">違犯内容</td>
                                        <td  class="col-sm-3">時間</td>
				</tr>
			 <?php foreach ($result as $res) {?>
			  <tr>
			  	
			  	<td><?php echo($res['Violate']['student_id']) ?> </td>
                                <td><?php echo($res['Violate']['content']) ?> </td>
			  	<td><?php echo($res['Violate']['time']) ?> </td>

                               
			  </tr>
			 <?php }?>
			</table>
		</div>
	</div>
</div> 


<?php                             

echo $this->Form->create('Admin', array(
    'inputDefaults' => array(
        'div' => false,
        'label' => false,
        'wrapInput' => false,
        'class' => 'form-control'
    ),
    'class' => 'well',
    'url' => array('controller' => 'Admins', 'action' => 'view_violation_content_process','id' => $document_id
       )
));

echo $this->Form->submit('確認', array(
    'div' => false,
    'class' => 'btn btn-default',
    'name' => 'accept'
));
echo $this->Form->submit('削除', array(
    'div' => false,
    'class' => 'btn btn-default',
    'name' => 'checkout'
));
echo $this->Form->end();

?>
