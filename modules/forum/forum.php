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
            $section = new Section($sql, $topic->topic_section);
            ob_start();
            include ROOT . 'templates/forum/single_topic.html';
            $breadcrumbs = <<<EOT
            <p class="breadcrumbs">
                <a href="community/">Community</a> &GT; <a href="community/section/$section->section_id/">$section->section_title</a> &GT; $topic->topic_title
            </p>
EOT;
            echo replace_tokens(ob_get_clean(), array('TOPIC_TITLE' => $topic->topic_title, 'POST_LIST' => $post_list, 'TOPIC_ID' => $topic->topic_id, 'BREADCRUMBS' => $breadcrumbs));
            break;
        case 'section':
            if(!isset($_GET['id'])) {
                echo 'No section selected';
            } else {
                $section = new Section($sql, $_GET['id']);
                $topic_obj = new Topic($sql);
                $topics = $topic_obj->load_multiple_topics($_GET['id']);
                $breadcrumbs = <<<EOT
            <p class="breadcrumbs">
                <a href="community/">Community</a> &GT; $section->section_title
            </p>
EOT;
                if(!$topics) {
                    ob_start();
                    include ROOT . 'templates/forum/single_section.html';
                    echo replace_tokens(ob_get_clean(), array('TOPIC_LIST' => '<tr><td>No topics found</td><td class="latest">&nbsp;</td></tr>', 'SECTION_TITLE' => $section->section_title, 'BREADCRUMBS' => $breadcrumbs));
                } else {
                    $topic_list = '';
                    foreach($topics as $topic) {
                        ob_start();
                        include ROOT . 'templates/forum/topic_list.html';
                        $topic_list .= replace_tokens(ob_get_clean(), array('TOPIC_ID' => $topic['topic_id'], 'TOPIC_TITLE' => $topic['topic_title'], 'TOPIC_STARTER' => $topic['user_name']));
                    }
                    ob_start();
                    include ROOT . 'templates/forum/single_section.html';
                    echo replace_tokens(ob_get_clean(), array('TOPIC_LIST' => $topic_list, 'SECTION_TITLE' => $section->section_title, 'BREADCRUMBS' => $breadcrumbs));
                }
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
                if(!is_array($section['latest_post'])) {
                    $latest_post_html = 'No topics';
                } else {
                    $latest_post_html = <<<EOT
                   <a href="community/topic/{$section['latest_post']['topic_id']}/">{$section['latest_post']['topic_title']}</a> by {$section['latest_post']['username']} on {$section['latest_post']['post_date']} 
EOT;
                }
                $section_list .= replace_tokens(ob_get_clean(), array('SECTION_ID' => $section['section_id'], 'SECTION_TITLE' => $section['section_title'], 'SECTION_DESC' => $section['section_description'], 'LATEST_POST' => $latest_post_html));
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