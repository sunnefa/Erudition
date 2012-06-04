<?php
/**
 * An object representing a single lesson
 * This class only has retrieve methods
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Lesson {
    
    /**
     * The id of the lesson
     * @var int 
     */
    public $lesson_id;
    
    /**
     * The title of the lesson
     * @var int 
     */
    public $lesson_title;
    
    /**
     * A path to the downloadable lesson files
     * @var string 
     */
    public $lesson_material;
    
    /**
     * The id of the preceeding lesson
     * @var int 
     */
    public $previous_lesson;
    
    /**
     * The id of the lesson quiz
     * @var int 
     */
    public $lesson_quiz;
    
    /**
     * The number of chapters in this lesson
     * @var int 
     */
    public $lesson_num_chapters;
    
    /**
     * A description of the lesson
     * @var string
     */
    public $lesson_description;
    
    /**
     * Reference to DBWrapper
     * @var type 
     */
    protected $db_wrapper;
    
    /**
     * Constructor, accepts DBWrapper and optional lesson id
     * @param DBWrapper $db_wrapper
     * @param type $lesson_id 
     */
    public function __construct(DBWrapper $db_wrapper, $lesson_id = 0) {
        $this->db_wrapper = $db_wrapper;
        
        if($lesson_id) {
            $this->select_lesson($lesson_id);
        }
    }
    
    /**
     * Selects a single lesson from the database
     * @param int $lesson_id 
     */
    private function select_lesson($lesson_id) {
        $lesson = $this->db_wrapper->select_data('erudition__lessons', array(
            'lesson_id',
            'lesson_material',
            'lesson_quiz',
            'lesson_title',
            'previous_lesson',
            'lesson_description',
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
            $this->lesson_description = $lesson['lesson_description'];
        } else {
            echo 'No lesson found';
        }
    }
    
    /**
     * Selects multiple lessons from the database, either all or the specified number
     * @param mixrd string/int $number
     * @return array 
     */
    public function select_multiple_lessons($number = 'all') {
        if(is_numeric($number)) $limit = $number;
        else $limit = null;
        
        $lessons = $this->db_wrapper->select_data('erudition__lessons', array(
            'lesson_id',
            'lesson_material',
            'lesson_quiz',
            'lesson_title',
            'previous_lesson',
            'lesson_description',
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
