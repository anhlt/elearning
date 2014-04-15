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
					<td  class="col-sm-1"><?php echo $this->Paginator->sort('id'); ?></td>
					<td  class="col-sm-1"><?php echo $this->Paginator->sort('Name');?></td>
					<td  class="col-sm-3">Delete</td>
				</tr>
			 <?php foreach ($res as $result) {?>
			  <tr>
			  	<td><?php echo($result['User']['id']) ?> </td>
			  	<td><?php echo($result['User']['username']) ?> </td>
                                <td><?php echo $this->html->link('delete',array('controller'=>"admins", 'action'=>"remove_admin_process"
                                    , $result['User']["id"]));?></td>
			  </tr>
			 <?php }?>
			</table>
		</div>
	</div>
