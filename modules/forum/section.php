<?php
if(!isset($_GET['id'])) {
    echo 'No section selected';
} else {
    $section = new Section($sql, $_GET['id']);
    $topic_obj = new Topic($sql);
    $topics = $topic_obj->load_multiple_topics($_GET['id']);
    $breadcrumbs = <<<EOT
<p class="breadcrumbs one-half">
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
            $user_id = (isset($_SESSION['user'])) ? $_SESSION['user'] : $_COOKIE['user'];
            $user = new User($sql, $user_id);
            $last_page = ceil($topic['total_posts'] / 15);
            $link_class = (strtotime($user->user_last_logged_in_date) < strtotime($topic['latest_post']['post_date'])) ? 'glowing' : 'non-glowing';
            ob_start();
            include ROOT . 'templates/forum/latest_post.html';
            $latest_post_html = replace_tokens(ob_get_clean(), array(
                'USER_NAME' => $topic['latest_post']['username'],
                'LINK_CLASS' => $link_class,
                'TOPIC_ID' => $topic['topic_id'],
                'PAGE_NUMBER' => $last_page,
                'POST_ID' => $topic['latest_post']['post_id'],
                'DATE' => date('D M d, Y h:i a',strtotime($topic['latest_post']['post_date']))
            ));
            
            ob_start();
            include ROOT . 'templates/forum/topic_list.html';
            if($topic['total_posts'] > 15) {
                $pagination = "";
                $total_pages = ceil($topic['total_posts'] / 15);
                for($i = 1; $i < $total_pages+1; $i++) {
                    $pagination .= <<<EOT
                    <a class="pagination" href="community/topic/{$topic['topic_id']}/page-$i/">$i</a>
EOT;
                }
            } else {
                $pagination = "";
            }
            $topic_list .= replace_tokens(ob_get_clean(), array('TOPIC_ID' => $topic['topic_id'], 'TOPIC_TITLE' => $topic['topic_title'], 'TOPIC_STARTER' => $topic['user_name'], 'LATEST_POST' => $latest_post_html, 'PAGINATION' => $pagination, 'NUMBER_OF_POSTS' => $topic['total_posts']));
        }
        ob_start();
        include ROOT . 'templates/forum/new_post_form.html';
        $post_form_html = replace_tokens(ob_get_clean(), array('TOPIC_ID' => "", 'NEW_POST_HEADLINE' => 'Add new topic', 'NAME_OF_ID' => 'section_id', 'ID_TO_SEND' => $section->section_id));
        ob_start();
        include ROOT . 'templates/forum/single_section.html';
        echo replace_tokens(ob_get_clean(), array('TOPIC_LIST' => $topic_list, 'SECTION_TITLE' => $section->section_title, 'BREADCRUMBS' => $breadcrumbs, 'NEW_TOPIC_FORM' => $post_form_html));
    }
}
?>