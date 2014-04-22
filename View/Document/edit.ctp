<?php echo $this->Html->script('jquery.validate', array('inline' => false));?>
<html>
	<head>
		<meta charset='utf-8'/>
		<title> 新授業を作成する</title>	
		<script>
			$(":file").filestyle({input: false});
		</script>		
	</head>
	<body>
		<?php echo $this->Session->flash(); ?>	
		<div class='head'><h3>ファイルをアップロード</h3></div>		
		<div class='main'>
		<?php echo $this->Form->create('Document',array(
			'inputDefaults' => array(  
				'div' => false,  
				'label' => false,  
				'wrapInput' => false,  
				'class' => 'form-control'  
				),  
			'class' => 'well',
		    'url' => array('controller' => 'document', 'action' => 'edit', 'id' => $id, 'document_id' => $document_id, 'ihan' => $ihan),
		    'method' => 'post',
		    'enctype' => 'multipart/form-data'
			)); ?>
			
			<!-- <?php echo $this->Form->hidden('ihan', array('value' => $ihan));?> -->		
			<div class="form-group">
				<?php echo $this->Form->input('title', array(  
					'value' => $result['title'],  
					'style' => 'width: 300px;',
					'label' => 'ファイルの名前',
				)); ?>  
			</div>

			<div class='form-group'>
				<?php echo $this->Form->input('link', array( 
					'type'=> 'file',					
					'value' => $result['link'],
					'placeholder' => 'ファイル',
					'required' => $ihan,
					'class' => 'btn-file'
				)); ?>
			</div>

			<div class='row'>
				<div class=' col-md-1'>
				<?php echo $this->Form->checkbox('Document.check', array(
				'class' => 'btn-checkbox',
				'required' => true	
				)); ?>	
				</div>
				<div>私はそのドキュメントを専従する</div>
			</div>

			<br>
			<div class='form-group'>
			<?php echo $this->Form->submit('アップロード', array(
				'class' => 'btn btn-primary',
				'div' => false,				
			));?>

			<?php echo $this->Form->reset('再アップロード',array(
				'class' => 'btn btn-primary',
				'div' => false,
				'value' => '再アップロード'
			));?>
			</div>								
		</form>
	</body>
</html>