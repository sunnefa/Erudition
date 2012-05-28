<?php

/*
 * This is section.php
 * Created on 28.5.2012
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */

/**
 * Description of section
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Section {
    public $section_id;
    public $section_title;
    public $section_description;
    protected $db_wrapper;
    
    public function __construct(DBWrapper $db_wrapper, $section_id = 0) {
        $this->db_wrapper = $db_wrapper;
        
        if($section_id) {
            $this->select_section($section_id);
        }
    }
    
    private function select_section($id) {
        $section = $this->db_wrapper->select_data('forum__sections', '*', 'section_id = ' . $id);
        
        if($section) {
            $section = array_flat($section);
            $this->section_description = $section['section_description'];
            $this->section_id = $section['section_id'];
            $this->section_title = $section['section_title'];
        }
        else echo 'No section found';
    }
    
    public function select_multiple_sections() {
        $sections = $this->db_wrapper->select_data('forum__sections', '*');
        
        if($sections) return $sections;
        else return false;
    }
}

?>
