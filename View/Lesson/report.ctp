<div>
	<?php echo $this->Session->flash(); ?>

	<div class="col-xs-5 col-md-3">
		<ul class="nav nav-pills nav-stacked" id="myTab">
			<li>
				<?php echo $this->html->link('ファイル情報', array('controller' => 'lesson', 'action' => 'doc',
					'id' => $id));?> 
			</li>
			<li>
				<?php echo $this->html->link('テスト情報', array('controller' => 'lesson', 'action' => 'test',
					'id' => $id));?> 
			</li>
			<li>
				<?php echo $this->html->link('課金情報', array('controller' => 'lesson', 'action' => 'bill',
					'id' => $id));?> 
			</li>
			<li>
				<?php echo $this->html->link('学生リスト', array('controller' => 'lesson', 'action' => 'student',
					'id' => $id));?> 
			</li>
			<li>
				<?php echo $this->html->link('サマリー情報', array('controller' => 'lesson', 'action' => 'summary',
					'id' => $id));?> 
			</li>
			<li class="active">
				<?php echo $this->html->link('レポート', array('controller' => 'lesson', 'action' => 'report',
					'id' => $id));?> 
			</li>			
		</ul>
	</div>
	<div class="col-xs-13 col-md-9">		
		<div class="well">
			<div class='form-group'>
				<b><?php echo $this->Html->link("授業管理", array('controller' => 'lecturer', 'action' => 'manage')); ?></b>	  
			</div>
			<div style='text-align: center; margin-bottom: 20px;'>
				<h4><b>レポート</b></h4>
			</div>

			<div class='row'>
				<p style='margin-left: 60px;'>				
				<?php 
				if($reports) {
					foreach ($reports as $report) {									
						echo $this->html->link($report['Document']['title'], array('controller' => 'document', 'action' => 'edit',
							'id' => $id, 'document_id' => $report['Document']['id'], 'ihan' => 'true'));
						echo '</br>';
					}
				}					
				else {
					echo '<b>レポートがない</b>';
				}
				?>
				</p>
			</div>
			<br><br> <br>	
			<div class='row' style='margin-left: 43px;'><b>返事</b></div>
			<br>			
			<div style='width: 700px; margin: auto; border: 1px solid #428BCA; background-color: #FFF; border-radius: 4px; padding: 10px 10px 20px 10px;'>
				<?php 
					foreach ($results as $result) {
						echo '<b><font color="#3276B1">'.$result['Comment']['full_name'].':   '.'</font></b>';
						echo '<span>'.$result['Comment']['content'].'</span>';
						echo '</br>';
					}
				?>				
			</div>
			<br><br>

			<div class='row'>
			<?php echo $this->Form->create('Report',array(
				'inputDefaults' => array(  
					'div' => false,  
					'label' => false,  
					'wrapInput' => false,  
					'class' => 'form-control'  
					),  
				'style' => 'margin-left: 45px;',
			    'url' => array('controller' => 'lecturer', 'action' => 'reply', 'id' => $id),
			    'method' => 'post',
			    'enctype' => 'multipart/form-data'
				)); ?>

				<div class="form-group">
					<div class="col-xs-13 col-md-8">
					<?php echo $this->Form->input('content', array(  
						'placeholder' => '返事の内容',  
						'style' => 'width: 400px;',
						'required' => true						
					)); ?>
					</div>
					<div class="col-xs-13 col-md-1">
						<?php echo $this->Form->submit('返事', array(
						'class' => 'btn btn-primary',
						'div' => false
						)); ?>
					</div>
					<div class="col-xs-13 col-md-1">
					<?php echo $this->Form->reset('キャンセル',array(
							'class' => 'btn btn-primary',
							'div' => false, 
							'value' => 'キャンセル'
							));?>	
					
					</div> 						
				</div>
			</form>
			</div>
		</div>		
	</div>
</div>