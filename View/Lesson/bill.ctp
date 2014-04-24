<div style="margin-top: 10px;">
	<?php echo $this->Session->flash(); ?>

	<div class="col-xs-5 col-md-3">
		<ul class="nav nav-pills nav-stacked" id="myTab">
		<li><?php echo $this->html->link('ファイル情報', array('controller'=>'lesson', 'action'=>'doc', 'id'=>$id));?></li>
		<li><?php echo $this->html->link('テスト情報', array('controller'=>'lesson', 'action'=>'test', 'id'=>$id));?></li>
		<li class="active">
			<?php echo $this->html->link('課金情報', array('controller'=>'lesson', 'action'=>'bill', 'id'=>$id));?>
		</li>
		<li><?php echo $this->html->link('学生リスト', array('controller'=>'lesson', 'action'=>'student', 'id'=>$id));?></li>
		<li><?php echo $this->html->link('サマリー情報', array('controller'=>'lesson', 'action'=>'summary', 'id'=>$id));?>	</li>
		<li><?php echo $this->html->link('コメント', array('controller'=>'lesson', 'action'=>'lesson_comment', 'id'=>$id));?></li>
		<li><?php echo $this->html->link('メッセージ', array('controller'=>'lesson', 'action'=>'message', 'id'=>$id));?></li>
		</ul>
	</div>
	<div class="col-xs-13 col-md-9">
		<div class="well">
			<div class='form-group'>
				<b><?php echo $this->Html->link("授業管理", array('controller' => 'lecturer', 'action' => 'manage'), 
					array('class' => 'btn btn-info')); ?></b>	  
			</div>
			<div style='text-align: center; margin-bottom: 20px;'>
				<h4><b>課金情報</b></h4>
			</div>
			<?php 
				echo $this->Paginator->pagination(array(
					'ul' => 'pagination'
					));
				$index = 0;
			?>	

			<table class="table table-condensed">
				<tr>
					<td  class="col-sm-2">順番</td>										
					<td  class="col-sm-3"><?php echo $this->Paginator->sort('Name', '名前');?></td>
					<td  class="col-sm-3"><?php echo $this->Paginator->sort('Start time', '開始時');?></td>
					<td 　class="col-sm-3"><?php echo $this->Paginator->sort('End time', '終了時間');?></td>
					<td  class="col-sm-1"><?php echo $this->Paginator->sort('End', '終わり');?><td>	
				</tr>
			 <?php foreach ($results as $result) {?>
			  <tr>
			  	<td><?php if($index < 9) echo "0"; echo ++$index; ?> </td>	  		  	
			  	<td><?php echo($result['Student']['full_name']) ?> </td>
			  	<td><?php echo($result['LessonMembership']['days_attended']) ?> </td>
			  	<td>
			  		<?php			  			 			
						$datetime = new DateTime($result['LessonMembership']['days_attended']);						
						$datetime->modify("+". $lesson_time . "day");
						echo $datetime->format('Y-m-d H:i:s');
			  		?> 
			  	</td>
			  	<td>
			  		<?php
			  			$today = date("Y-m-d H:i:s");
						$date2='31_12_11';
						echo (strtotime($datetime->format('Y-m-d H:i:s')) < strtotime($today)) ? "True" : "False";
			  		?>
			  	</td>			  	
			  </tr>
			 <?php }?>
			</table>
		</div>
	</div>
</div>
