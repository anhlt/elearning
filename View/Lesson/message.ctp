<div style="margin-top: 10px;">
	<?php echo $this->Session->flash(); ?>

	<div class="col-xs-5 col-md-3">
	<ul class="nav nav-pills nav-stacked" id="myTab">
	<li><?php echo $this->html->link('ファイル情報', array('controller'=>'lesson', 'action'=>'doc', 'id'=>$id));?></li>
	<li><?php echo $this->html->link('テスト情報', array('controller'=>'lesson', 'action'=>'test', 'id'=>$id));?></li>
	<li><?php echo $this->html->link('課金情報', array('controller'=>'lesson', 'action'=>'bill', 'id'=>$id));?></li>
	<li><?php echo $this->html->link('学生リスト', array('controller'=>'lesson', 'action'=>'student', 'id'=>$id));?></li>
	<li><?php echo $this->html->link('サマリー情報', array('controller'=>'lesson', 'action'=>'summary', 'id' => $id));?></li>
	<li><?php echo $this->html->link('コメント', array('controller'=>'lesson', 'action'=>'lesson_comment', 'id'=>$id));?>	</li>
	<li class='active'>
		<?php echo $this->html->link('メッセージ', array('controller'=>'lesson', 'action'=>'message', 'id'=>$id));?>
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
				<h4><b>ドキュメント情報</b></h4>
			</div>

			<?php 
				echo $this->Paginator->pagination(array('ul' => 'pagination'));
				$index = 0;
			?>
			
			<?php 
				echo $this->Paginator->pagination(array('ul' => 'pagination'));
				$index = 0;
			?>

			<table class="table table-bordered table-hover">
				<h2>結果</h2>
				<br>
				<tr>
					<td  class="col-sm-1"><b><?php echo $this->Paginator->sort('id', '順番'); ?></b></td>
					<td  class="col-sm-1"><b><?php echo $this->Paginator->sort('id', '送信者'); ?></b></td>
					<td  class="col-sm-4"><b><?php echo $this->Paginator->sort('content','内容');?></b></td>
					<td  class="col-sm-2"><b><?php echo $this->Paginator->sort('type','種類')?></b></td>
					<td  class="col-sm-2"><b><?php echo $this->Paginator->sort('time','時間')?></b></td>
				</tr>
				<?php foreach ($results as $result) :?>
				<tr id="resultsDiv">
					<td><?php if($index < 9) echo '0'; echo ++$index; ?> </td>
					<td><?php echo($result['Message']['username']) ?> </td>
					<td><?php echo($result['Message']['content']) ?> </td>
					<td><?php echo($result['Message']['type']) ?></td>
					<td><?php echo($result['Message']['time']) ?></td>
				</tr>
				<?php endforeach;?>
			</table>				
		</div>		
	</div>	
</div>
