<div class="row">
<?php echo $this->Session->flash(); ?>

<?php echo $this->element('admin_menus');?>
<div class="col-xs-13 col-md-9">

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
</div>