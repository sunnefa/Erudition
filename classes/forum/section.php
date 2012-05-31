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
    public $latest_post;
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
            $this->latest_post = $this->load_latest_post($section['section_id']);
        }
        else echo 'No section found';
    }
    
    private function load_latest_post($section_id) {
        $topics_sections_join = $this->db_wrapper->build_joins('INNER', array('forum__topics', 't'), array('t.topic_section', 's.section_id'));
        $posts_topics_join = $this->db_wrapper->build_joins('INNER', array('forum__posts', 'p'), array('p.topic_id', 't.topic_id'));
        $users_posts_join = $this->db_wrapper->build_joins('INNER', array('users__users', 'u'), array('u.user_id', 'p.created_by'));
        $post = $this->db_wrapper->select_data(array('forum__sections', 's'), array(
            'p.post_date',
            "CONCAT(u.user_first_name, ' ', u.user_last_name) AS username",
            't.topic_id',
            't.topic_title',
            'p.post_id',
            '(SELECT COUNT(a.post_id) FROM forum__posts AS a WHERE a.topic_id = t.topic_id) AS total_posts'
        ), 's.section_id = ' . $section_id, 1, 'p.post_date DESC', 'p.post_id', $topics_sections_join . ' ' . $posts_topics_join . ' ' . $users_posts_join);
        if(is_array($post)) $post = array_flat($post);
        
        return $post;
    }
    
    public function select_multiple_sections() {
        $sections = $this->db_wrapper->select_data('forum__sections', array(
            'section_id',
            'section_title',
            'section_description',
            '(SELECT COUNT(forum__topics.topic_id) FROM forum__topics WHERE forum__topics.topic_section = forum__sections.section_id) AS num_topics'
        ));
        
        if($sections) {
            foreach($sections as $key => $section) {
                $sections[$key]['latest_post'] = $this->load_latest_post($section['section_id']);
            }
            return $sections;
        }
        else return false;
    }
}

?>
