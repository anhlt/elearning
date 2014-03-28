<?php
class LessonMembership extends AppModel {
	public $useTable = "students_lessons";
    public $belongsTo = array(
        'Student', 'Lesson'
    );
}
