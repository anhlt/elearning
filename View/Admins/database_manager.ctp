<div class = 'row'>
 	<?php echo $this->element('admin_menus');?>
	<div class="col-xs-13 col-md-9">  
    	<?php echo $this->Session->flash(); ?>

		<h2>Database Manager</h2>
		<table class="table table-condensed">
			<tr>
				<th>File name</th>
				<th>File size</th>
				<th>Created date</th>
				<th>Restore</th>
				<th><?php echo $this->Html->link('Delete all',array('controller'=>'admins', 'action'=>'delete_all'),array(),"Are you sure to delete all backup files");?></th>
			</tr>
			<?php foreach ($files_info as $file_info): ?>
			<tr>
				<td><?php echo $file_info['basename'] ?></td>
				<td><?php echo $filesize = round($file_info['filesize']/1024,1);?> KB</td>
				<td><?php echo $file_info['created_date']?></td>
				<td><?php echo $this->Html->link('Restore',array('controller'=>'admins', 'action'=>'restore_database', 'file' => $file_info['basename']),array(),"Are you sure to delete this backup to replace current database")?></td>
				<td><?php echo $this->Html->link('Delete',array('controller'=>'admins', 'action'=>'delete_file', 'file' => $file_info['basename']),array(),"Are you sure to delete this backup") ?></td>
			</tr>
			<?php endforeach ?>

		</table>
		<?php echo "<td>".$this->Html->link('Create a backup',array('controller'=>'admins', 'action'=>'backup_database'),array(),"Are you sure to create a backup")."</td>"; ?>
	</div>

</div>