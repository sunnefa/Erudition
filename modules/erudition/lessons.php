<?php
if(is_logged_in()) {
    $part = (isset($_GET['part'])) ? $_GET['part'] : 'list';

    switch($part) {
        case 'lesson':
            include ROOT . 'modules/erudition/single_lesson.php';
            break;
        case 'chapter':
            include ROOT . 'modules/erudition/single_chapter.php';
            break;
        case 'list':
        default:
            $lesson_obj = new Lesson($sql);
            $all_lessons = $lesson_obj->select_multiple_lessons();
            $lesson_list = "";
            foreach($all_lessons as $lesson) {
                ob_start();
                include ROOT . 'templates/erudition/lesson_list.html';
                $lesson_list .= replace_tokens(ob_get_clean(), array('LESSON_ID' => $lesson['lesson_id'], 'LESSON_TITLE' => $lesson['lesson_title'], 'LESSON_NUM_CHAPTERS' => $lesson['lesson_num_chapters']));
            }
            ob_start();
            include ROOT . 'templates/erudition/all_lessons.html';
            echo replace_tokens(ob_get_clean(), array('LESSON_LIST' => $lesson_list));
            break;
    }
} else {
    echo 'You do not have permission to view this page';
}

?>
