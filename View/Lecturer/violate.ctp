<div class="row">
    <?php echo $this->Session->flash(); ?>
</div>
<div class="row">
    <div class="col-sm-3 col-md-2">
        <ul class="nav nav-pills nav-stacked">
            <li class="active"><a href="#"> レポート </a>
        </ul>
    </div>
    <div class="col-sm-9 col-md-10">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#home" data-toggle="tab"><span class="glyphicon glyphicon-inbox">
            </span>新しい</a></li>
            <li><a href="#profile" data-toggle="tab"><span class="glyphicon glyphicon-user"></span>
                古い</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane fade in active" id="home">
                <div class="list-group">
                	<?php foreach ($Messages as $message):?>
                    <?php if($message['Message']['read'] == 0):?>
                    <a href="/lecturer/violate/<?php echo $message["Message"]['id']?>" class="list-group-item">
                        <span class="glyphicon glyphicon-star-empty"></span>
                        <span class="name" style="min-width: 120px;display: inline-block;">管理者</span> 
                        <?php if($message['Message']['type'] == 'Block'):?>
                            <span class="text-muted" style="font-size: 11px;"><span class="label label-warning">Block</span></span> 
                        <?php elseif($message['Message']['type'] == 'Delete') :?>
                            <span class="text-muted" style="font-size: 11px;"><span class="label label-danger">Deleted</span></span> 
                        <?php elseif($message['Message']['type'] == 'Unblock') :?>
                            <span class="text-muted" style="font-size: 11px;"><span class="label label-success">Unblocked</span></span> 
                        <?php endif ?>    
                        <span class=""><?php echo $message['Message']['content']?></span>
                        <span
                            class="badge"><?php echo $message['Message']['time']?></span> 
                        <span class="pull-right">
                        	<span class="glyphicon glyphicon-ok"></span>
                            <span class="glyphicon glyphicon-remove"></span>
                        </span>
                    </a>
                    <?php endif?>
	                <?php endforeach ?>
                </div>
            </div>
            <div class="tab-pane fade in" id="profile">
                <div class="list-group">
                    <?php foreach ($Messages as $message):?>
                    <?php if($message['Message']['read'] == 1):?>
                    <a href="/lecturer/violate/<?php echo $message["Message"]['id']?>" class="list-group-item">
                        <span class="glyphicon glyphicon-star-empty"></span>
                        <span class="name" style="min-width: 120px;display: inline-block;">管理者</span> 
                        <?php if($message['Message']['type'] == 'Block'):?>
                            <span class="text-muted" style="font-size: 11px;"><span class="label label-warning">Block</span></span> 
                        <?php elseif($message['Message']['type'] == 'Delete') :?>
                            <span class="text-muted" style="font-size: 11px;"><span class="label label-danger">Deleted</span></span> 
                        <?php elseif($message['Message']['type'] == 'Unblock') :?>
                            <span class="text-muted" style="font-size: 11px;"><span class="label label-success">Unblocked</span></span> 
                        <?php endif ?>
                        <span class=""><?php echo $message['Message']['content']?></span>
                        <span
                            class="badge"><?php echo $message['Message']['time']?></span> 
                        <span class="pull-right">
                            <span class="glyphicon glyphicon-ok"></span>
                            <span class="glyphicon glyphicon-remove"></span>
                        </span>
                    </a>
                    <?php endif?>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
</div>
