<div class="row">    
 <?php echo $this->element('admin_menus');?>

<div class="col-xs-13 col-md-9">
    <h2>課金のTSVを管理する</h2>  

    <div class="well">         
       
        <?php 
        
        echo $this->html->link($year.'年'.$month.'月のTSVを作成しませんか', array('controller' => "admins", 'action' => "generate_tsv",$year,$month));
        
        ?>
    </div>
</div>

