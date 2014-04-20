<?php echo $this->html->link('へ戻る',array('controller'=>'admins','action'=>'manage_document')); ?>
<div class="col-xs-13 col-md-9"> 
<?php echo $this->Session->flash();    ?>    
<?php
if(isset($datas)){
?>
<h2>「<?php echo $datas['0']['Violate']['document_id'];?>」ドキュメントの違犯報告</h2>


<table class="table table-striped">
    <tr>
        <td>順番</td>
        <td>ユーザID</td>
        <td>内容</td>
        <td>時間</td>
        <td>報告削除</td>
    </tr>   
<?php  
$i=1;
foreach($datas as $data){
    echo "<tr>";
        echo "<td>".$i++."</td>";
        echo "<td>".$this->html->link('見る　',array('controller'=>'admins','action'=>'view_infor_student',$data['Violate']['student_id'])).$data['Violate']['student_id']."</td>";
        echo "<td>".$data['Violate']['content']."</td>";
        echo "<td>".$data['Violate']['time']."</td>";
        echo "<td>".$this->html->link('削除',array('controller'=>'admins','action'=>'delete_violate',$data['Violate']['document_id'],$data['Violate']['id']))."</td>";
    echo "</tr>";
}
?>
</table>
<?php
}
?>
</div>


</div>