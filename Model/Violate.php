
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Violate
 *
 * @author Tha
 */
class Violate extends AppModel {
    //put your code 
    public $belongsTo = array(
        'Document' => array(
            'className' => 'Document',            
    ));
}

?>

