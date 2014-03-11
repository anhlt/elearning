<div class="col-xs-5 col-md-3">
    <ul class="nav nav-pills nav-stacked" id="myTab">
        <li class="active">
            <?php echo $this->html->link('管理者を追加', array('controller' => "admins", 'action' => "add_admin"
            ));
            ?></li>
			　<li >　<?php echo $this->html->link('管理者を削除', array('controller' => "admins", 'action' => "remove_admin"
            ));
            ?></li>
    </ul>
</div>




<?php
echo "違犯リポットの送信者 :" . $student_fullname . "<br>";
echo "違犯のドキュメント :" . $title . "<br>";
echo "先生名 ：" . $lecturer_name . "<br>";
;
echo "違犯した回数 : ".$count."<br>";
;
echo "内容 : " . $content . "<br>";
;
//echo $this->html->link('見る',array('controller'=>"admins", 'action'=>"view_violation_content＿process"
//                                    ,"accept" =>1));

echo $this->Form->create('Admin', array(
    'inputDefaults' => array(
        'div' => false,
        'label' => false,
        'wrapInput' => false,
        'class' => 'form-control'
    ),
    'class' => 'well',
    'url' => array('controller' => 'Admins', 'action' => 'view_violation_content_process','id' => $violate_id
       ,'count'=> $count, 'lecturer_id' =>$lecturer_id,
        )
));

echo $this->Form->submit('確認', array(
    'div' => false,
    'class' => 'btn btn-default',
    'name' => 'accept'
));
echo $this->Form->submit('削除', array(
    'div' => false,
    'class' => 'btn btn-default',
    'name' => 'checkout'
));
echo $this->Form->end();

?>