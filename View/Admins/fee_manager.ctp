<div class="row">    
 <?php echo $this->element('admin_menus');?>

<div class="col-xs-13 col-md-9">
    <h2>課金のTSVを管理する</h2>  
    <?php  ?>

    <div class="well">         
       
        <?php 
        
        if($exit == false)
        echo $this->html->link($year.'年'.$month.'月のTSVを作成しませんか', array('controller' => "admins", 'action' => "generate_tsv",$year,$month
        ));
        else echo $this->Session->flash();
        
        ?>
    </div>
</div>

