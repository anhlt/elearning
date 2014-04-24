<?php $index = 0; ?>
<div class='row'>
	<div class="col-xs-2 col-md-2">
	<a class='btn btn-info' href="javascript:history.go(-1)">戻る</a>
	</div>
	<div class="col-xs-15 col-md-10">
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
				<td><font color="red"><b><?php echo($result['Message']['type']) ?><b></font></td>
				<td><?php echo($result['Message']['time']) ?></td>
			</tr>
			<?php endforeach;?>
		</table>
	</div>
</div>