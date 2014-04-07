
<script>
    $("#yesBt").click(function(){
        alert("E-learningのシステムをお使いになる、ありがとうございます");
    });
</script>
<?php
$this->LeftMenu->leftMenuStudent(STUDENT_CHOOSE_COURSE); 
?>
<div class = 'col-xs-13 col-md-9 well' > 
<?php 
echo "本当に自分のアカウントが削除したいですか";

echo $this->Form->create();
echo $this->Form->input("削除", array("type"=>"submit", "label"=>"", "id"=>"yesBt"));
echo $this->Form->end();
?>
</div>
