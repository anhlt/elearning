<div style="margin-top: 10px;">
	<?php echo $this->Session->flash(); ?>

	<div class="col-xs-5 col-md-3">
	<ul class="nav nav-pills nav-stacked" id="myTab">
	<li class='active'>
		<?php echo $this->html->link('ファイル情報', array('controller'=>'lesson', 'action'=>'doc', 'id'=>$id));?> 
	</li>
	<li><?php echo $this->html->link('テスト情報', array('controller'=>'lesson', 'action'=>'test', 'id'=>$id));?></li>
	<li><?php echo $this->html->link('課金情報', array('controller'=>'lesson', 'action'=>'bill', 'id'=>$id));?></li>
	<li><?php echo $this->html->link('学生リスト', array('controller'=>'lesson', 'action'=>'student', 'id'=>$id));?></li>
	<li><?php echo $this->html->link('サマリー情報', array('controller'=>'lesson', 'action'=>'summary', 'id' => $id));?></li>
	<li><?php echo $this->html->link('レポート', array('controller'=>'lesson', 'action'=>'report', 'id'=>$id));?>	</li>	
	</ul>
	</div>
	<div class="col-xs-13 col-md-9">
		<div class="well">
			<div class='form-group'>
				<b><?php echo $this->Html->link("授業管理", array('controller' => 'lecturer', 'action' => 'manage')); ?></b>	  
			</div>
			<div style='text-align: center; margin-bottom: 20px;'>
				<h4><b>ドキュメント情報</b></h4>
			</div>

			<?php 
				echo $this->Paginator->pagination(array('ul' => 'pagination'));
				$index = 0;
			?>
			
			<table class="table">
				<tr>
					<td  class="col-sm-1">ID</td>
					<td  class="col-sm-3"><?php echo $this->Paginator->sort('Title','名前'); ?></td>					
					<td  class="col-sm-1">更新</td>					
					<td  class="col-sm-1">削除</td>
					<td  class="col-sm-1">見る</td>		
				</tr>
				 <?php foreach ($results as $result) {?>
				  <tr>
				  	<td><?php echo ++$index ?> </td>
				  	<td><?php echo($result['Document']['title']) ?> </td>				  					  	
				  	<td><?php echo $this->Html->image("edit.png", array("alt" => "edit",'url' => array('controller' => 'document', 'action' => 'edit', 'id' => $id, "document_id"=>$result['Document']['id'], 'ihan' => 'false'))); ?>
				  	</td>		  	
				  	<td><?php echo $this->Html->image("trash.png", array("alt" => "delete",'url' => array('controller' => 'document', 'action' => 'delete', "id"=>$result['Document']['id']))); ?>
				  	</td>
				  	<td><?php echo $this->Html->link("", array('controller' => 'document', 'action' => 'show',$result['Document']['id']), array('class' => 'glyphicon glyphicon-folder-open')); ?>
			  	</td>
				  	</tr>
				 <?php }?>
			</table>			
		</div>		
	</div>

	<div style='float: right; margin-right: 15px;'>
		<div class="form-group">
			<span style='margin-right: 20px;'> ドキュメントを追加する</span>
			<?php echo $this->html->link('追加', array('controller' => 'document', 'action' => 'add', 'id' => $id),
				array('class' => 'btn btn-primary'));?>		
		</div>	
	</div>
</div>
