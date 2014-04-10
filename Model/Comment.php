<?php
class Comment extends AppModel {
	public $useTable = 'comment';

	public $belongsTo = array(
        'Lesson','User'
    );
}

?>
