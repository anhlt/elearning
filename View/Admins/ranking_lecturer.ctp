
<div class="row">    
    <?php echo $this->element('menu'); ?>



    <div class="col-xs-13 col-md-9">
        <h2>ランキング</h2>  
        <?php echo $this->Session->flash(); ?>
        
        <?php
        echo $this->Form->create('ranking', array(
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
            echo $this->Form->input('choose_ranking', array(
                'style' => 'width:180px;',
                'label' => 'ランキングタイプ',
                'options' => array('lesson','lecturer'),
                'onchange' => "this.form.submit()"
            ));
            ?>       
        </div>        
        
        <?php echo $this->Form->end(); ?>          
        

        <div class="well">

            <table class="table table-condensed">
                <tr>
                    
                    <td  class col-sm-3 >
                    <?php if(!isset($ranking)||$ranking == 0 )echo "Lesson_id </td>" ;
                    else echo "Lecturer_id </td>";
                    ?>
                    <td  class="col-sm-3">NumberOflikes</td>
                            
                            
                </tr>

                <option onselect="test(this);"></option> 
                
                <?php
                
                if(!isset($ranking)||$ranking == 0 )
                    {
                if (isset($lessons)) {
                    foreach ($lessons as $lesson) {
                        echo "<tr>";
                        echo "<td>" . $lesson['id'] . "</td>";
                        echo "<td>" . $lesson['count_like'] . "</td>";
                        echo "</tr>";
                    }
                }   
                    }
                else
                {
                if (isset($teachers)) {
                    foreach ($teachers as $teacher) {
                        echo "<tr>";
                        echo "<td>" . $teacher['id'] . "</td>";
                        echo "<td>" . $teacher['count_like'] . "</td>";
                        echo "</tr>";
                    }
                }
                }
                ?>
            </table>
        </div>
    </div>

