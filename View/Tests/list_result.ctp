<div class='row'>
	<div class="col-xs-2 col-md-2">
	<a class='btn btn-info' href="javascript:history.go(-1)">Back</a>
	</div>
	<div class="col-xs-15 col-md-10">
		<table class="table table-bordered table-hover">
			Results
			<tr>
				<td  class="col-sm-1"><?php echo $this->Paginator->sort('id'); ?></td>
				<td  class="col-sm-1"><?php echo $this->Paginator->sort('Student.full_name');?></td>
				<td  class="col-sm-3"><?php echo $this->Paginator->sort('point')?></td>
				<td  class="col-sm-3">Detail</td>
			</tr>
			<?php foreach ($results as $result) :?>
			<tr id="resultsDiv">
				<td><?php echo($result['Result']['id']) ?> </td>
				<td><?php echo($result['Student']['full_name']) ?> </td>
				<td><?php echo($result['Result']['point']) ?> </td>
				<td>
					<?php echo $this->html->link('Detail', array('controller' => 'tests', 'action' => 'result',$result['Result']['id']), array('class' => 'btn btn-info'))?>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
	</div>
</div>