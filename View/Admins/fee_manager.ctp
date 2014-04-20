<div class="row">    
    <?php echo $this->element('menu'); ?>

    <div class="col-xs-13 col-md-9">
        <h2>TSVファイルの管理</h2>  

        <?php
        echo $this->Form->create('Fee', array(
            //'controller' => "admins", 
            array('action' => "fee_manager"),
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
            echo $this->Form->input('month', array(
                'style' => 'width:120px;',
                'label' => '月',
                // 'type' => 'month',
                'option' => $months,
                // 'onchange' => "this.form.submit()",
                'value' => $month - 1,
            ));
            ?>

        </div>        

        <div class="form-group">
            <?php
            echo $this->Form->input('year', array(
                'style' => 'width:80px;',
                'label' => '年',
                'options' => $years,
                //'onchange' => "this.form.submit()",
                'value' => $year - 1970,
            ));
            ?>  

        </div>

        <div class="form-group">
            <?php
            echo $this->Form->input('choose', array(
                'style' => 'width:80px;',
                'options' => array('先生', '学生'),
                //'onchange' => "this.form.submit()",
                'label' => '対象',
            ));
            ?>                 
        </div>  
        <div class="form-group">
            <?php
            echo $this->Form->submit('リスト', array(
                'div' => false,
                'class' => 'btn btn-default'
            ));
            ?>  
        </div> 
        <?php echo $this->Form->end(); ?>  
    </div>  
</div>  



 <?php echo $this->Session->flash(); ?>
<div class="col-xs-13 col-md-9">  

    <?php
    if (!isset($object) || $object == 0) {
        ?>     
        <h2>先生課金</h2>  

        <div class="well">
            <table class="table table-condensed">
                <tr>
                    <td align="center">先生ID</td>
                    <td align="center">名前</td>                    
                    <td align="center">住所</td>
                    <td align="center">電話番号</td>
                    <td align="center">銀行口座</td>     
                    <td align="center">学生に登録された授業数</td>
                    <td align="center">課金</td>
                </tr>

                <?php
                $array = array();
                if (isset($lecturer_list)) {
                    $array = $lecturer_list;
//                    echo "<pre>";
//                    var_dump($array);
                    foreach ($lecturer_list as $lecturer) {
                       
                            echo "<tr>";
                            echo '<td align="center">' . ($lecturer['Lecturer']['id']) . "</td>";
                            echo '<td align="center">' . ($lecturer['Lecturer']["full_name"]) . "</td>";
                            echo '<td align="center">' . ($lecturer['Lecturer']["address"]) . "</td>";
                            $phone_number = $lecturer['Lecturer']["phone_number"];
//                            $phone_number = substr_replace($phone_number, '-', 3, 0);
//                            $phone_number = substr_replace($phone_number, '-', 7, 0);
                            $credit_number = $lecturer['Lecturer']["credit_card_number"];
//                            $credit_number = substr_replace($credit_number, '-', 3, 0);
//                            $credit_number = substr_replace($credit_number, '-', 8, 0);
//                            $credit_number = substr_replace($credit_number, '-', 13, 0);
//                            $credit_number = substr_replace($credit_number, '-', 18, 0);
//                            $credit_number = substr_replace($credit_number, '-', 23, 0);
                            echo '<td align="center">' . $phone_number . "</td>";
                            echo '<td align="center">' . $credit_number . "</td>";

                            echo '<td align="center">' . ($lecturer['count']) . "</td>";
                            echo '<td align="center">' . ($lecturer['count'] * $lesson_cost) * $lecturer_money_percent . "</td>";

                            echo "</tr>";
                       
                    }
                }
            } else {
                if (isset($student_list)) {
                    ?>

                    <h2>学生課金</h2>  
                   

                    <div class="well">

                        <table class="table table-condensed">
                            <tr>
                                <td align="center">学生ID</td>
                                <td align="center">名前</td>                       
                                <td align="center">住所ID</td>
                                <td align="center">電話番号ID</td>
                                <td align="center">クレジットカード番号</td>
                                <td align="center">登録した授業数</td>
                                <td align="center">課金</td>

                            </tr>

                            <?php
                            foreach ($student_list as $student) {
                                
                                    echo "<tr>";
                                    echo '<td align="center">' . ($student['Student']['id']) . "</td>";
                                    echo '<td align="center">' . ($student['Student']['full_name']) . "</td>";

                                    echo '<td align="center">' . ($student['Student']['address']) . "</td>";

                                    $phone_number = $student['Student']["phone_number"];
//                                    $phone_number = substr_replace($phone_number, '-', 3, 0);
//                                    $phone_number = substr_replace($phone_number, '-', 7, 0);

                                    $credit_number = $student['Student']["credit_card_number"];
                                    $credit_number = substr_replace($credit_number, '-', 3, 0);
                                    $credit_number = substr_replace($credit_number, '-', 8, 0);
                                    $credit_number = substr_replace($credit_number, '-', 13, 0);
                                    $credit_number = substr_replace($credit_number, '-', 18, 0);
                                    $credit_number = substr_replace($credit_number, '-', 23, 0);

                                    echo '<td align="center">' . $phone_number . "</td>";
                                    echo '<td align="center">' . $credit_number . "</td>";
                                    echo '<td align="center">' . ($student['count']) . "</td>";
                                    echo '<td align="center">' . ($student['count'] * $lesson_cost) . "</td>";
                                    echo "</tr>";
                                
                            }
                        }
                    }
                    ?>
                </table>


                <?php
                echo "<br>";
                if (!$exit && (!isset($checkyearover) || !$checkyearover)) {
                    echo $this->html->link($year . "年" . $month . '月のTSVを作成しませんか?', array('controller' => "admins", 'action' => "generate_tsv", $year, $month
                    ));
                }
                ?> 
                <?php
                echo $this->Session->flash();
                if ($exit) {
                    echo $this->html->link($year . "年" . $month . '月のTSVをdownload?', array('controller' => "admins", 'action' => "downloadtsv", $year, $month));
                }
                ?>  

            </div>
    </div>
