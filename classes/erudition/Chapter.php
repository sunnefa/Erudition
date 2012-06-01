<?php

/*
 * This is Chapter.php
 * Created on 31.5.2012
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */

/**
 * Description of Chapter
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Chapter {
    public $chapter_id;
    public $chapter_title;
    public $chapter_video;
    public $chapter_transcript;
    public $chapter_lesson;
    public $previous_chapter;
    
    protected $db_wrapper;
    
    public function __construct(DBWrapper $db_wrapper, $chapter_id = 0) {
        $this->db_wrapper = $db_wrapper;
        
        if($chapter_id) {
            $this->select_chapter($chapter_id);
        }
    }
    
    private function select_chapter($chapter_id) {
        $chapter = $this->db_wrapper->select_data('erudition__chapters', '*', 'chapter_id = ' . $chapter_id);
        
        if($chapter) {
            $chapter = array_flat($chapter);
            $this->chapter_id = $chapter['chapter_id'];
            $this->chapter_title = $chapter['chapter_title'];
            $this->chapter_lesson = $chapter['chapter_title'];
            $this->chapter_transcript = $chapter['chapter_transcript'];
            $this->chapter_video = $chapter['chapter_video'];
            $this->previous_chapter = $chapter['previous_chapter'];
        } else {
            echo 'No chapter found';
        }
    }
    
    public function select_multiple_chapters($number = 'all') {
        if(is_int($number)) $limit = $number;
        else $limit = null;
        
        $chapters = $this->db_wrapper->select_data('erudition__chapters', '*', null, $limit);
        if($chapters) return $chapters;
        else echo 'No chapters found';
    }
}

?>
