<?php
$topic = new Topic($sql, $_POST['topic_id']);

$user_id = (isset($_SESSION['user'])) ? $_SESSION['user'] : $_COOKIE['user'];

$added_post = $topic->add_post($user_id, $_POST['post_title'], $_POST['post_content'], date('Y-m-d H:i:s'));

if($added_post) {
    reload('community/topic/' . $_POST['topic_id'] . '/');
} else {
    echo 'Could not add post';
}
?>