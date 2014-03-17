
<div class="col-xs-5 col-md-3">
		<ul class="nav nav-pills nav-stacked" id="myTab">
			<li class="active">
                            <?php echo $this->html->link('管理者を追加',array('controller'=>"admins", 'action'=>"add_admin"
                                   ));?></li>
			　<li >　<?php echo $this->html->link('管理者を削除',array('controller'=>"admins", 'action'=>"remove_admin"
                                   ));?></li>
		</ul>
	</div>

<div class="col-xs-13 col-md-9">
    	<?php echo $this->Session->flash(); ?>

		<div class="well">
			<?php echo $this->Paginator->pagination(array(
				'ul' => 'pagination'
				)); ?>
			<table class="table table-condensed">
				<tr>
					<td  class="col-sm-1"><?php echo $this->Paginator->sort('student_id'); ?></td>
					<td  class="col-sm-1"><?php echo $this->Paginator->sort('document_id');?></td>
                                        <td  class="col-sm-1"><?php echo $this->Paginator->sort('time');?></td>
					<td  class="col-sm-3">See</td>
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