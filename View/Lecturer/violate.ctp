<div class = "col-xs col-md-9 col-md-offset-2">
	<table class='table table-striped table-bordered'>
			<h2>授業</h2>
			<tr>
				<td  class="col-sm-1">Document</td>
				<td  class="col-sm-2">Lesson</td>
				<td  class="col-sm-2">Reporter</td>
				<td  class="col-sm-1">Messess</td>
				<td  class="col-sm-1">accepted</td>
			</tr>			
			<?php foreach ($docs as $doc):?>
				<?php foreach ($doc['Violate'] as $Violate) :?>
				<tr>
					<td  class="col-sm-1"><?php echo $doc['Document']['title']; ?></td>
					<td  class="col-sm-2"><?php echo $doc['Lesson']['name'];?></td>
					<td  class="col-sm-2"><?php echo $Violate['student_id'];?></td>
					<td  class="col-sm-1"><?php echo $Violate['content'];?></td>
					<td  class="col-sm-1"><?php echo $Violate['accepted'];?></td>
				</tr>	
				<?php endforeach?>		
			<?php endforeach ?>

	</table>
</div>