<div style="margin-top: 10px;">
	<?php echo $this->Session->flash(); ?>

	<div class="col-xs-5 col-md-3">
	<ul class="nav nav-pills nav-stacked" id="myTab">
	<li><?php echo $this->html->link('ファイル情報', array('controller'=>'lesson', 'action'=>'doc', 'id'=>$id));?></li>
	<li><?php echo $this->html->link('テスト情報', array('controller'=>'lesson', 'action'=>'test', 'id'=>$id));?></li>
	<li><?php echo $this->html->link('課金情報', array('controller'=>'lesson', 'action'=>'bill', 'id'=>$id));?></li>
	<li><?php echo $this->html->link('学生リスト', array('controller'=>'lesson', 'action'=>'student', 'id'=>$id));?></li>
	<li><?php echo $this->html->link('サマリー情報', array('controller'=>'lesson', 'action'=>'summary', 'id' => $id));?></li>
	<li class="active">
		<?php echo $this->html->link('レポート', array('controller'=>'lesson', 'action'=>'report', 'id'=>$id));?>	
	</li>	
	</ul>
	</div>
	<div class="col-xs-13 col-md-9">		
		<div class="well">
			<div class='form-group'>
				<b><?php echo $this->Html->link("授業管理", array('controller' => 'lecturer', 'action' => 'manage'),
					array('class' => 'btn btn-info')); ?></b>	  
			</div>
			<div style='text-align: center; margin-bottom: 20px;'>
				<h4><b>レポート</b></h4>
			</div>				
			<div style='width: 600px; margin: auto;'>
				<?php
					$index = 0;
					if(count($reports) > 0) {
						print '<b>違反ドキュメント</b>';
				?>
				<div style='margin: 10px 0px 10px 0px;'>
				<table class="table table-bordered">
				<tr>
					<td  class="col-sm-2">順番</td>
					<td  class="col-sm-4">名前</td>					
					<td  class="col-sm-2">編集</td>		
				</tr>
				 <?php foreach ($reports as $report) {?>
					<tr>
					  	<td><?php if($index < 9) echo '0'; echo ++$index ?> </td>
					  	<td><?php echo($report['Document']['title']) ?> </td>				  					  	
					  	<td>
					  		<?php echo $this->Html->link("", array('controller' => 'document', 'action' => 'edit', 'id' => $id, 
					  			"document_id"=>$report['Document']['id'], 'ihan' => 'true'), array('class' => 
					  			'glyphicon glyphicon-edit')); ?>
					  	</td>		  	
					</tr>
				 <?php }?>
				</table>
				</div>
				<?php }?>

				<?php
					$index = 0;
					if(count($bans) >= 0) {
						print '<b>禁止ドキュメント</b>';					
				?>
				<table class="table table-bordered">
				<tr>
					<td  class="col-sm-2">順番</td>
					<td  class="col-sm-4">名前</td>					
					<td  class="col-sm-2">編集</td>		
				</tr>
				 <?php foreach ($bans as $ban) {?>
					<tr>
					  	<td><?php if($index < 9) echo '0'; echo ++$index ?> </td>
					  	<td><?php echo($ban['Document']['title']) ?> </td>				  					  	
					  	<td>
					  		<?php echo $this->Html->link("", array('controller' => 'document', 'action' => 'edit', 'id' => $id, 
					  			"document_id"=>$ban['Document']['id'], 'ihan' => 'true'), array('class' => 
					  			'glyphicon glyphicon-edit')); ?>
					  	</td>		  	
					</tr>
				 <?php }?>
				</table>
				<?php }?>		
			
				
				<p>返事</p>			
				<?php
					if(count($results) >= 0) {
						echo "<div style='width: 600px; background: #FFFFFF; border : 1px solid #94E5FF; border-radius: 4px; padding: 10px;'>";
						foreach ($results as $result) {
							echo '<i><font color="#3276B1">'.$result['Comment']['full_name'].':   '.'</font></i>';
							echo '<span>'.$result['Comment']['content'].'</span>';
							echo '</br>';
						}
						echo "</div>";
					}
				?>				
				
				<br><br>
				<?php echo $this->Form->create('Report',array(
					'inputDefaults' => array(  
						'div' => false,  
						'label' => false,  
						'wrapInput' => false,  
						'class' => 'form-control'  
						),
				    'url' => array('controller' => 'lecturer', 'action' => 'reply', 'id' => $id),
				    'method' => 'post',
				    'enctype' => 'multipart/form-data'
					)); ?>		
						
						<?php echo $this->Form->input('content', array(  
							'placeholder' => '返事の内容',							
							'required' => true,
							'style' => 'width: 400px; float: left;'						
						)); ?>						
						
						<?php echo $this->Form->submit('返事', array(
						'class' => 'btn btn-primary',
						'div' => false,
						'style' => 'margin-left: 45px; float: left;',
						)); ?>
						
						<?php echo $this->Form->reset('キャンセル',array(
								'class' => 'btn btn-primary',
								'div' => false, 
								'value' => 'キャンセル',
								'style' => 'margin-left: 10px;',
							));?>						
						 						
					
				<?php echo '</form>';?>		
		</div>
	</div>
</div>