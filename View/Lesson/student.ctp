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
			<li class="active">
				<?php echo $this->html->link('学生リスト', array('controller' => 'lesson', 'action' => 'student',
					'id' => $id));?> 
			</li>
			<li>
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
				<h4><b>学生情報</b></h4>
			</div>

			<?php echo $this->Paginator->pagination(array(
				'ul' => 'pagination'
				)); ?>
			<table class="table table-condensed">
				Students
				<tr>
					<td  class="col-sm-1"><?php echo $this->Paginator->sort('id'); ?></td>					
					<td  class="col-sm-2"><?php echo $this->Paginator->sort('Name');?></td>
					<td  class="col-sm-1"><?php echo $this->Paginator->sort('baned');?></td>
					<td  class="col-sm-1"><?php echo $this->Paginator->sort('liked');?></td>
					<td  class="col-sm-2">Manage</td>
				</tr>
			 <?php foreach ($results as $result) {?>
			  <tr>
			  	<td><?php echo($result['Student']['id']) ?> </td>			  	
			  	<td><?php echo($result['Student']['full_name']) ?> </td>
			  	<td><?php echo($result['Study']['baned']?"True":"false") ?> </td>
			  	<td><?php echo($result['Study']['liked']?"True":"false") ?> </td>
			  	<td><?php echo $this->html->link('Delete', array('controller' => 'lesson', 'action' => 'deletestudent',
			  		"student_id"=>$result['Student']['id'],"lesson_id"=>$result['Study']['lesson_id']),array('class' => 'btn btn-danger'))?>
			  		<?php echo $this->html->link('Ban', array('controller' => 'lesson', 'action' => 'banstudent',"student_id"=>$result['Student']['id'],"lesson_id"=>$result['Study']['lesson_id']),array('class' => 'btn btn-warning'))?>
			  	</td>
			  </tr>
			 <?php }?>
			</table>
		</div>
	</div>
</div>
