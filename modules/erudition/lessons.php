<?php
/**
 * The controller for the lessons module
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com> 
 */

//if the user is logged in they have permission to view the lessons
if(is_logged_in()) {
    $part = (isset($_GET['part'])) ? $_GET['part'] : 'list';
    //switch on the part being loaded
    switch($part) {
        case 'lesson':
            include ROOT . 'modules/erudition/single_lesson.php';
            break;
        case 'chapter':
            include ROOT . 'modules/erudition/single_chapter.php';
            break;
        case 'list':
        default:
            //create a new lesson object
            $lesson_obj = new Lesson($sql);
            //load all the lessons
            $all_lessons = $lesson_obj->select_multiple_lessons();
            $lesson_list = "";
            //loop through them
            foreach($all_lessons as $lesson) {
                ob_start();
                //include the lesson list template
                include ROOT . 'templates/erudition/lesson_list.html';
                //replace the template tokens
                $lesson_list .= replace_tokens(ob_get_clean(), array('LESSON_ID' => $lesson['lesson_id'], 'LESSON_TITLE' => $lesson['lesson_title'], 'LESSON_NUM_CHAPTERS' => $lesson['lesson_num_chapters']));
            }
            ob_start();
            //include the template
            include ROOT . 'templates/erudition/all_lessons.html';
            //replace the template tokens
            echo replace_tokens(ob_get_clean(), array('LESSON_LIST' => $lesson_list));
            break;
    }
    // if they are not logged in they don't have permission
} else {
    echo 'You do not have permission to view this page';
}

?>
