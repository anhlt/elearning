<div class="row">
    <?php echo $this->element('admin_menus'); ?>
    <div class="col-xs-13 col-md-9"> 
        <h2>ドキュメントの管理</h2>
        <?php echo $this->Session->flash(); ?>
        <?php
if(isset($datas)){
//if($data != NULL){
        ?>

        <table class="table">
            <tr>
                <td  class="col-sm-1">順番</td>
                <td  class="col-sm-1">タイトル</td>
                <td  class="col-sm-1">ファイル名</td>						
                <td  class="col-sm-1">違犯報告</td>
                <td  class="col-sm-1">禁止</td>	
                <td  class="col-sm-1">ファイル削除</td>	
            </tr>

        	
        <?php
        $i = 1;
        foreach ($datas as $data) {
            echo "<tr>";
                echo "<td class = 'col-sm-1'>".$i++."</td>";
                echo "<td class = 'col-sm-1'>".$data['documents']['title']."</td>";
                echo "<td class = 'col-sm-1'>".$this->html->link($data['documents']['link'],array('controller'=>'admins','action'=>'see_document',$data['documents']['id']))."</td>";
                echo "<td class = 'col-sm-1'>".$data['0']['count']."回　".$this->html->link('詳しく',array('controller'=>'admins','action'=>'see_violate_document',$data['documents']['id']))."</td>";
                if($data['documents']['baned'] == 0){
                    echo "<td class = 'col-sm-1'>".$this->html->link('禁止',array('controller'=>'admins','action'=>'ban_document',$data['documents']['id']))."</td>";
                }else{
                    echo "<td class = 'col-sm-1'>".$this->html->link('禁止の削除',array('controller'=>'admins','action'=>'delete_ban_document',$data['documents']['id']))."</td>";
                }
                echo "<td class = 'col-sm-1'>".$this->html->link('削除',array('controller'=>'admins','action'=>'delete_document',$data['documents']['id']))."</td>";
            echo "</tr>";
        }
        ?>
        </table>	    
    </div>
<?php
}
?>

</div>