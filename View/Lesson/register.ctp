<script>
    $("#yesBt").click(function(){
        alert("登録してありがとうございます");
    });
</script>
<?php
$this->LeftMenu->leftMenuStudent(STUDENT_CHOOSE_COURSE); 
?>
<div class = 'col-xs-13 col-md-9 well' > 
<?php 
echo "この授業を登録すると、あなたのアカウントから２万ドンを引かれます。同意しますか";

echo $this->Form->create();
echo $this->Form->input("同意", array("type"=>"submit", "label"=>"", "id"=>"yesBt"));
echo $this->Form->end();
?>
</div>
