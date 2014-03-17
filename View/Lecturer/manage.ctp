<div class="row">
	<?php echo $this->Session->flash(); ?>

	<div class="col-xs-5 col-md-3">
		<ul class="nav nav-pills nav-stacked" id="myTab">
			<li class="active"><a href="/lecturer/">Class Manager</a></li>
			<li><a href="/lecturer/lesson">New Class</a></li>
			<li><a href="#">Messages</a></li>
		</ul>
	</div>
	<div class="col-xs-13 col-md-9">
		<div class="well">
			<table class="table table-condensed" >
			Lessons
			<tr>
				<td  class="col-sm-1"><?php echo $this->Paginator->sort('id'); ?></td>
				<td  class="col-sm-1"><?php echo $this->Paginator->sort('Name');?></td>
				<td  class="col-sm-3">Description</td>
				<td  class="col-sm-3">Manage</td>
			</tr>
			<?php foreach ($results as $result) :?>
			<tr id="resultsDiv">
				<td><?php echo($result['Lesson']['id']) ?> </td>
				<td><?php echo($result['Lesson']['Name']) ?> </td>
				<td><?php echo($result['Lesson']['summary']) ?> </td>
				<td><?php echo $this->html->link('Delete', array('controller' => 'lesson', 'action' => 'delete', "id"=>$result['Lesson']['id']),array('class' => 'button'))?>
					<?php echo $this->html->link('Edit', array('controller' => 'lesson', 'action' => 'edit', "id"=>$result['Lesson']['id']),array('class' => 'button'))?>
					<?php echo $this->html->link('Detail', array('controller' => 'lesson', 'action' => 'detail_doc', "id"=>$result['Lesson']['id']), array('class' => 'button'))?>
					<?php echo $this->html->link('Manage', array('controller' => 'lecturer', 'action' => 'studentmanage', "lesson_id"=>$result['Lesson']['id']), array('class' => 'btn btn-info'))?>
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
