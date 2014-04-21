<div class="row">    
    <?php echo $this->element('menu'); ?>

    <div class="col-xs-13 col-md-9">
        <h2>課金</h2>  
        <?php echo $this->Session->flash(); ?>
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
                 'option'=> $months,                
                'onchange' => "this.form.submit()",
                
            ));
            ?>              

        </div>

        <div class="form-group">
            <?php
            echo $this->Form->input('year', array(
                'style' => 'width:80px;',
                'label' => '年',
                'options' => $years,
                'onchange' => "this.form.submit()",
                
            ));
            ?>       
        </div>

        <div class="form-group">
            <?php
            echo $this->Form->input('choose', array(
                'style' => 'width:80px;',
                'options' => array('先生', '学生'),
                'onchange' => "this.form.submit()",
                'value' => '先生',
                'label' => '対象',
            ));
            ?>                 
        </div>



        <?php echo $this->Form->end(); ?>  

    </div>  
</div>  


<div class="col-xs-13 col-md-9">
    <h2>学生課金</h2>  
    <?php echo $this->Session->flash(); ?>

    <div class="well">

        <table class="table table-condensed">
            <tr>
                <td  class="col-sm-3">Student_id</td>
                <td  class="col-sm-3"> 登録したコマ数</td>
                <td  class="col-sm-3">学費</td>
            </tr>


            <?php
            
            if (isset($student_list)) {
                foreach ($student_list as $student) {
                    echo "<tr>";
                    echo "<td>" . ($student['Student']['id']) . "</td>";
                    echo "<td>" . ($counts[$student['Student']['id']]) . "</td>";
                    echo "<td>" . ($counts[$student['Student']['id']] * 20000) . "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>
        
       
        <?php
        echo "<br><br>";
        echo $month;
        echo $this->html->link('TSVを作成する', array('controller' => "admins", 'action' => "generate_tsv",$year,$month
        ));
        ?></td>

    </div>
</div>

