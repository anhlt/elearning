<div class="row">


    <?php echo $this->element('admin_menus'); ?>

    <div class="col-xs-13 col-md-9">
        <h2>管理者の管理</h2>
        <?php echo $this->Session->flash(); ?>
        <div class="well">             
            <br>   
            <?php echo $this->html->link('管理者の追加', array('controller' => "admins", 'action' => "add_admin"), array('class' => 'btn btn-info')); ?></br> 
            <br>

            <?php
            echo $this->Paginator->pagination(array(
                'ul' => 'pagination'
            ));
            ?>
            <table class="table table-condensed">
                <tr>
                    <td  class="col-sm-1"><?php echo $this->Paginator->sort('id'); ?></td>
                    <td  class="col-sm-1"><?php echo $this->Paginator->sort('Name'); ?></td>
                    <td  class="col-sm-3">登録情報の更新</td>
                    <td  class="col-sm-3">状態</td>
                    <td  class="col-sm-3">削除</td>
                </tr>
<?php foreach ($res as $result) { ?>
                    <tr>
                        <td><?php echo($result['User']['id']) ?> </td>
                        <td><?php echo($result['User']['username']) ?> </td>
                        <td><?php echo $this->html->link('更新', array('controller' => "admins", 'action' => "edit_admin_process", $result['User']["id"]), array('class' => 'btn btn-info')); ?></td>
                        <td><?php 
            if ($result['User']['status']==true){
                echo "On";
            }else echo "Off";
                            ?> </td>
                        <td><?php  if($flag != $result['User']['id']) echo $this->html->link('削除', array('controller' => "admins", 'action' => "remove_admin_process", $result['User']["id"]), array('class' => 'btn btn-danger')); ?></td>
                        
                    </tr>
<?php } ?>
            </table>
        </div>
    </div>
</div>
