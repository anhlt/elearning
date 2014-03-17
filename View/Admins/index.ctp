
<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div class="col-xs-5 col-md-3">
		<ul class="nav nav-pills nav-stacked" id="myTab">
			<li class="active">
                            <?php echo $this->html->link('管理者を追加',array('controller'=>"admins", 'action'=>"add_admin"
                                   ));?></li>
			　<li >　<?php echo $this->html->link('管理者を削除',array('controller'=>"admins", 'action'=>"remove_admin"
                                   ));?></li>
		</ul>
	</div>



