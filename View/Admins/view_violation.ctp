<div class="row">

 <?php echo $this->element('admin_menus');?>    
<div class="col-xs-13 col-md-9">
<h2>ドキュメント管理</h2>    
    	<?php echo $this->Session->flash(); ?>

		<div class="well">
			<?php echo $this->Paginator->pagination(array(
				'ul' => 'pagination'
				)); ?>
			<table class="table table-striped">
				<tr>
					<td  class="col-sm-1"><?php echo $this->Paginator->sort('student_id'); ?></td>
					<td  class="col-sm-1"><?php echo $this->Paginator->sort('document_id');?></td>
                                        <td  class="col-sm-1"><?php echo $this->Paginator->sort('time');?></td>
					<td  class="col-sm-3">見る</td>
				</tr>
			 <?php foreach ($res as $result) {?>
			  <tr>
			  	<td><?php echo($result['Violate']['student_id']) ?> </td>
			  	<td><?php echo($result['Violate']['document_id']) ?> </td>
                                <td><?php echo($result['Violate']['time']) ?> </td>
                                <td><?php echo $this->html->link('見る',array('controller'=>"admins", 'action'=>"view_violation_content"
                                    ,$result['Violate']["id"]));?></td>
			  </tr>
			 <?php }?>
			</table>
		</div>
	</div>
</div> 