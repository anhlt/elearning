<div class = 'row'>
 	<?php echo $this->element('admin_menus');?>
	<div class="col-xs-13 col-md-9">  
    	<?php echo $this->Session->flash(); ?>

		<h2>Database Manager</h2>
		<table class="table table-condensed">
			<tr>
				<th>ファイル名</th>
				<th>ファイルのサイズ</th>
				<th>作成時間</th>
				<th>リストア</th>
				<th><?php echo $this->Html->link('全部削除',array('controller'=>'admins', 'action'=>'delete_all'),array(),"Are you sure to delete all backup files");?></th>
			</tr>
			<?php foreach ($files_info as $file_info): ?>
			<tr>
				<td><?php echo $file_info['basename'] ?></td>
				<td><?php echo $filesize = round($file_info['filesize']/1024,1);?> KB</td>
				<td><?php echo $file_info['created_date']?></td>
				<td><?php echo $this->Html->link('リストア',array('controller'=>'admins', 'action'=>'restore_database', 'file' => $file_info['basename']),array(),"Are you sure to delete this backup to replace current database")?></td>
				<td><?php echo $this->Html->link('削除',array('controller'=>'admins', 'action'=>'delete_file', 'file' => $file_info['basename']),array(),"Are you sure to delete this backup") ?></td>
			</tr>
			<?php endforeach ?>

		</table>
		<?php echo "<td>".$this->Html->link('バックアップファイルの作成',array('controller'=>'admins', 'action'=>'backup_database'),array(),"バックアップファイルを作成しない？")."</td>"; ?>
	</div>

</div>