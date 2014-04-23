<?php
class LessonsTag extends AppModel {
	public $useTable = "lessons_tags";
    public $belongsTo = array(
        'Tag', 'Lesson'
    );
}
