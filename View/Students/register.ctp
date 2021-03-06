
<div class="row">
    <?php echo $this->Session->flash(); ?>
    <div class="col-md-8 col-md-offset-2">  

<?php echo $this->Form->create('User', array(  
    'inputDefaults' => array(  
        'div' => false,  
        'label' => false,  
        'wrapInput' => false,  
        'class' => 'form-control'  
    ),  
    'class' => 'well'  
)); ?> 

 <div class="form-group">
<?php echo $this->Form->input('username', array(  
    'placeholder' => 'ユーザ名',  
    'style' => 'width:180px;',
    'label' => 'ユーザ名',
)); ?>  
    </div>
    <div class="form-group">

    <div class="form-group">
<?php echo $this->Form->input('Student.full_name', array(  
    'placeholder' => '氏名',  
    'style' => 'width:180px;',
    'label' => '氏名',
)); ?>  
    </div>
    
<?php echo $this->Form->input('password', array(  
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
    'label' => 'メール'
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
    'class' => 'inline'
)); ?>  
    </div>	
    <div class="form-group">
<?php echo $this->Form->input('Student.address', array(  
    'placeholder' => 'アドレス',  
    'style' => 'width:180px;',
    'label' => 'アドレス',
)); ?>  
    </div>	
    <div class="form-group">
<?php echo $this->Form->input('Student.phone_number', array(  
    'placeholder' => '電話番号',  
    'style' => 'width:180px;',
    'label' => '電話番号',
)); ?>  
    </div>

    <div class="form-group">
<?php echo $this->Form->input('Student.credit_card_number', array(  
    'placeholder' => 'クレジットカード情報',  
    'style' => 'width:180px;',
    'label' => 'クレジットカード情報',
)); ?>
    </div>  
    
<?php echo $this->Form->submit('登録', array(  
    'div' => false,  
    'class' => 'btn btn-default'  
)); ?>  
    <?php echo $this->Form->end(); ?>  

    </div>  
</div>  
