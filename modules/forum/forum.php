<?php
/**
 * Controller for the main forum
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com> 
 */

//a user must be logged in to view this page
if(is_logged_in()) {
    //the part that is shown
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
            
            //declare some variables needed
            $section_obj = new Section($sql);
            $all_sections = $section_obj->select_multiple_sections();
            $section_list = '';
            
            //loop through the list of all the sections
            foreach($all_sections as $section) {
                ob_start();
                include ROOT . 'templates/forum/section_list.html';
                if(!is_array($section['latest_post'])) {
                    $latest_post_html = 'No topics';
                } else {
                    //variables needed for the latest post
                    $user_id = (isset($_SESSION['user'])) ? $_SESSION['user'] : $_COOKIE['user'];
                    $user = new User($sql, $user_id);
                    $last_page = ceil($section['latest_post']['total_posts'] / 15);
                    $link_class = (strtotime($user->user_last_logged_in_date) < strtotime($section['latest_post']['post_date'])) ? 'glowing' : 'non-glowing';
                    
                    //include the html for the latest post
                    ob_start();
                    include ROOT . 'templates/forum/latest_post.html';
                    $latest_post_html = replace_tokens(ob_get_clean(), array(
                        'USER_NAME' => $section['latest_post']['username'],
                        'LINK_CLASS' => $link_class,
                        'TOPIC_ID' => $section['latest_post']['topic_id'],
                        'PAGE_NUMBER' => $last_page,
                        'POST_ID' => $section['latest_post']['post_id'],
                        'DATE' => date('D M d, Y h:i a',strtotime($section['latest_post']['post_date']))
                    ));
                }
                //replace the template tokens in the section list with the appropriate values
                $section_list .= replace_tokens(ob_get_clean(), array('SECTION_ID' => $section['section_id'], 'SECTION_TITLE' => $section['section_title'], 'SECTION_DESC' => $section['section_description'], 'LATEST_POST' => $latest_post_html, 'NUMBER_OF_TOPICS' => $section['num_topics']));
            } //end section list loop
            
            //include the html for the main forum
            ob_start();
            include ROOT . 'templates/forum/main_forum.html';
            $main_html = replace_tokens(ob_get_clean(), array('SECTION_LIST' => $section_list));
            echo $main_html;
            break;
    }
    //if the user is not logged in
} else {
    echo 'You do not have permission to view this page';
}
?>