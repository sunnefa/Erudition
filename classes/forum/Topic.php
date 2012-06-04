<?php
/**
 * Represents a single topic in the forum
 * This object also has all posts in the forum
 * Currently has create and retrieve methods only
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Topic {
    /**
     * The id of the topic
     * @var int 
     */
    public $topic_id;
    
    /**
     * The title of the topic
     * @var string 
     */
    public $topic_title;
    
    /**
     * The forum section the topic belongs to
     * @var int
     */
    public $topic_section;
    
    /**
     * The user who started the topic
     * @var int
     */
    public $topic_started_by;
    
    /**
     * The date the topic was started
     * @var string
     */
    public $topic_date;
    
    /**
     * An array of all the posts in this topic
     * @var array
     */
    public $topic_posts;
    
    /**
     * The total number of posts in the topic
     * @var int
     */
    public $total_posts;
    
    /**
     * A reference to DbWrapper
     * @var type 
     */
    protected $db_wrapper;

    /**
     * Construct, accepts DBWrapper and topic id
     * @param DBWrapper $db_wrapper
     * @param int $topic_id 
     */
    public function __construct(DBWrapper $db_wrapper, $topic_id = 0) {
        $this->db_wrapper = $db_wrapper;
        
        if($topic_id) {
            $this->select_topic($topic_id);
        }
    }
    
    /**
     * Select a single topic
     * @param int $id 
     */
    private function select_topic($id) {
        $topic = $this->db_wrapper->select_data('forum__topics', array(
            'topic_id',
            'topic_title',
            'topic_date',
            'topic_section',
            'topic_started_by',
            '(SELECT COUNT(forum__posts.post_id) FROM forum__posts WHERE forum__posts.topic_id = forum__topics.topic_id) AS total_posts'
        ), 'topic_id = ' . $id);
        
        if($topic) {
            $topic = array_flat($topic);
            $this->topic_date = $topic['topic_date'];
            $this->topic_id = $topic['topic_id'];
            $this->topic_section = $topic['topic_section'];
            $this->topic_started_by = $topic['topic_started_by'];
            $this->topic_title = $topic['topic_title'];
            $this->total_posts = $topic['total_posts'];
        } else {
            echo 'No topic found';
        }
    }
    
    /**
     * Selects multiple topics, either all or only the ones in a particular section
     * @param int $section
     * @return boolean 
     */
    public function load_multiple_topics($section = null) {
        $joins = $this->db_wrapper->build_joins('LEFT', array('users__users','u'), array('user_id', 't.topic_started_by'));
        $where = ($section != null) ? 'topic_section = ' . $section : '';
        $topics = $this->db_wrapper->select_data(array('forum__topics','t'), array(
            't.topic_id',
            't.topic_title',
            't.topic_started_by',
            't.topic_date',
            't.topic_section',
            '(SELECT COUNT(p.post_id) FROM forum__posts AS p WHERE t.topic_id = p.topic_id)  AS total_posts',
            "CONCAT(u.user_first_name, ' ', u.user_last_name) AS user_name"
        ), $where, null, null, 't.topic_id', $joins);
        if($topics) {
            foreach($topics as $key => $topic) {
                $topics[$key]['latest_post'] = $this->load_latest_post($topic['topic_id']);
            }
            return $topics;
        }
        else return false;
    }
    
    /**
     * Loads the latest post in a topic
     * @param int $id
     * @return array
     */
    private function load_latest_post($id) {
        $joins = $this->db_wrapper->build_joins('INNER', array('users__users', 'u'), array('user_id', 'p.created_by'));
        $post = $this->db_wrapper->select_data(array('forum__posts', 'p'), array(
            'p.post_date',
            "CONCAT(u.user_first_name, ' ', u.user_last_name) AS username",
            'p.post_id'
        ), 'p.topic_id = ' . $id, 1, 'p.post_date DESC', null, $joins);
        if(is_array($post)) $post = array_flat($post);
        return $post;
    }
    
    /**
     * Loads all the posts in the current topic 
     */
    public function load_topic_posts() {
        $posts = $this->db_wrapper->select_data('forum__posts', '*', 'topic_id = ' . $this->topic_id);
        if($posts) {
            $this->topic_posts = $posts;
        } else {
            echo 'No posts found';
        }
    }
    
    /**
     * Adds a post to the current topic
     * @param int $user_id
     * @param string $post_title
     * @param string $post_content
     * @param string $date
     * @param int $topic_id
     * @return boolean 
     */
    public function add_post($user_id, $post_title, $post_content, $date, $topic_id = 0) {
        if($topic_id == 0) $topic_id = $this->topic_id;
        $added = $this->db_wrapper->insert_data('forum__posts', array(
            'post_title' => $post_title,
            'post_content' => $post_content,
            'post_date' => $date,
            'created_by' => $user_id,
            'topic_id' => $topic_id
        ));
        if($added) return $this->db_wrapper->get_insert_id();
        else return false;
    } 
    
    /**
     * Adds a new topic to the database
     * @param int $user_id
     * @param int $section_id
     * @param string $topic_title
     * @param string $date
     * @return boolean 
     */
    public function add_topic($user_id, $section_id, $topic_title, $date) {
        $added = $this->db_wrapper->insert_data('forum__topics', array(
            'topic_title' => $topic_title,
            'topic_started_by' => $user_id,
            'topic_section' => $section_id,
            'topic_date' => $date
        ));
        if($added) return $this->db_wrapper->get_insert_id();
        else return false;
    }
}

?>
