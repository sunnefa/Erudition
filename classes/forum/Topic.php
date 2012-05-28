<?php

/*
 * This is Topic.php
 * Created on 28.5.2012
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */

/**
 * Description of Topic
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Topic {
    
    public $topic_id;
    public $topic_title;
    public $topic_section;
    public $topic_started_by;
    public $topic_date;
    public $topic_posts;
    
    protected $db_wrapper;


    public function __construct(DBWrapper $db_wrapper, $topic_id = 0) {
        $this->db_wrapper = $db_wrapper;
        
        if($topic_id) {
            $this->select_topic($topic_id);
        }
    }
    
    private function select_topic($id) {
        $topic = $this->db_wrapper->select_data('forum__topics', '*', 'topic_id = ' . $id);
        
        if($topic) {
            $topic = array_flat($topic);
            $this->topic_date = $topic['topic_date'];
            $this->topic_id = $topic['topic_id'];
            $this->topic_section = $topic['topic_section'];
            $this->topic_started_by = $topic['topic_started_by'];
            $this->topic_title = $topic['topic_title'];
        } else {
            echo 'No topic found';
        }
    }
    
    public function load_multiple_topics($section = null) {
        $joins = $this->db_wrapper->build_joins('LEFT', array('users__users','u'), array('user_id', 't.topic_started_by'));
        $where = ($section != null) ? 'topic_section = ' . $section : '';
        $topics = $this->db_wrapper->select_data(array('forum__topics','t'), array(
            't.topic_id',
            't.topic_title',
            't.topic_started_by',
            't.topic_date',
            't.topic_section',
            "CONCAT(u.user_first_name, ' ', u.user_last_name) AS user_name"
        ), $where, null, null, 't.topic_id', $joins);
        if($topics) return $topics;
        else return false;
    }
    
    public function load_topic_posts() {
        $posts = $this->db_wrapper->select_data('forum__posts', '*', 'topic_id = ' . $this->topic_id);
        if($posts) {
            $this->topic_posts = $posts;
        } else {
            echo 'No posts found';
        }
    }
    
    public function add_post($user_id, $post_title, $post_content, $date, $topic_id = 0) {
        if($topic_id == 0) $topic_id = $this->topic_id;
        $added = $this->db_wrapper->insert_data('forum__posts', array(
            'post_title' => $post_title,
            'post_content' => $post_content,
            'post_date' => $date,
            'created_by' => $user_id,
            'topic_id' => $topic_id
        ));
        if($added) return true;
        else return false;
    } 
}

?>
