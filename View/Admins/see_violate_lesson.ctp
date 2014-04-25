
<div class="row">
  
    <?php echo $this->html->link('へ戻る',array('controller'=>'admins','action'=>'manage_lesson')); ?>
    
    <?php echo $this->element('admin_menus'); ?>
    <div class="col-xs-13 col-md-9">
        <div class="row">
            <h2>授業の詳しい情報</h2> 
            <h3>授業</h3>
            <table>    
                <tr>
                    <td>授業ID  </td>
                    <td><?php echo $lesson[0]['lessons']['id']; ?></td>
                </tr>  
                <tr>
                    <td>タイトル    </td>
                    <td><?php echo $lesson[0]['lessons']['name']; ?></td>
                </tr> 

                <tr>
                    <td>説明    </td>
                    <td><?php echo $lesson[0]['lessons']['summary']; ?></td>
                </tr> 
                <tr>
                    <td>作成時間    </td>
                    <td><?php echo $lesson[0]['lessons']['update_date']; ?></td>
                </tr> 
                <tr>
                    <td>授業時間    </td>
                    <td><?php echo $lesson[0]['lessons']['lesson_time']; ?></td>
                </tr> 
                <tr>
                    <td>状態    </td>
                    <td><?php if ($lesson[0]['lessons']['baned'] == 1) echo "禁止された";
    else echo "有効" ?></td>
                </tr> 
            </table>
            <h3>先生</h3>
            <table>  
                <tr>
                    <td>先生ID  </td>
                    <td><?php echo $lesson[0]['lecturers']['id']; ?></td>
                </tr> 
                <tr>
                    <td>ユーザ名    </td>
                    <td><?php echo $lesson[0]['users']['username']; ?></td>
                </tr> 
                <tr>
                    <td>氏名    </td>
                    <td><?php echo $lesson[0]['lecturers']['full_name']; ?></td>
                </tr> 
            </table>  
                <h3>違犯報告</h3>
                
                <table class="table">
                    <tr>
                        <td  class="col-sm-1">順番</td>
                        <td  class="col-sm-1">報告者</td>                      
                        <td  class="col-sm-1">内容</td>
                        <td  class="col-sm-1">時間</td>    
                        <td  class="col-sm-1">削除</td>
                    </tr>
                    <?php
                    $i = 1;
                    foreach ($ihan as $ihan) {
                        echo "<tr>";
                            echo "<td  class='col-sm-1'>".$i++."</td>";
                            echo "<td  class='col-sm-1'>".$ihan["ihans"]["lecturer_id"]."</td>";                      
                            echo "<td  class='col-sm-1'>".$ihan["ihans"]["content"]."</td>";
                            echo "<td  class='col-sm-1'>".$ihan["ihans"]["time"]."</td>";  
                            echo "<td>".$this->html->link('削除',array('controller'=>'admins','action'=>'delete_violate_lesson',$ihan['ihans']['id'],$ihan['ihans']['lesson_id']))."</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
        </div>    
    </div>
</div>
