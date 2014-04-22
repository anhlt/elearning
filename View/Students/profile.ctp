    <?php echo $this->Session->flash(); ?>
    <?php $this->LeftMenu->leftMenuStudent(STUDENT_PROFILE); ?>

    <div class="col-xs-13 col-md-9">  
        <div class="well">        
<?php
    echo ("<h1>".$student['full_name']."君のプロファイル</h1>");
    echo $this->Html->image("icon/student.jpg", array("height"=>"100", "width"=>"100"))."<br>";
    echo $student['full_name']."</br>"."<br>"."<br>";
    echo "<table class='table table-bordered'>";
    echo $this->Html->tableCells(array("<span class = 's'>ユーザ名</span>",  $user['username'])); 
    echo $this->Html->tableCells(array("<span class = 's'>アドレス</span>", $student['address']));
    echo $this->Html->tableCells(array("<span class = 's'>メール</span>", $student['email']));
    echo $this->Html->tableCells(array("<span class = 's'>誕生日</span>", $student['date_of_birth']));
    echo $this->Html->tableCells(array("<span class = 's'>クレジットカードの番号</span>",$student['credit_card_number']));
    //  echo $this->Html->tableCells(array("<span class = 's'>ランダムの質問</span>", $student['current_verifycode']));
    echo "</table>";
    if ($student['id']==Authcomponent::user('id')){
        echo $this->Html->link("アカウントを更新",array("controller"=>"students", "action"=>"fix_account"));
        echo "<br>";
        echo $this->Html->link("アカウントを削除", array("controller"=>"students", "action"=>"delete"));
    }
?>
        </div>

    </div>
