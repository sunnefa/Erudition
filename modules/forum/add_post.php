<?php
$user_id = (isset($_SESSION['user'])) ? $_SESSION['user'] : $_COOKIE['user'];

//if there is a topic id posted, we want to add a post to that topic id
if(isset($_POST['topic_id'])) {
    $topic = new Topic($sql, $_POST['topic_id']);


    $added_post = $topic->add_post($user_id, $_POST['post_title'], $_POST['post_content'], date('Y-m-d H:i:s'));

    if($added_post) {
        
    } else {
        echo 'Could not add post';
    }
//if there is no topic id posted we want to create a new topic
} elseif(isset($_POST['section_id'])) {
    $topic = new Topic($sql);
    
    $added_topic = $topic->add_topic($user_id, $_POST['section_id'], $_POST['post_title'], date('Y-m-d H:i:s'));
    if($added_topic) {
        $added_post = $topic->add_post($user_id, $_POST['post_title'], $_POST['post_content'], date('Y-m-d H:i:s'), $added_topic);
    }
}
$topic_id = (isset($_POST['topic_id'])) ? $_POST['topic_id'] : $added_topic;
$page_number = (isset($topic->total_posts)) ? ceil($topic->total_posts / 15) : 1;

reload('community/topic/' . $topic_id . '/page-' . $page_number . '/#post-' . $added_post);
?>