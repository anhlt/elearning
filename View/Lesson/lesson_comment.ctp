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
		<?php echo $this->html->link('コメント', array('controller'=>'lesson', 'action'=>'lesson_comment', 'id'=>$id));?>
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
				<p>返事</p>			
				<?php
					if(count($results) > 0) {
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