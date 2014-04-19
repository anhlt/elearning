<?php
$this->LeftMenu->leftMenuStudent(STUDENT_PROFILE, "更新"); 
?>
    <?php echo $this->Session->flash(); ?>
<div class = 'col-xs-13 col-md-9' > 
<?php echo $this->Form->create('User', array(  
    'inputDefaults' => array(  
        'div' => false,  
        'label' => false,  
        'wrapInput' => false,  
        'class' => 'form-control'  
    ),  
    'class' => 'well'  
)); ?>
<h1> プロファイを更新する</h1>
<div class='form-group'>
<?php echo $this->Form->input('Student.full_name', array(  
    'placeholder' => 'Full name',  
    'style' => 'width:180px;',
    'label' => '氏名',
    'value'=>$student['full_name']
)); ?>  
</div>
    <div class="form-group">

<?php echo $this->Form->input('User.password', array(  
    'placeholder' => 'パスワード',  
    'style' => 'width:180px;',
    'label' => 'パスワード'
)); ?>  
    </div>

    <div class="form-group">
<?php echo $this->Form->input('rePassword', array(  
    'placeholder' => '再パスワード',  
    'style' => 'width:180px;',
    'label' => '再パスワード',
    'type'=>'password'
)); ?>  
    </div>
    <div class="form-group">
<?php echo $this->Form->input('Student.email', array(  
    'placeholder' => 'メール',  
    'style' => 'width:180px;',
    'label' => 'メール', 
    'value'=>$student['email']
)); ?>  
    </div>
    <div class="form-group">
<?php echo $this->Form->input('Student.date_of_birth', array(  
    'type'=>'date',
    'placeholder' => '生年月日', 
    //    'dateFormat'=>'DMY',
    'minYear' => date('Y') -USER_AGE_MAX ,
    'maxYear' => date('Y') -USER_AGE_MIN, 
    'style' => 'width:100px;',
    'label' => '生年月日',
    'class' => 'inline', 
)); ?>  
    </div>	
    <div class="form-group">
<?php echo $this->Form->input('Student.address', array(  
    'placeholder' => 'アドレス',  
    'style' => 'width:180px;',
    'label' => 'アドレス',
    'value' => $student['address']
)); ?>  
    </div>	
    <div class="form-group">
<?php echo $this->Form->input('Student.phone_number', array(  
    'placeholder' => '電話番号',  
    'style' => 'width:180px;',
    'label' => '電話番号',
    'value'=> $student['phone_number']
)); ?>  
    </div>

    <div class="form-group">
<?php echo $this->Form->input('Student.credit_card_number', array(  
    'placeholder' => '銀行口座情報',  
    'style' => 'width:180px;',
    'label' => '銀行口座情報',
    'value' =>$student['credit_card_number']
)); ?>
    </div>  
    <div class="form-group">
<?php echo $this->Form->input('Student.question_verifycode_id', array(    
    'style' => 'width:180px;',
    'label' => '質問',
    'options' => $droplist,
)); ?>  
    </div>
    <div class="form-group">
<?php echo $this->Form->input('Student.answer_verifycode', array(  
    'placeholder' => '答え',  
    'style' => 'width:180px;',
    'label' => '答え'
)); ?>  
    </div>
<?php echo $this->Form->submit('更新', array(  
    'div' => false,  
    'class' => 'btn btn-default'  
)); ?>  
    <?php echo $this->Form->end(); ?>  
</div> 
