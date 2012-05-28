<?php
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
            $latest_post_html = <<<EOT
            <a href="community/topic/{$topic['topic_id']}/#post-{$topic['latest_post']['post_id']}">by {$topic['latest_post']['username']}</a> on {$topic['latest_post']['post_date']}    
EOT;
            $pagination = "";
            $topic_list .= replace_tokens(ob_get_clean(), array('TOPIC_ID' => $topic['topic_id'], 'TOPIC_TITLE' => $topic['topic_title'], 'TOPIC_STARTER' => $topic['user_name'], 'LATEST_POST' => $latest_post_html));
        }
        ob_start();
        include ROOT . 'templates/forum/single_section.html';
        echo replace_tokens(ob_get_clean(), array('TOPIC_LIST' => $topic_list, 'SECTION_TITLE' => $section->section_title, 'BREADCRUMBS' => $breadcrumbs));
    }
}
?>