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
		<div class='head'><h3>ファイルをアップロード</h3></div>		
		<div class='main'>
		<?php echo $this->Form->create('Test',array(
			'inputDefaults' => array(  
				'div' => false,  
				'label' => false,  
				'wrapInput' => false,  
				'class' => 'form-control'  
				),  
			'class' => 'well',
		    'url' => array('controller' => 'Test', 'action' => 'edit','id' => $id),
		    'method' => 'post',
		    'enctype' => 'multipart/form-data'
			)); ?>

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
					'required' => false,
					'class' => 'btn-file'
				)); ?>
			</div>

			<div class="form-group">
				<?php echo $this->Form->input('test_time', array(
					'value' => $result['test_time'],  
					'style' => 'width: 100px;',
					'label' => '時間 （分）',					
				)); ?>				
			</div>					
			
			<div class='form-group'>					 
				<?php echo $this->Form->submit('アップロード', array(
				'class' => 'btn btn-primary',
				'div' => false
				));?>

				<?php echo $this->Form->reset('再アップロード',array(
					'class' => 'btn btn-primary',
					'div' => false, 
					'value' => '再アップロード'
					));?>	
			</div>
			</div>					
		</form>
	</body>
</html>