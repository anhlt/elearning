<div class='col-xs-5 col-md-3'>
	<ul class='nav nav-pills nav-stacked'>
		<li><?php
                echo $this->html->link('管理者の追加', array('controller' => "admins", 'action' => "add_admin"
                ));
?></li>
		<li><?php
                echo $this->html->link('管理者の削除', array('controller' => "admins", 'action' => "remove_admin"
                ));
?></li>
		<li><?php
                echo $this->html->link('IPアドレスの管理', array('controller' => "admins", 'action' => "add_ip_address"
                ));
?></li>
		<li><?php
                echo $this->html->link('先生の管理', array('controller' => "admins", 'action' => "manage_lecturer"
                ));
?></li>
		<li><?php
                echo $this->html->link('学生の管理', array('controller' => "admins", 'action' => "manage_student"
                ));
?></li>
		<li><?php
                echo $this->html->link('先生のランキグ', array('controller' => "admins", 'action' => "ranking_lecturer"
                ));
?></li>
		<li><?php
                echo $this->html->link('違犯報告', array('controller' => "admins", 'action' => "view_violation"
                ));
?></li>
		<li><?php
                echo $this->html->link('仕様の管理', array('controller' => "admins", 'action' => "manage_parameter"
                ));
?></li>
		<li><?php
                echo $this->html->link('データベースの管理', array('controller' => "admins", 'action' => "database_manager"
                ));
?></li>
		<li><?php
                echo $this->html->link('TSVファイルの管理', array('controller' => "admins", 'action' => "fee_manager"
                ));
?></li>
	</ul>
</div>