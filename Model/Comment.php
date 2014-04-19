<?php
class Comment extends AppModel {
	public $belongsTo = array(
        'Lesson','User'
    );
}

?>