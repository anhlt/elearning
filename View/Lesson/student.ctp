<div style="margin-top: 10px;">
	<?php echo $this->Session->flash(); ?>

	<div class="col-xs-5 col-md-3">
	<ul class="nav nav-pills nav-stacked" id="myTab">
		<li><?php echo $this->html->link('ファイル情報', array('controller'=>'lesson', 'action'=>'doc', 'id'=>$id));?></li>
		<li><?php echo $this->html->link('テスト情報', array('controller'=>'lesson', 'action'=>'test', 'id'=>$id));?></li>
		<li><?php echo $this->html->link('課金情報', array('controller'=>'lesson', 'action'=>'bill', 'id'=>$id));?></li>
		<li class="active">
			<?php echo $this->html->link('学生リスト', array('controller'=>'lesson', 'action'=>'student', 'id'=>$id));?>
		</li>
		<li><?php echo $this->html->link('サマリー情報', array('controller'=>'lesson', 'action'=>'summary', 'id'=>$id));?>	</li>
		<li><?php echo $this->html->link('コメント', array('controller'=>'lesson', 'action'=>'lesson_comment', 'id'=>$id));?></li>	
	</ul>
	</div>

	<div class="col-xs-13 col-md-9">
		<div class="well">
			<div class='form-group'>
				<b><?php echo $this->Html->link("授業管理", array('controller' => 'lecturer', 'action' => 'manage'),
					array('class' => 'btn btn-info')); ?></b>	  
			</div>
			<div style='text-align: center; margin-bottom: 20px;'>
				<h4><b>学生情報</b></h4>
			</div>

			<?php 
				echo $this->Paginator->pagination(array('ul' => 'pagination'));
				$index = 0;
			?>
			<table class="table table-condensed">
				<tr>
					<td  class="col-sm-1"><b>順番</b></td>					
					<td  class="col-sm-3"><?php echo $this->Paginator->sort('name', '名前');?></td>
					<td  class="col-sm-1"><?php echo $this->Paginator->sort('baned', '禁止');?></td>
					<td  class="col-sm-1"><?php echo $this->Paginator->sort('liked', 'いいね');?></td>
					<td  class="col-sm-1">禁止</td>
					<td  class="col-sm-1">削除</td>
				</tr>
			 <?php foreach ($results as $result) {?>
			  <tr>
			  	<td><b><?php if($index < 9) echo '0'; echo ++$index; ?></b></td>			  	
			  	<td><?php echo($result['Student']['full_name']) ?> </td>
			  	<td><b><font color="#0A91FF"><?php echo($result['LessonMembership']['baned']?"True":"False") ?></font></b></td>
			  	<td><b><font color="#0A91FF"><?php echo($result['LessonMembership']['liked']?"True":"False") ?></font></b></td>
			  	<td>
			  		<?php
			  			$s = $result['LessonMembership']['baned'] ? "ok" : "ban";
			  			echo $this->html->link('', array('controller' => 'lesson', 'action' => 'banstudent',"student_id"=>$result['Student']['id'],"lesson_id"=>$result['LessonMembership']['lesson_id']),array('class' => 'glyphicon glyphicon-' . $s . '-circle'))?>	  		
			  	</td>
			  	<td>
			  		<?php echo $this->html->link('', array('controller' => 'lesson', 'action' => 'deletestudent',
			  		"student_id"=>$result['Student']['id'],"lesson_id"=>$result['LessonMembership']['lesson_id']), array('class' => 'glyphicon glyphicon-trash'))?>			  		
			  	</td>
			  </tr>
			 <?php }?>
			</table>
		</div>
	</div>
</div>
