<html>
<head>
<meta charset='utf-8'/>
<title> 新授業を作成する</title>

<!--
<script type="text/javascript">		
	var row = 0;
	function addRow(frm) {		
		row ++;
		//alert("title"+row);

		var line ='<div class="row" id="row'+row+'"> <div class="col-md-8"> <?php echo $this->Form->input("title'+row+'", array("class"=>"form-control", "required"=> true, "label"=>"ドキュメントの名前", "placeholder"=>"ドキュメントの名前", "style"=>"width: 300px"));?> <?php echo $this->Form->input("link'+row+'", array("type"=>"file", "required"=>true, "class"=>"btn-file"));?> </div><div class="col-md-2"> <input type="button" class="btn btn-primary" value="削除" onclick="removeRow('+row+');">'+
		'</div></div>';

		/*var line =  '<div class="form-group">'+
						'<input type="text" name="title'+row+'" placeholder="ドキュメントの名前" style="width: 300px" label="ドキュメントの名前">'+
					'</div>'+
					'<div class="form-group">'+
						'<input type="file" name="link'+row+'" placeholder="ファイル" class="btn-file" label="ファイル">'+
					'</div>';*/



		console.log(line);
		jQuery('#addition').append(line);
		frm.add_qty.value = '';
		frm.add_name.value = '';
	}

	function removeRow(index) {
		jQuery('#row'+index).remove();
	}


	function getExtension(filename) {
    	return filename.split('.').pop().toLowerCase();
	}

	function checkFile(inputFile) {
	    var valid_extensions = /(.pdf)$/i;
	    if(!valid_extensions.test(inputFile.value)) {
	        alert('Loai file ko hop le!');
	        inputFile.value = '';
	    } else if(inputFile.files[0].size > 2048){ //2M
	        alert('kich thuoc file qua lon!');
	        inputFile.value = '';
	    }
	}
-->

<script type="text/javascript">	
	var row = 0;

	function addRow(frm) {	
		row ++;
		var line = '<div class="form-group">'+
		'<input type="text" name="data[Document]['+row+'][title]" placeholder="ドキュメントの名前" style="width: 300px" label="ドキュメントの名前">'+
		'</div>'+
		'<div class="form-group">'+
		'<input type="file" name="data[Document]['+row+'][link]" placeholder="ファイル" class="btn-file" label="ファイル">'+
		'</div>';

		console.log(line);
		jQuery('#addition').append(line);
		frm.add_qty.value = '';
		frm.add_name.value = '';
	}

	function removeRow(index) {
		jQuery('#row'+index).remove();
	}


	function getExtension(filename) {
	     return filename.split('.').pop().toLowerCase();
	}

	function checkFile(inputFile) {
		var valid_extensions = /(.pdf)$/i;
		if(!valid_extensions.test(inputFile.value)) {
		alert('Loai file ko hop le!');
		inputFile.value = '';
		} else if(inputFile.files[0].size > 2048){ //2M
		alert('kich thuoc file qua lon!');
		inputFile.value = '';
		}
	}
	</script>
</head>

<body>
	<div class='head'><h3>ドキュメントをアップロード</h3></div>	
	<div class='main'>
	<?php echo $this->Form->create('Document',array(
		'inputDefaults' => array(
		'div' => false,
		'label' => false,
		'wrapInput' => false,
		'class' => 'form-control'
		),
		'class' => 'well',
		'url' => array('controller' => 'document', 'action' => 'add','id' => $id),
		'method' => 'post',
		'enctype' => 'multipart/form-data'
		)); ?>

		<div class='form-group'>
		<input onclick="addRow(this.form);" type="button" value='追加' class='btn btn-primary'/>
		</div>

		<div class="form-group">
		<?php echo $this->Form->input('Document.0.title', array(
		'placeholder' => 'ドキュメントの名前',
		'style' => 'width: 300px;',
		'label' => 'ドキュメントの名前'	
		)); ?>
		</div>

		<div class='form-group'>
		<?php echo $this->Form->input('Document.0.link', array(
		'type'=> 'file',
		'placeholder' => 'ファイル',
		'class' => 'btn-file',
		'required' => true
		)); ?>
		</div>

		<div id='addition'>

		</div>
		<br>

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
		'div' => false
		));?>

		<?php echo $this->Form->reset('再アップロード',array(
		'class' => 'btn btn-primary',
		'div' => false,
		'value' => '再アップロード'
		));?>	
		</div>
		</form>
	</div>	
</body>
</html>