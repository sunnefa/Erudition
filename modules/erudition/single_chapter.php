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
    
    //get the right forum topic
    if($chapter->chapter_forum_topic != null || $chapter->chapter_forum_topic != 0) {
    $topic = new Topic($sql, $chapter->chapter_forum_topic);
    $topic->load_topic_posts();
    $post_list = '';
    $posts = array_chunk($topic->topic_posts, 15);
    $all_p = count($posts);
    $p = (isset($_GET['p'])) ? $_GET['p']-1 : 0;
    //lopp through and show the posts in the topic
    foreach($posts[$p] as $post) {
        ob_start();
        include ROOT . 'templates/forum/post_list.html';
        $post_title = ($post['post_title'] != NULL) ? $post['post_title'] : $topic->topic_title;
        $user = new User($sql, $post['created_by']);
        $post_list .= replace_tokens(ob_get_clean(), array('POST_TITLE' => $post_title, 'POST_CONTENT' => $post['post_content'], 'POST_ID' => $post['post_id'], 'POST_DATE' => $post['post_date'], 'USER_NAME' => $user->user_first_name . ' ' . $user->user_last_name, 'USER_IMAGE' => $user->user_image));
    }//end of post list loop
    
    ob_start();
    //include the single topic template
    include ROOT . 'templates/forum/single_topic.html';
    //do the pagination
    $pagination = "";
    for($i = 1; $i < $all_p+1; $i++) {
        if($i == $p+1) {
            $pagination .= <<<EOT
            <span class="pagination">$i</span>
EOT;
        } else {
            $pagination .= <<<EOT
        <a class="pagination" href="community/topic/$topic->topic_id/page-$i/">$i</a>
EOT;
        }
    }
    //include the new post form
    ob_start();
    include ROOT . 'templates/forum/new_post_form.html';
    $post_form_html = replace_tokens(ob_get_clean(), array('ID_TO_SEND' => $topic->topic_id, 'NAME_OF_ID' => 'topic_id', 'NEW_POST_HEADLINE' => 'Reply to ' . $topic->topic_title));
    
    //echo the single topic template with the right replacements
    $forum_topic = <<<EOT
    <h2>Discuss this chapter</h2>
<div style="height:500px;overflow: scroll;">
EOT;
    $forum_topic .= replace_tokens(ob_get_clean(), array('TOPIC_TITLE' => $topic->topic_title, 'POST_LIST' => $post_list, 'NEW_POST_FORM' => $post_form_html, 'PAGINATION' => $pagination, 'BREADCRUMBS' => ''));
    
    $forum_topic .= '</div>';
    } else {
        $forum_topic = '';
    }
    
    //include the template for a single chapter and replace the template tokens with the right values
    ob_start();
    include ROOT . 'templates/erudition/single_chapter.html';
    echo replace_tokens(ob_get_clean(), array('CHAPTER_TITLE' => $chapter->chapter_title, 'CHAPTER_TRANSCRIPT' => $chapter->chapter_transcript, 'CHAPTER_VIDEO' => $chapter->chapter_video, 'QUIZ_ID' => $lesson->lesson_quiz, 'LESSON_LIST' => $lesson_list, 'CHAPTER_LIST' => $chapter_list, 'LESSON_TITLE' => $lesson->lesson_title, 'BREADCRUMBS' => $breadcrumbs, 'FORUM_TOPIC' => $forum_topic));
}
?>