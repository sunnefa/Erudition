<?php
if(!isset($_GET['id'])) {
    echo 'No topic selected';
} else {
    $topic = new Topic($sql, $_GET['id']);
    $topic->load_topic_posts();
    $post_list = '';
    $posts = array_chunk($topic->topic_posts, 15);
    $all_p = count($posts);
    $p = (isset($_GET['p'])) ? $_GET['p']-1 : 0;
    foreach($posts[$p] as $post) {
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
    $pagination = "";
    for($i = 1; $i < $all_p+1; $i++) {
        if($i == $p+1) {
            $pagination .= <<<EOT
            <span class="pagination">$i</span>
EOT;
        } else {
            $pagination .= <<<EOT
        <a class="pagination" href="community/topic/$topic->topic_id/page-$i/">$i</a>
EOT;
        }
    }
    ob_start();
    include ROOT . 'templates/forum/new_post_form.html';
    $post_form_html = replace_tokens(ob_get_clean(), array('ID_TO_SEND' => $topic->topic_id, 'NAME_OF_ID' => 'topic_id', 'NEW_POST_HEADLINE' => 'Reply to ' . $topic->topic_title));
    echo replace_tokens(ob_get_clean(), array('TOPIC_TITLE' => $topic->topic_title, 'POST_LIST' => $post_list, 'NEW_POST_FORM' => $post_form_html, 'BREADCRUMBS' => $breadcrumbs, 'PAGINATION' => $pagination));
}
?>