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
		    'url' => array('controller' => 'Tests', 'action' => 'add','id' => $id),
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
					'id' => 'f',
					'placeholder' => 'ファイル',  
					'class' => 'btn-file',
					'accept' => "image/gif, image/jpeg"
				)); ?>

			</div>

			<div class="form-group">
				<?php echo $this->Form->input('test_time', array(  
					'placeholder' => 'テスト時間',  
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
	<script>
		var fl = document.getElementById('f');					

			fl.onchange = function(e){ 
			    var ext = this.value.match(/\.(.+)$/)[1];			    
			    switch(ext)
			    {
			        case 'tsv':		            
			            break;
			        default:
			            alert('File invalid !');
			            this.value='';
			    }		    
			};
	</script>
</html>