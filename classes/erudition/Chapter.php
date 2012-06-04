<?php
/**
 * Represents a single chapter
 * This class only has retrive methods
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Chapter {
    /**
     * The id of the loaded chapter
     * @var int 
     */
    public $chapter_id;
    
    /**
     * The title of the chapter
     * @var string
     */
    public $chapter_title;
    
    /**
     * The path to the chapter's video
     * @var string 
     */
    public $chapter_video;
    
    /**
     * A transcript of the chapters video
     * @var string
     */
    public $chapter_transcript;
    
    /**
     * The id of the lesson the chapter belongs to
     * @var int 
     */
    public $chapter_lesson;
    
    /**
     * The id of the chapter preceeding this chapter
     * @var int
     */
    public $previous_chapter;
    
    /**
     * A reference to DBWrapper
     * @var DBWrapper 
     */
    protected $db_wrapper;
    
    /**
     * Constructor, accepts DBWrapper and optional chapter id
     * @param DBWrapper $db_wrapper
     * @param int $chapter_id 
     */
    public function __construct(DBWrapper $db_wrapper, $chapter_id = 0) {
        $this->db_wrapper = $db_wrapper;
        
        if($chapter_id) {
            $this->select_chapter($chapter_id);
        }
    }
    
    /**
     * Selects a single chapter from the database
     * @param int $chapter_id 
     */
    private function select_chapter($chapter_id) {
        $chapter = $this->db_wrapper->select_data('erudition__chapters', '*', 'chapter_id = ' . $chapter_id);
        
        if($chapter) {
            $chapter = array_flat($chapter);
            $this->chapter_id = $chapter['chapter_id'];
            $this->chapter_title = $chapter['chapter_title'];
            $this->chapter_lesson = $chapter['chapter_lesson'];
            $this->chapter_transcript = $chapter['chapter_transcript'];
            $this->chapter_video = $chapter['chapter_video'];
            $this->previous_chapter = $chapter['previous_chapter'];
        } else {
            echo 'No chapter found';
        }
    }
    
    /**
     * Selects many chapters from the database, based on their lesson id
     * @param int $lesson_id
     * @return array 
     */
    public function select_multiple_chapters($lesson_id) {
        $chapters = $this->db_wrapper->select_data('erudition__chapters', '*', 'chapter_lesson = ' . $lesson_id);
        if($chapters) return $chapters;
        else echo 'No chapters found';
    }
}

?>
