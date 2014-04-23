<div class="row">
<?php echo $this->element('admin_menus');?>
<div class="col-xs-13 col-md-9">  
<h2>システム仕様の管理</h2>    

<?php echo $this->Session->flash();?>

<?php echo $this->Form->create('parameter',array(  
  'inputDefaults' => array(  
    'div' => false,  
    'label' => false,  
    'wrapInput' => false,  
    'class' => 'form-control',  
  ),  
  'class' => 'well form-inline',  
)); ?> 
<table class="table">
  <tr>
      <td>1回の受講料</td>
       <td><?php echo $this->Form->input('lesson_cost', array('style' => 'width:100px;', 'value' => $_LESSON_COST)); ?></td> 
       <td>万 ドン</td>
  </tr> 
  <tr>
      <td>報酬％</td>
      <td><?php echo $this->Form->input('lecturer_money_percent', array('style' => 'width:100px;', 'value' => $_LECTURER_MONEY_PERCENT)); ?></td>
      <td>％</td>
  </tr>
  <tr>
      <td>受講可能時間</td>
      <td><?php echo $this->Form->input('enable_lesson_time', array('style' => 'width:100px;', 'value' => $_ENABLE_LESSON_TIME)); ?></td>
      <td>日</td>
  </tr>
  <tr>
      <td>ログイン誤り回数</td>
      <td><?php echo $this->Form->input('wrong_password_times', array('style' => 'width:100px;', 'value' => $_WRONG_PASSWORD_TIMES)); ?></td>
      <td>回</td>
  </tr>
    <tr>
      <td>ロック時間</td>
      <td><?php echo $this->Form->input('lock_time', array('style' => 'width:100px;', 'value' => $_LOCK_TIME)); ?></td>
      <td>秒</td>
  </tr>
  <tr>
      <td>自動セション終了時間</td>
      <td><?php echo $this->Form->input('session_time', array('style' => 'width:100px;', 'value' => $_SESSION_TIME)); ?></td>
      <td>秒</td>
  </tr>
  <tr>
      <td>違犯の最大回数</td>
      <td><?php echo $this->Form->input('violations_times', array('style' => 'width:100px;', 'value' => $_VIOLATIONS_TIMES)); ?></td>
      <td>回</td>
  </tr>
  <tr>
      <td>バックアップ時刻</td>
      <td><?php echo $this->Form->input('backup_time', array('style' => 'width:100px;', 'value' => $_BACKUP_TIME)); ?></td>
      <td>時に　毎日</td>
  </tr>
  <tr>   
      <td></td>
      <td></td>
      <td><?php echo $this->Form->submit('セーブ', array( 
    'div' => false,  
    'class' => 'btn btn-default'  
  )); ?> </td>
  </tr>    
   
</div>    
<?php echo $this->Session->flash(); ?>
<?php echo $this->Form->end(); ?>
 
</div>  
