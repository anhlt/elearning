<div class="row">
	<?php echo $this->Session->flash(); ?>

	<div class="col-xs-5 col-md-3">
	<ul class="nav nav-pills nav-stacked" id="myTab">
		<li><?php echo $this->html->link('検索', array('controller'=>'lesson', 'action'=>'search'));?></li>
		<li><?php echo $this->html->link('授業管理', array('controller'=>'lecturer', 'action'=>'manage'));?></li>
		<li><?php echo $this->html->link('新しい従業', array('controller'=>'lecturer', 'action'=>'lesson'));?></li>
		<li><?php echo $this->html->link('情報を更新', array('controller'=>'lecturer', 'action'=>'edit'));?></li>
		<li><?php echo $this->html->link('メッセージ', array('controller'=>'lecturer', 'action'=>'message'));?></li>
		<li><?php echo $this->html->link('アクアウートを削除', array('controller'=>'lecturer', 'action'=>'delete'));?>	</li>	
	</ul>
	</div>
	<div class="col-xs-13 col-md-9">
		<?php echo $this->Form->create('Lesson',array(
			'inputDefaults' => array(  
				'div' => false,  
				'label' => false,  
				'wrapInput' => false,  
				'class' => 'form-control',
				),  
			'class' => 'well',
		    'url' => array('controller' => 'Lesson', 'action' => 'add')
			)); ?>

		<div class="form-group">
			<?php echo $this->Form->input('id', array(  
				'placeholder' => 'クラスコード',  
				'style' => 'width:300px;',
				'label' => 'クラスコード',
				'type'  => 'hidden',
			)); ?>  
		</div>

		<div class="form-group">
			<?php echo $this->Form->input('name', array(  
				'placeholder' => 'クラス名',  
				'style' => 'width:300px;',
				'label' => 'クラス名',
			)); ?>  
		</div>

		<div class="form-group">
			<?php echo $this->Form->text('summary', array(  
				'placeholder' => '説明',  
				'style' => 'width:300px;',
				'label' => '説明',
			)); ?>  
		</div>

		<div class="form-group">
			<?php echo $this->Form->text('Tag.name', array(  
				'placeholder' => 'タグ',  
				'style' => 'width:300px;',
				'label' => 'タグ',
				'class' => 'tm-input'
			)); ?>
		</div>
		<?php echo $this->Form->submit('追加', array(  
		'div' => false,  
		'class' => 'btn btn-default',
		'id' => 'button'  
		)); ?>  


		<?php echo $this->Form->end(); ?>  
	</div>

	<script type="text/javascript">    
	window.onload = function WindowLoad(event) {
			jQuery(".tm-input").tagsManager({
		});
	}

	$("#button").click(function(event){
		event.preventDefault()
		console.log( "Handler for .submit() called.");
		var tag = ($('.tm-input').tagsManager('tags'));
		console.log(tag);
		console.log(tag.length);
		if (tag.length != 0 || $('.tm-input').val().length != 0){
			$('.tm-input').tagsManager('pushTag',$('.tm-input').val());
			$('#LessonLessonForm').submit();
		}else if(tag.length == 0 && $('.tm-input').val().length == 0)
		{
			alert('タグを入力してください');
		}
	});
	</script>
</div>
