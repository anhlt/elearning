<div class="row">
	<?php
		echo $this->Session->flash(); 
		$index = 0;
	?>

	<div class="col-xs-5 col-md-3">
	<ul class="nav nav-pills nav-stacked" id="myTab">
		<li><?php echo $this->html->link('検索', array('controller'=>'lesson', 'action'=>'search'));?></li>
		<li><?php echo $this->html->link('授業管理', array('controller'=>'lecturer', 'action'=>'manage'));?></li>
		<li><?php echo $this->html->link('新しい従業', array('controller'=>'lecturer', 'action'=>'lesson'));?></li>
		<li><?php echo $this->html->link('情報を更新', array('controller'=>'lecturer', 'action'=>'edit'));?></li>
		<li><?php echo $this->html->link('メッセージ', array('controller'=>'lecturer', 'action'=>'message'));?></li>
		<li><?php echo $this->html->link('アクアウートを削除', array('controller'=>'lecturer', 'action'=>'delete'));?>	</li>	
	</ul>
	</div>
	<div class="col-xs-13 col-md-9">
		<div class="well">
			<div style='text-align: center; margin-bottom: 40px;'>
				<h4><b>授業を管理</b></h4>
			</div>

			<table class="table table-condensed">
			<tr>
				<td  class="col-sm-1"><b><?php echo $this->Paginator->sort('id', '順番'); ?><b></td>
				<td  class="col-sm-3"><b><?php echo $this->Paginator->sort('Name','名前');?><b></td>
				<td  class="col-sm-3"><b>説明<b></td>
				<td  class="col-sm-2"><b>禁止された</b></td>
				<td  class="col-sm-1"><b>更新</b></td>
				<td  class="col-sm-1"><b>管理</b></td>				
			</tr>
			<?php foreach ($results as $result) :?>
			<tr id="resultsDiv">
				<td><b><?php if($index < 9) echo '0'; echo ++$index; ?></b></td>
				<td><?php echo($result['Lesson']['Name']) ?> </td>
				<td><?php echo($result['Lesson']['summary']) ?> </td>
				<td><b><?php echo($result['Lesson']['baned']?"True":"False");?><b></td>
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
