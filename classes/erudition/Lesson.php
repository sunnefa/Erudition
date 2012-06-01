<?php
/**
 * Description of Lesson
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Lesson {
    public $lesson_id;
    public $lesson_title;
    public $lesson_material;
    public $previous_lesson;
    public $lesson_quiz;
    public $lesson_num_chapters;
    
    protected $db_wrapper;
    
    public function __construct(DBWrapper $db_wrapper, $lesson_id = 0) {
        $this->db_wrapper = $db_wrapper;
        
        if($lesson_id) {
            $this->select_lesson($lesson_id);
        }
    }
    
    private function select_lesson($lesson_id) {
        $lesson = $this->db_wrapper->select_data('erudition__lessons', array(
            'lesson_id',
            'lesson_material',
            'lesson_quiz',
            'lesson_title',
            'previous_lesson',
            '(SELECT COUNT(erudition__chapters.chapter_id) FROM erudition__chapters WHERE erudition__chapters.chapter_lesson = erudition__lessons.lesson_id) AS lesson_num_chapters'
        ), 'lesson_id = ' . $lesson_id);
        if($lesson) {
            $lesson = array_flat($lesson);
            $this->lesson_id = $lesson['lesson_id'];
            $this->lesson_material = $lesson['lesson_material'];
            $this->lesson_quiz = $lesson['lesson_quiz'];
            $this->lesson_title = $lesson['lesson_title'];
            $this->previous_lesson = $lesson['previous_lesson'];
            $this->lesson_num_chapters = $lesson['lesson_num_chapters'];
        } else {
            echo 'No lesson found';
        }
    }
    
    public function select_multiple_lessons($number = 'all') {
        if(is_numeric($number)) $limit = $number;
        else $limit = null;
        
        $lessons = $this->db_wrapper->select_data('erudition__lessons', array(
            'lesson_id',
            'lesson_material',
            'lesson_quiz',
            'lesson_title',
            'previous_lesson',
            '(SELECT COUNT(erudition__chapters.chapter_id) FROM erudition__chapters WHERE erudition__chapters.chapter_lesson = erudition__lessons.lesson_id) AS lesson_num_chapters'
        ), null, $limit);
        
        if($lessons) {
            return $lessons;
        } else {
            echo 'No lessons found';
        }
    }
}

?>
