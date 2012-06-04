<?php
/**
 * The controller for adding posts to the forum
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com> 
 */

//get the user id of the logged in user
$user_id = (isset($_SESSION['user'])) ? $_SESSION['user'] : $_COOKIE['user'];

//if there is a topic id posted, we want to add a post to that topic id
if(isset($_POST['topic_id'])) {
    //topic object
    $topic = new Topic($sql, $_POST['topic_id']);
    //add post
    $added_post = $topic->add_post($user_id, $_POST['post_title'], $_POST['post_content'], date('Y-m-d H:i:s'));
    //did it work?
    if($added_post) {
        
    } else {
        echo 'Could not add post';
    }
//if there is no topic id posted we want to create a new topic
} elseif(isset($_POST['section_id'])) {
    //topic object
    $topic = new Topic($sql);
    //add topic
    $added_topic = $topic->add_topic($user_id, $_POST['section_id'], $_POST['post_title'], date('Y-m-d H:i:s'));
    //if it worked we add a post
    if($added_topic) {
        $added_post = $topic->add_post($user_id, $_POST['post_title'], $_POST['post_content'], date('Y-m-d H:i:s'), $added_topic);
    }
}
//get the id of the topic that was postted to
$topic_id = (isset($_POST['topic_id'])) ? $_POST['topic_id'] : $added_topic;
//get the page number the post is supposed to be on
$page_number = (isset($topic->total_posts)) ? ceil($topic->total_posts / 15) : 1;
//redirect to the newly added post
reload('community/topic/' . $topic_id . '/page-' . $page_number . '/#post-' . $added_post);
?>