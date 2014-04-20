<div class="row">
	<?php echo $this->Session->flash(); ?>


	<div class="col-xs-5 col-md-3">
		<ul class="nav nav-pills nav-stacked" id="myTab">
			<li><a href="/lesson/search">授業を検索</a></li>
			<li><a href="/lecturer/">従業管理</a></li>
			<li><a href="/lecturer/lesson">新しい従業</a></li>
			<li><a href="/lecturer/edit">情報を更新</a></li>
			<li><a href="/lecturer/delete">アクアウートを削除</a></li>

		</ul>
	</div>		<div class="col-xs-13 col-md-9">
		<div class="well">
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
			  	<td><?php echo($result['LessonMembership']['baned']?"True":"false") ?> </td>
			  	<td><?php echo($result['LessonMembership']['liked']?"True":"false") ?> </td>
			  	<td><?php echo $this->html->link('削除', array('controller' => 'lesson', 'action' => 'deletestudent',
			  		"student_id"=>$result['Student']['id'],"lesson_id"=>$result['LessonMembership']['lesson_id']),array('class' => 'btn btn-danger'))?>
			  		<?php echo $this->html->link('ロック', array('controller' => 'lesson', 'action' => 'banstudent',"student_id"=>$result['Student']['id'],"lesson_id"=>$result['LessonMembership']['lesson_id']),array('class' => 'btn btn-warning'))?>
			  	</td>
			  </tr>
			 <?php }?>
			</table>
		</div>
	</div>
</div>
