<?php
if(!isset($_GET['id'])) {
    echo 'Invalid quiz id';
} else {
    $quiz = new Quiz($sql, $_GET['id']);
    $questions = $quiz->select_questions(false);
    
    $question_list = "";
        foreach($questions as $question) {
            $answer_list = "";
            shuffle_assoc($question['answers']);
            foreach($question['answers'] as $id => $answer) {
                $disabled = (isset($_SESSION['messages']['quiz_results'])) ? 'disabled' : '';
                ob_start();
                include ROOT . 'templates/quiz/answer_list.html';
                $answer_list .= replace_tokens(ob_get_clean(), array('QUESTION_ID' => $question['question_id'], 'ANSWER_ID' => $id, 'ANSWER' => $answer, 'DISABLED' => $disabled));
            }
            
            if(isset($_SESSION['messages']['question_results'])) {
                if($_SESSION['messages']['question_results'][$question['question_id']] == true) {
                    $question_class = 'success';
                    $question_result_text = 'Correct! Well done :-)';
                } elseif($_SESSION['messages']['question_results'][$question['question_id']] == false) {
                    $question_class = 'error';
                    $question_result_text = 'Sorry that is the wrong answer :-(';
                } 
            } else {
                $question_class = 'invisible';
                $question_result_text = '';
            }
            
            ob_start();
            include ROOT . 'templates/quiz/question_list.html';
            $question_list .= replace_tokens(ob_get_clean(), array('QUESTION' => $question['question'], 'ANSWER_LIST' => $answer_list, 'QUESTION_MEDIA' => $question['question_media'], 'QUESTION_CLASS' => $question_class, 'QUESTION_RESULT' => $question_result_text));
        }
        
        if(isset($_SESSION['messages']['quiz_results'])) {
            $quiz_message = $_SESSION['messages']['quiz_results'];
            $disabled = 'disabled';
            $message_class = 'info';
            $retake = '';
        } else {
            $quiz_message = '';
            $disabled = '';
            $message_class = 'invisible';
            $retake = 'invisible';
        }
        
        ob_start();
        include ROOT . 'templates/quiz/take_quiz.html';

        echo replace_tokens(ob_get_clean(), array('QUIZ_TITLE' => $quiz->quiz_title, 'QUIZ_DESCRIPTION' => $quiz->quiz_description, 'QUESTION_LIST' => $question_list, 'QUIZ_ID' => $quiz->quiz_id, 'DISABLED' => $disabled, 'QUIZ_MESSAGE' => $quiz_message, 'MESSAGE_CLASS' => $message_class, 'RETAKE' => $retake));
}

unset($_SESSION['messages']);
?>