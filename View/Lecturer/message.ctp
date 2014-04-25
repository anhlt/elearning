<div style="margin-top: 10px;">
	<?php echo $this->Session->flash(); ?>

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
			<div class='form-group'>
				<b><?php echo $this->Html->link("授業管理", array('controller' => 'lecturer', 'action' => 'manage'),
					array('class' => 'btn btn-info')); ?></b>	  
			</div>
			<div style='text-align: center; margin-bottom: 20px;'>
				<h2><b>メッセージ</b></h2>
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
				<tr>
					<td class="col-sm-1"><b><?php echo $this->Paginator->sort('id', '順番'); ?></b></td>
					<td class="col-sm-1"><b><?php echo $this->Paginator->sort('sender', '送信者'); ?></b></td>
					<td class="col-sm-3"><b><?php echo $this->Paginator->sort('content','内容');?></b></td>
					<td class="col-sm-2"><b><?php echo $this->Paginator->sort('type','種類')?></b></td>
					<td class="col-sm-2"><b><?php echo $this->Paginator->sort('object','オブジェクト')?></b></td>
					<td class="col-sm-2"><b><?php echo $this->Paginator->sort('time','時間')?></b></td>
					<td class="col-sm-3"><b>読む</b></td>
				</tr>
				<?php foreach ($results as $result) :?>
				<tr id="resultsDiv">
					<td><b><?php if($index < 9) echo '0'; echo ++$index; ?></b></td>
					<td><?php echo($result['Message']['username']) ?> </td>
					<td><?php echo($result['Message']['content']) ?> </td>
					<td><?php echo($result['Message']['type']) ?></td>
					<td><?php echo($result['Message']['object_type']) ?></td>
					<td><?php echo($result['Message']['time']) ?></td>
					<td>
						<?php
							if($result['Message']['read'] == 0) {
								$link = 'msg_accept/id:'. $result['Message']['id'];
								echo "<a href='". $link ."'>読む</a>";
							} else {
								echo "読みました";
							}
						?>

					</td> 
				</tr>
				<?php endforeach;?>
			</table>				
		</div>		
	</div>	
</div>
