<?php
//is a user logged in?
if(!is_logged_in()) {
    echo '<p class="error">You do not have permission to view this page</p>';
} else {
    //if there is no id set, show all quizzes
        if(!isset($_GET['id'])) {
        $quiz = new Quiz($sql);

        $all_quizzes = $quiz->select_multiple_quizzes();

        $quiz_list = "";
        foreach($all_quizzes as $single_quiz) {
            ob_start();
            include ROOT . 'templates/quiz/single_quiz.html';
            $text = ob_get_clean();
            $quiz_list .= replace_tokens($text, array('QUIZ_ID' => $single_quiz['quiz_id'], 'QUIZ_TITLE' => $single_quiz['quiz_title'], 'QUIZ_DESCRIPTION' => $single_quiz['quiz_description']));
        }

        ob_start();
        include ROOT . 'templates/quiz/quiz_list.html';
        $quiz_content = ob_get_clean();

        echo replace_tokens($quiz_content, array('QUIZ_LIST' => $quiz_list));
    } else {
        include ROOT . 'modules/quiz/single_quiz.php';
    } 
}
?>