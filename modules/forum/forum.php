<?php
if(is_logged_in()) {
    $part = (isset($_GET['part'])) ? $_GET['part'] : 'forum';
    switch($part) {
        //showing a single topic
        case 'topic':
            include 'topic.php';
            break;
        //showing a single section
        case 'section':
            include 'section.php';
            break;
        //showing the whole forum
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
                    $last_page = ceil($section['latest_post']['total_posts'] / 15);
                    $latest_post_html = <<<EOT
                   <a href="community/topic/{$section['latest_post']['topic_id']}/page-$last_page/#post-{$section['latest_post']['post_id']}">{$section['latest_post']['topic_title']}</a> by {$section['latest_post']['username']} on {$section['latest_post']['post_date']} 
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