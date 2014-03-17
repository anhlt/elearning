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
		    'url' => array('controller' => 'Test', 'action' => 'add','id' => $id),
		    'method' => 'post',
		    'enctype' => 'multipart/form-data'
			)); ?>

			<div class="form-group">
				<?php echo $this->Form->input('title', array(  
					'placeholder' => 'ファイルの名前',  
					'style' => 'width: 300px;',
					'label' => 'ファイルの名前',
				)); ?>  
			</div>

			<div class='form-group'>
				<?php echo $this->Form->input('link', array( 
					'type'=> 'file', 
					'placeholder' => 'ファイル',  
					'class' => 'btn-file'
				)); ?>
			</div>

			<div class="form-group">
				<?php echo $this->Form->input('test_time', array(  
					'placeholder' => 'テスト時間',  
					'style' => 'width: 100px;',
					'label' => '時間 （分）',
				)); ?>				
			</div>	
			<?php $val = "thanhbq"?>		
			<div class='form-group'>
				<?php echo $this->Form->textarea('des', array(												
					'label' => '説明', 					 
					'class'=>"form-control",
					'rows'=>"4",
					'style'=> 'width: 600px;'
				));?>
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