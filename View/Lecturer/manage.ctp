<div class="row">
	<?php
		echo $this->Session->flash(); 
		$index = 0;
	?>

	<div class="col-xs-5 col-md-3">
		<ul class="nav nav-pills nav-stacked" id="myTab">
			<li><a href="/lesson/search">検索</a></li>
			<li><a href="/lecturer/">授業管理</a></li>
			<li><a href="/lecturer/lesson">新しい従業</a></li>
			<li><a href="/lecturer/edit">情報を更新</a></li>
			<li><a href="/lecturer/delete">アクアウートを削除</a></li>
		</ul>
	</div>
	<div class="col-xs-13 col-md-9">
		<div class="well">
			<table class="table table-condensed">
			<h2>授業</h2>
			<tr>
				<td  class="col-sm-1"><?php echo $this->Paginator->sort('id', '順番'); ?></td>
				<td  class="col-sm-2"><?php echo $this->Paginator->sort('Name','名前');?></td>
				<td  class="col-sm-2">説明</td>
				<td  class="col-sm-1">更新</td>
				<td  class="col-sm-1">管理</td>
			</tr>
			<?php foreach ($results as $result) :?>
			<tr id="resultsDiv">
				<td><?php if($index < 9) echo '0'; echo ++$index; ?> </td>
				<td><?php echo($result['Lesson']['Name']) ?> </td>
				<td><?php echo($result['Lesson']['summary']) ?> </td>
				<td>
					<?php echo $this->html->link('更新', array('controller' => 'lesson', 'action' => 'edit', "id"=>$result['Lesson']['id']),array('class' => 'btn btn-primary'))?>					
				</td>
				<td>
					<?php echo $this->html->link('管理', array('controller' => 'lesson', 'action' => 'doc', "id"=>$result['Lesson']['id']), array('class' => 'btn btn-primary'))?>
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
