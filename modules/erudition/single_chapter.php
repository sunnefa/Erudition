<?php
/**
 * The controller for a single chapter
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com> 
 */

//if there is no chapter id set, we can't show any chapter
if(!isset($_GET['id'])) {
    echo 'Invalid chapter id';
} else {
    //create the chapter object
    $chapter = new Chapter($sql, $_GET['id']);
    //we also need a lesson object
    $lesson = new Lesson($sql, $chapter->chapter_lesson);
    //load all the lessons
    $all_lessons = $lesson->select_multiple_lessons();
    $lesson_list = "";
    //loop through the lesson list and include the template etc.
    foreach($all_lessons as $single_lesson) {
        ob_start();
        include ROOT . 'templates/erudition/lesson_list.html';
        $lesson_list .= replace_tokens(ob_get_clean(), array('LESSON_ID' => $single_lesson['lesson_id'], 'LESSON_TITLE' => $single_lesson['lesson_title'], 'LESSON_NUM_CHAPTERS' => $single_lesson['lesson_num_chapters']));
    }
    //load all the lesson chapters
    $lesson_chapters = $chapter->select_multiple_chapters($chapter->chapter_lesson);
    $chapter_list = "";
    //loop through the chapter list, include the template etc
    foreach($lesson_chapters as $single_chapter) {
        ob_start();
        include ROOT . 'templates/erudition/chapter_list.html';
        $chapter_list .= replace_tokens(ob_get_clean(), array('CHAPTER_ID' => $single_chapter['chapter_id'], 'CHAPTER_TITLE' => $single_chapter['chapter_title']));
    }
    
    //build the breadcrumb links
    $breadcrumbs = <<<EOT
    <p class="breadcrumbs"><a href="courses/">Courses</a> &GT; <a href="courses/lesson/$lesson->lesson_id/">$lesson->lesson_title</a> &GT; $chapter->chapter_title</p>
EOT;
    //include the template for a single chapter and replace the template tokens with the right values
    ob_start();
    include ROOT . 'templates/erudition/single_chapter.html';
    echo replace_tokens(ob_get_clean(), array('CHAPTER_TITLE' => $chapter->chapter_title, 'CHAPTER_TRANSCRIPT' => $chapter->chapter_transcript, 'CHAPTER_VIDEO' => $chapter->chapter_video, 'QUIZ_ID' => $lesson->lesson_quiz, 'LESSON_LIST' => $lesson_list, 'CHAPTER_LIST' => $chapter_list, 'LESSON_TITLE' => $lesson->lesson_title, 'BREADCRUMBS' => $breadcrumbs));
}
?>