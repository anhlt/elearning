<?php
class Document extends AppModel {
	public $name = 'document';
	public $hasMany = array(
            'Violate' => array(
                'className' => 'Violate',
                'dependent' => true,
            )
        );
        public $validate = array(
		'title' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'タイトルを入力してお願い'
			)
		),		
		'link' => array(
			'required' => array(
				"rule" => "notEmpty",
				"message" => "メールを入力してお願い",
			),
			'extension' => array(
				"rule" => array(
	            	'extension', array('pdf', 'mp4', 'jpg','mp3','wav','gif','png')
	        		),
				"message" => "ファイルのフォーマットが正しくない",
			),
		)
	);
}
?>
