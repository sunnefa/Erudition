<?php
if(!isset($_GET['id'])) {
    echo 'Invalid chapter id';
} else {
    $chapter = new Chapter($sql, $_GET['id']);
    
    ob_start();
    include ROOT . 'templates/erudition/single_chapter.html';
    echo replace_tokens(ob_get_clean(), array('CHAPTER_TITLE' => $chapter->chapter_title, 'CHAPTER_TRANSCRIPT' => $chapter->chapter_transcript, 'CHAPTER_VIDEO' => $chapter->chapter_video));
}
?>