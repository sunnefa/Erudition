<?php
if(!isset($_GET['id'])) {
    echo 'Invalid lesson id';
} else {
    $lesson = new Lesson($sql, $_GET['id']);
    
    $chapter_obj = new Chapter($sql);
    $chapters = $chapter_obj->select_multiple_chapters();
    
    $chapter_list = "";
    foreach($chapters as $chapter) {
        ob_start();
        include ROOT . 'templates/erudition/chapter_list.html';
        $chapter_list .= replace_tokens(ob_get_clean(), array('CHAPTER_TITLE' => $chapter['chapter_title'], 'CHAPTER_ID' => $chapter['chapter_id']));
    }
    
    ob_start();
    include ROOT . 'templates/erudition/single_lesson.html';
    echo replace_tokens(ob_get_clean(), array('LESSON_TITLE' => $lesson->lesson_title, 'CHAPTER_LIST' => $chapter_list));
}
?>