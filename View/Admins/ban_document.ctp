<div class = 'col-xs-10 col-md-9 well'>
    <h1>先生に報告を送る</h1>
    <div class="col-xs-13 col-md-9">
        <?php
        echo $this->Form->create('Message', array(
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
            echo $this->Form->textarea("Message.content", array(
                "rows"=>10, 
                "cols"=>80, 
                'label' => '内容',
                "placeholder"=>"内容")) ;
            ?>  
        </div>
        <?php echo $this->Form->submit('送る', array(  
	'div' => false,  
	'class' => 'btn btn-default'  
	)); ?>  
<?php echo $this->Form->end(); ?>  
    </div>
</div>
