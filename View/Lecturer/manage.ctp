<div class="row">
	<?php echo $this->Session->flash(); ?>

	<div class="col-xs-5 col-md-3">
		<ul class="nav nav-pills nav-stacked" id="myTab">
			<li><a href="/lecturer/">授業管理</a></li>
			<li><a href="/lecturer/lesson">新しい授業</a></li>
			<li><a href="/lecturer/edit">情報を更新</a></li>
			<li><a href="/lecturer/delete">アクアウートを削除</a></li>

		</ul>
	</div>
	<div class="col-xs-13 col-md-9">
		<div class="well">
			<table class="table table-condensed" >
			従業
			<tr>
				<td  class="col-sm-1"><?php echo $this->Paginator->sort('id'); ?></td>
				<td  class="col-sm-1"><?php echo $this->Paginator->sort('Name','名前');?></td>
				<td  class="col-sm-3">説明</td>
				<td  class="col-sm-3">管理</td>
			</tr>
			<?php foreach ($results as $result) :?>
			<tr id="resultsDiv">
				<td><?php echo($result['Lesson']['id']) ?> </td>
				<td><?php echo($result['Lesson']['Name']) ?> </td>
				<td><?php echo($result['Lesson']['summary']) ?> </td>
				<td>
					<?php echo $this->html->link('更新', array('controller' => 'lesson', 'action' => 'edit', "id"=>$result['Lesson']['id']),array('class' => 'btn'))?>
					<?php echo $this->html->link('管理', array('controller' => 'lesson', 'action' => 'doc', "id"=>$result['Lesson']['id']), array('class' => 'btn btn-info'))?>
				</td>
			</tr>
			<?php endforeach;?>
			</table>
		  	<?php echo $this->Paginator->pagination(array(
					'ul' => 'pagination'
				)); ?>

		</div>
	</div>
</div>
<?php $this->Js->writeBuffer(); ?>
