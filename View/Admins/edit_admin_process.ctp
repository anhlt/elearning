<div class="row">
    
    <?php echo $this->element('admin_menus');?>
    <div class="col-xs-13 col-md-9">
        <h2>管理者の登録情報の更新</h2>  
        <?php echo $this->Session->flash(); ?>
        <?php
        echo $this->Form->create('User', array(
            'inputDefaults' => array(
                'div' => false,
                'label' => false,
                'wrapInput' => false,
                'class' => 'form-control'
            ),
            'class' => 'well'
        ));
        ?>
        <div class="form-group">
            <?php
            echo $this->Form->input('Admin.id', array(
                'placeholder' => 'ユーザ名',
                'style' => 'width:180px;',
                'readonly'=>'readonly',
                'label' => 'ユーザ名',
            ));
            ?>  
        </div>
        <div class="form-group">
            <?php
            echo $this->Form->input('username', array(
                'placeholder' => 'ユーザ名',
                'style' => 'width:180px;',
                'label' => 'ユーザ名',
            ));
            ?>  
        </div>
        <div class="form-group">
            <?php
            echo $this->Form->input('Admin.email', array(
                'placeholder' => 'メール',
                'style' => 'width:180px;',
                'label' => 'メール'
            ));
            ?>  
        </div>
        <?php foreach ($this->request->data['IpAdmin'] as $key => $ip_address):?>
        <div class="form-group">
            <?php
            echo $this->Form->input('IpAdmin.'.$key.'.id', array(
                'placeholder' => 'メール',
                'style' => 'width:180px;',
                'label' => 'メール'
            ));
            ?>
            <?php
            echo $this->Form->input('IpAdmin.'.$key.'.ip_address', array(
                'placeholder' => 'IPアドレス',
                'style' => 'width:180px;',
                'label' => 'IPアドレス'
            ));
            ?>
        <?php endforeach ?>
        </div>
        <?php
        echo $this->Form->submit('セーブ', array(
            'div' => false,
            'class' => 'btn btn-default'
        ));
        ?>  

<?php echo $this->Form->end(); ?>  

    </div>  
</div>  


