<?php
class Test extends AppModel {    
    //public $belongsTo = array('Lesson');
    public $hasMany = array(
                'Result' => array('dependent' => True ));
    
	public $validate = array(
		'title' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'タイトルを入力してお願い'
			)
		),
		'link' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'ファイルがない'
			),
			'extension' => array(
				"rule" => array(
	            	'extension', array('tsv')
	        		),
				"message" => "ファイルのフォーマットが正しくない",
			),
		),
		'test_time' => array(
			'required' => array(
				'rule'    => 'numeric',
				'message' => '数字を入力してお願い'
			),
			'time' => array(
				'rule'    => array('comparison', '>=', 0),
				'message' => 'ゼロよりも大きい時間'
			)
		),		
	);
}
?>
