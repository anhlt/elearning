

<?php echo $this->Form->create('Document',array(
	'inputDefaults' => array(  
		'div' => false,  
		'label' => false,  
		'wrapInput' => false,  
		'class' => 'form-control'  
		),  
	'class' => 'well',
    'url' => array('controller' => 'Documents', 'action' => 'edit')
    )); ?>


<?php echo   $this->Form->input('id'); ?>
<?php echo $this->Upload->edit('Document', $this->Form->fields['Document.id']);?>


<?php echo $this->Form->submit('Save', array(  
'div' => false,  
'class' => 'btn btn-default'  
)); ?>  