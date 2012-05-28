<?php
if(!isset($_GET['id'])) {
    echo 'No topic selected';
} else {
    $topic = new Topic($sql, $_GET['id']);
    $topic->load_topic_posts();
    $post_list = '';
    foreach($topic->topic_posts as $post) {
        ob_start();
        include ROOT . 'templates/forum/post_list.html';
        $post_title = ($post['post_title'] != NULL) ? $post['post_title'] : $topic->topic_title;
        $user = new User($sql, $post['created_by']);
        $post_list .= replace_tokens(ob_get_clean(), array('POST_TITLE' => $post_title, 'POST_CONTENT' => $post['post_content'], 'POST_ID' => $post['post_id'], 'POST_DATE' => $post['post_date'], 'USER_NAME' => $user->user_first_name . ' ' . $user->user_last_name, 'USER_IMAGE' => $user->user_image));
    }
    $section = new Section($sql, $topic->topic_section);
    ob_start();
    include ROOT . 'templates/forum/single_topic.html';
    $breadcrumbs = <<<EOT
    <p class="breadcrumbs">
        <a href="community/">Community</a> &GT; <a href="community/section/$section->section_id/">$section->section_title</a> &GT; $topic->topic_title
    </p>
EOT;
    echo replace_tokens(ob_get_clean(), array('TOPIC_TITLE' => $topic->topic_title, 'POST_LIST' => $post_list, 'TOPIC_ID' => $topic->topic_id, 'BREADCRUMBS' => $breadcrumbs));
}
?>