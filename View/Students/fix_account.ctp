<?php
$this->LeftMenu->leftMenuStudent(STUDENT_CHOOSE_COURSE); 
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
    'label' => 'Full name',
    'value'=>$student['full_name']
)); ?>  
</div>
    <div class="form-group">

<?php echo $this->Form->input('User.password', array(  
    'placeholder' => 'Password',  
    'style' => 'width:180px;',
    'label' => 'Password'
)); ?>  
    </div>

    <div class="form-group">
<?php echo $this->Form->input('rePassword', array(  
    'placeholder' => 'Re Password',  
    'style' => 'width:180px;',
    'label' => 'Re Password',
    'type'=>'password'
)); ?>  
    </div>
    <div class="form-group">
<?php echo $this->Form->input('Student.email', array(  
    'placeholder' => 'Email',  
    'style' => 'width:180px;',
    'label' => 'Email', 
    'value'=>$student['email']
)); ?>  
    </div>
    <div class="form-group">
<?php echo $this->Form->input('Student.date_of_birth', array(  
    'type'=>'date',
    'placeholder' => 'Birthday', 
    //    'dateFormat'=>'DMY',
    'minYear' => date('Y') -USER_AGE_MAX ,
    'maxYear' => date('Y') -USER_AGE_MIN, 
    'style' => 'width:100px;',
    'label' => 'Birthday',
    'class' => 'inline', 
)); ?>  
    </div>	
    <div class="form-group">
<?php echo $this->Form->input('Student.address', array(  
    'placeholder' => 'Address',  
    'style' => 'width:180px;',
    'label' => 'Address',
    'value' => $student['address']
)); ?>  
    </div>	
    <div class="form-group">
<?php echo $this->Form->input('Student.phone_number', array(  
    'placeholder' => 'Phone',  
    'style' => 'width:180px;',
    'label' => 'Phone',
    'value'=> $student['phone_number']
)); ?>  
    </div>

    <div class="form-group">
<?php echo $this->Form->input('Student.credit_card_number', array(  
    'placeholder' => 'Credit Card Number',  
    'style' => 'width:180px;',
    'label' => 'Credit Card Number',
    'value' =>$student['credit_card_number']
)); ?>
    </div>  
    <div class="form-group">
<?php echo $this->Form->input('Student.question_verifycode_id', array(    
    'style' => 'width:180px;',
    'label' => 'Question',
    'options' => $droplist,
)); ?>  
    </div>
    <div class="form-group">
<?php echo $this->Form->input('Student.answer_verifycode', array(  
    'placeholder' => 'Answer',  
    'style' => 'width:180px;',
    'label' => 'Answer'
)); ?>  
    </div>
<?php echo $this->Form->submit('Update', array(  
    'div' => false,  
    'class' => 'btn btn-default'  
)); ?>  
    <?php echo $this->Form->end(); ?>  
</div> 
