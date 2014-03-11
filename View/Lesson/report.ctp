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
			<div class='row'><h4 style='text-align: center;'><b>レポートがない</b></h4></div>
			<br><br> <br>	
			<div class='row' style='margin-left: 43px;'><b>返事</b></div>
			<br>			
			<div style='width: 700px; height: 100px; margin: auto; border: 1px solid #428BCA;'>
				

				
			</div>

			

			<br><br>

			<div class='row'>
			<?php echo $this->Form->create('Test',array(
				'inputDefaults' => array(  
					'div' => false,  
					'label' => false,  
					'wrapInput' => false,  
					'class' => 'form-control'  
					),  
				'style' => 'margin-left: 45px;',
			    'url' => array('controller' => 'Test', 'action' => 'add','id' => $id),
			    'method' => 'post',
			    'enctype' => 'multipart/form-data'
				)); ?>

				<div class="form-group">
					<div class="col-xs-13 col-md-8">
					<?php echo $this->Form->input('title', array(  
						'placeholder' => '返事の内容',  
						'style' => 'width: 300px;'						
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
