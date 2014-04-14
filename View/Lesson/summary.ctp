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
			<li class="active">
				<?php echo $this->html->link('サマリー情報', array('controller' => 'lesson', 'action' => 'summary',
					'id' => $id));?> 
			</li>
			<li>
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
				<h4><b>サマリー情報</b></h4>
			</div>

			<div class='row'>
				<div class='col-md-4'>参加した人：</div>
				<div class='col-md-8'><?php echo $row?>　人</div>
			</div>
			<br>
			<div class='row'>
				<div class='col-md-4'>いいね：</div>
				<div class='col-md-8'><?php echo $like?> %</div>
			</div>
			<br>
			<div class='row'>
				<div class='col-md-4'>課金：</div>
				<div class='col-md-8'><?php echo ($row * 20000)?>　VND</div>
			</div>
			<br> <br>
			<div class='row'>
				<div class='col-md-2'>
					<p style='margin-top: 15px;'>授業を削除する</p>
				</div>
				<div class='col-md-1'>
					<?php echo $this->html->link('Delete', array('controller' => 'lesson', 'action' => 'delete'), array('class' => 'btn btn-primary'))?>
				</div>
			</div>
		</div>
	</div>
</div>
