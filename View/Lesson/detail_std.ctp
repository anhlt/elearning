<div class="row">
	<?php echo $this->Session->flash(); ?>

	<div class="col-xs-5 col-md-3">
		<ul class="nav nav-pills nav-stacked" id="myTab">
			<li><a href="/lesson/detail_doc">ファイル情報</a></li>
			<li><a href="/lesson/detail_test">テスト情報</a></li>
			<li><a href="/lesson/detail_coins">課金情報</a></li>
			<li class="active"><a href="/lesson/detail_std">学生リスト</a></li>
			<li><a href="/lesson/summary">サマリー情報</a></li>
			<li><a href="/lesson/report">レポート</a></li>
		</ul>
	</div>

	<div class="col-xs-13 col-md-9">
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
			  	<td><?php echo $this->html->link('Delete', array('controller' => 'lesson', 'action' => 'deletestudent',
			  		"student_id"=>$result['Student']['id'],"lesson_id"=>$result['LessonMembership']['lesson_id']),array('class' => 'btn btn-danger'))?>
			  		<?php echo $this->html->link('Ban', array('controller' => 'lesson', 'action' => 'banstudent',"student_id"=>$result['Student']['id'],"lesson_id"=>$result['LessonMembership']['lesson_id']),array('class' => 'btn btn-warning'))?>
			  	</td>
			  </tr>
			 <?php }?>
			</table>
		</div>
	</div>
</div>
