<?php 
class Tag extends AppModel {
	public $hasAndBelongsToMany  = "Lesson";
	 // public $validate = array(
  //       'name' => array(
  //           'lenght' => array(
  //               'rule'    => array('minLength', '2'),
  //               'message' => 'Minimum 2 characters long'
  //           ),
  //       )
  //   );
}