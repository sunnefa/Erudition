<?php
if(is_logged_in()) {
    $part = (isset($_GET['part'])) ? $_GET['part'] : 'forum';
    switch($part) {
        case 'topic':
            $topic = new Topic($sql, $_GET['id']);
            $topic->load_topic_posts();
            $post_list = '';
            foreach($topic->topic_posts as $post) {
                ob_start();
                include ROOT . 'templates/forum/post_list.html';
                $post_title = ($post['post_title'] != NULL) ? $post['post_title'] : $topic->topic_title;
                $user = new User($sql, $post['created_by']);
                $post_list .= replace_tokens(ob_get_clean(), array('POST_TITLE' => $post_title, 'POST_CONTENT' => $post['post_content'], 'POST_DATE' => $post['post_date'], 'USER_NAME' => $user->user_first_name . ' ' . $user->user_last_name, 'USER_IMAGE' => $user->user_image));
            }
            ob_start();
            include ROOT . 'templates/forum/single_topic.html';
            echo replace_tokens(ob_get_clean(), array('TOPIC_TITLE' => $topic->topic_title, 'POST_LIST' => $post_list));
            break;
        case 'section':
            if(!isset($_GET['id'])) {
                echo 'No section selected';
            } else {
                $section = new Section($sql, $_GET['id']);
                $topic_obj = new Topic($sql);
                $topics = $topic_obj->load_multiple_topics();
                $topic_list = '';
                foreach($topics as $topic) {
                    ob_start();
                    include ROOT . 'templates/forum/topic_list.html';
                    $topic_list .= replace_tokens(ob_get_clean(), array('TOPIC_ID' => $topic['topic_id'], 'TOPIC_TITLE' => $topic['topic_title'], 'TOPIC_STARTER' => $topic['user_name']));
                }
                ob_start();
                include ROOT . 'templates/forum/single_section.html';
                echo replace_tokens(ob_get_clean(), array('TOPIC_LIST' => $topic_list, 'SECTION_TITLE' => $section->section_title));
            }
            break;
        case 'forum':
        default:
            //TODO: load the latest post
            $section_obj = new Section($sql);
            $all_sections = $section_obj->select_multiple_sections();
            $section_list = '';
            foreach($all_sections as $section) {
                ob_start();
                include ROOT . 'templates/forum/section_list.html';
                $section_list .= replace_tokens(ob_get_clean(), array('SECTION_ID' => $section['section_id'], 'SECTION_TITLE' => $section['section_title'], 'SECTION_DESC' => $section['section_description']));
            }
            ob_start();
            include ROOT . 'templates/forum/main_forum.html';
            $main_html = replace_tokens(ob_get_clean(), array('SECTION_LIST' => $section_list));
            echo $main_html;
            break;
    }
} else {
    echo 'You do not have permission to view this page';
}
?>