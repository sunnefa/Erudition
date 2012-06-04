<?php
/**
 * The controller for a single lesson
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com> 
 */

//if there is no lesson id, we can't load a lesson
if(!isset($_GET['id'])) {
    echo 'Invalid lesson id';
} else {
    //create a lesson object
    $lesson = new Lesson($sql, $_GET['id']);
    //we also need a chapter object
    $chapter_obj = new Chapter($sql);
    $chapters = $chapter_obj->select_multiple_chapters($_GET['id']);
    
    $chapter_list = "";
    //loop through the chapter list and replace the tokens etc.
    foreach($chapters as $chapter) {
        ob_start();
        include ROOT . 'templates/erudition/chapter_list.html';
        $chapter_list .= replace_tokens(ob_get_clean(), array('CHAPTER_TITLE' => $chapter['chapter_title'], 'CHAPTER_ID' => $chapter['chapter_id']));
    }
    
    //build the breadcrumb links
    $breadcrumbs = <<<EOT
    <p class="breadcrumbs"><a href="courses/">Courses</a> &GT; $lesson->lesson_title</p>
EOT;
    //include the single lesson template and replace the template tokens with the right values
    ob_start();
    include ROOT . 'templates/erudition/single_lesson.html';
    echo replace_tokens(ob_get_clean(), array('LESSON_TITLE' => $lesson->lesson_title, 'LESSON_DESCRIPTION' => $lesson->lesson_description, 'CHAPTER_LIST' => $chapter_list, 'BREADCRUMBS' => $breadcrumbs));
}
?>