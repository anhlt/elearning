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
	<li><?php echo $this->html->link('コメント', array('controller'=>'lesson', 'action'=>'lesson_comment', 'id'=>$id));?></li>	
	</ul>
	</div>
	<div class="col-xs-13 col-md-9">
		<div class="well">
			<div class='form-group'>
				<b><?php echo $this->Html->link("授業管理", array('controller' => 'lecturer', 'action' => 'manage'),
					array('class' => 'btn btn-info')); ?></b>	  
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
					<td  class="col-sm-1">順番</td>
					<td  class="col-sm-5"><?php echo $this->Paginator->sort('title','名前'); ?></td>	
					<td  align="left" class="col-sm-2">禁止</td>				
					<td  align="center" class="col-sm-1">更新</td>					
					<td  align="center" class="col-sm-1">見る</td>
					<td  align="center" class="col-sm-1">削除</td>										
				</tr>
				 <?php foreach ($results as $result) {?>
				  <tr>
				  	<td><b><?php if($index < 9) echo '0'; echo ++$index ?><b></td>
				  	<td><?php echo($result['Document']['title']) ?></td>
				  	<td align="left">
				  		<b>
				  			<?php
				  				if($result['Document']['baned'] == 0)
				  					$msg = "<font color='#156AEB'/>普通</font>";
				  				elseif($result['Document']['baned'] == 1)
				  					$msg = "<font color='red'>禁止された</font>";
				  				else
				  					$msg = "<font color='#FFB300'>修正された</font>";
				  				echo $msg;

				  			?>
				  		<b>
				  	</td>				  					  	
				  	<td align="center">
				  		<?php 
				  			$i = ($result['Document']['baned'] == 1) ? "true" : "false";
				  			echo $this->Html->link("", array('controller' => 'document', 'action' => 'edit', 'id' => $result['Document']['id'], 'ihan' => $i), array('class' => 'glyphicon glyphicon-edit')); ?>
				  	</td>				  	
				  	<td align="center">
				  		<?php echo $this->Html->link("", array('controller' => 'document', 'action' => 'show', $result['Document']['id']), array('class' => 'glyphicon glyphicon-folder-open')); ?>
			  		</td>		  	
				  	<td align="center">
				  		<?php echo $this->Html->link("", array('controller' => 'document', 'action' => 'delete', "id"=>$result['Document']['id']), array('class' => 'glyphicon glyphicon-trash')); ?>
				  	</td>				  				  				  				  	
				  	</tr>
				 <?php }?>
			</table>				
				<span style='margin: 10px 20px 20px 570px;'> ドキュメントを追加する</span>
				<?php echo $this->html->link('追加', array('controller' => 'document', 'action' => 'add', 'id' => $id),
					array('class' => 'btn btn-primary'));?>					
		</div>		
	</div>	
</div>
