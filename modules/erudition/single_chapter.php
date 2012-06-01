<?php
if(!isset($_GET['id'])) {
    echo 'Invalid chapter id';
} else {
    $chapter = new Chapter($sql, $_GET['id']);
    $lesson = new Lesson($sql, $chapter->chapter_lesson);
    $all_lessons = $lesson->select_multiple_lessons();
    $lesson_list = "";
    foreach($all_lessons as $single_lesson) {
        ob_start();
        include ROOT . 'templates/erudition/lesson_list.html';
        $lesson_list .= replace_tokens(ob_get_clean(), array('LESSON_ID' => $single_lesson['lesson_id'], 'LESSON_TITLE' => $single_lesson['lesson_title'], 'LESSON_NUM_CHAPTERS' => $single_lesson['lesson_num_chapters']));
    }
    
    $lesson_chapters = $chapter->select_multiple_chapters($chapter->chapter_lesson);
    $chapter_list = "";
    foreach($lesson_chapters as $single_chapter) {
        ob_start();
        include ROOT . 'templates/erudition/chapter_list.html';
        $chapter_list .= replace_tokens(ob_get_clean(), array('CHAPTER_ID' => $single_chapter['chapter_id'], 'CHAPTER_TITLE' => $single_chapter['chapter_title']));
    }
    
    $breadcrumbs = <<<EOT
    <p class="breadcrumbs"><a href="courses/">Courses</a> &GT; <a href="courses/lesson/$lesson->lesson_id/">$lesson->lesson_title</a> &GT; $chapter->chapter_title</p>
EOT;
    
    ob_start();
    include ROOT . 'templates/erudition/single_chapter.html';
    echo replace_tokens(ob_get_clean(), array('CHAPTER_TITLE' => $chapter->chapter_title, 'CHAPTER_TRANSCRIPT' => $chapter->chapter_transcript, 'CHAPTER_VIDEO' => $chapter->chapter_video, 'QUIZ_ID' => $lesson->lesson_quiz, 'LESSON_LIST' => $lesson_list, 'CHAPTER_LIST' => $chapter_list, 'LESSON_TITLE' => $lesson->lesson_title, 'BREADCRUMBS' => $breadcrumbs));
}
?>