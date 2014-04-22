<div style="margin-top: 10px;">
	<?php echo $this->Session->flash(); ?>

	<div class="col-xs-5 col-md-3">
	<ul class="nav nav-pills nav-stacked" id="myTab">
	<li><?php echo $this->html->link('ファイル情報', array('controller'=>'lesson', 'action'=>'doc', 'id' => $id));?></li>
	<li class="active">
		<?php echo $this->html->link('テスト情報', array('controller'=>'lesson', 'action'=>'test', 'id'=>$id));?></li>
	<li><?php echo $this->html->link('課金情報', array('controller'=>'lesson', 'action'=>'bill', 'id'=>$id));?></li>
	<li><?php echo $this->html->link('学生リスト', array('controller'=>'lesson', 'action'=>'student',	'id'=>$id));?></li>
	<li><?php echo $this->html->link('サマリー情報', array('controller'=>'lesson', 'action'=>'summary', 'id'=>$id));?>	</li>
	<li><?php echo $this->html->link('レポート', array('controller'=>'lesson', 'action'=>'report', 'id'=>$id));?></li>		
	</ul>
	</div>
	<div class="col-xs-13 col-md-9">
		<div class="well">
			<div class='form-group'>
				<b><?php echo $this->Html->link("授業管理", array('controller' => 'lecturer', 'action' => 'manage'),
					array('class' => 'btn btn-info')); ?></b>	  
			</div>
			<div style='text-align: center; margin-bottom: 20px;'>
				<h4><b>テスト情報</b></h4>
			</div>

			<?php 
				echo $this->Paginator->pagination(array('ul' => 'pagination')); 
				$index = 0;
			?>
			<div class='table-responsive'>
			<table class="table">
				<tr>
					<td  class="col-sm-1">順番</td>	
					<td  class="col-sm-2"><?php echo $this->Paginator->sort('Title','名前'); ?></td>					
					<td  class="col-sm-1"><?php echo $this->Paginator->sort('Time','時間'); ?></td>
					<td  class="col-sm-1">更新</td>
					<td  class="col-sm-1">削除</td>
					<td  class="col-sm-1">見る</td>
					<td  class="col-sm-1">結果</td>	
				</tr>
			 <?php foreach ($results as $result) {?>
			  <tr>
			  	<td><?php if($index < 9) echo '0'; echo ++$index; ?> </td>
			  	<td><?php echo($result['Test']['title']) ?></td>			  	
			  	<td><?php echo($result['Test']['test_time']) ?> </td>
			  	<td>
			  		<?php echo $this->Html->link("", array('controller' => 'tests', 'action' => 'edit', "id"=>$result['Test']['id']), array('class' => 'glyphicon glyphicon-edit')); ?>
			  	</td>
			  	<td>
			  		<?php echo $this->Html->link("", array('controller' => 'tests', 'action' => 'delete', "id"=>$result['Test']['id']), array('class' => 'glyphicon glyphicon-remove-sign')); ?>
			  	</td>
			  	<td>
			  		<?php echo $this->Html->link("", array('controller' => 'tests', 'action' => 'show', $result['Test']['id']), array('class' => 'glyphicon glyphicon-folder-open')); ?>
			  	</td>
			  	<td><?php echo $this->Html->link("", array('controller' => 'tests', 'action' => 'list_result',$result['Test']['id']), array('class' => 'glyphicon glyphicon-list-alt')); ?>
			  	</td>
			  </tr>
			 <?php }?>
			</table>
			</div>			
				<span style='margin: 10px 20px 10px 600px;'>テストを追加する</span>
				<?php echo $this->html->link('追加', array('controller' => 'tests', 'action' => 'add', 'id' => $id),
					array('class' => 'btn btn-primary'));?>				
			</div>
		</div>
	</div>	
</div>