<div>
	<?php echo $this->Session->flash(); ?>

	<div class="col-xs-5 col-md-3">
		<ul class="nav nav-pills nav-stacked" id="myTab">
			<li><a href="/lesson/detail_doc">ファイル情報</a></li>
			<li><a href="/lesson/detail_test">テスト情報</a></li>
			<li class="active"><a href="/lession/detail_coins">課金情報</a></li>
			<li><a href="/lesson/detail_std">学生リスト</a></li>
			<li><a href="/lesson/summary">サマリー情報</a></li>
			<li><a href="/lesson/report">レポート</a></li>
		</ul>
	</div>
	<div class="col-xs-13 col-md-9">
		<div class="well">
			<?php echo $this->Paginator->pagination(array(
				'ul' => 'pagination'
				)); ?>
			<table class="table table-condensed">
				<tr>
					<td  class="col-sm-1"><?php echo $this->Paginator->sort('id'); ?></td>	
					<td  class="col-sm-1">Link</td>				
					<td  class="col-sm-1">Title</td>
					<td  class="col-sm-1">Delete</td>
					<td  class="col-sm-1">Edit</td>			
				</tr>
			 <?php foreach ($results as $result) {?>
			  <tr>
			  	<td><?php echo($result['Document']['id']) ?> </td>
			  	<td><?php echo($result['Document']['link']) ?> </td>
			  	<td><?php echo($result['Document']['title']) ?> </td>
			  	<td><?php echo $this->Html->image("edit.png", array("alt" => "edit",'url' => array('controller' => 'document', 'action' => 'edit', "id"=>$result['Document']['id']))); ?>
			  	</td>
			  	<td><?php echo $this->Html->image("trash.png", array("alt" => "delete",'url' => array('controller' => 'document', 'action' => 'delete', "id"=>$result['Document']['id']))); ?>
			  	</td>
			  </tr>
			 <?php }?>
			</table>
		</div>
	</div>
</div>
