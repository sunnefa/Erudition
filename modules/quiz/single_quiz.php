<?php
/**
 * Controller for a single quiz
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com> 
 */

//if there's no id we can't show anything
if(!isset($_GET['id'])) {
    echo 'Invalid quiz id';
} else {
    //quiz object and questions
    $quiz = new Quiz($sql, $_GET['id']);
    $questions = $quiz->select_questions(false);
    //loop through the questions
    $question_list = "";
        foreach($questions as $question) {
            $answer_list = "";
            //shuffle the answers
            shuffle_assoc($question['answers']);
            //loop through the answers
            foreach($question['answers'] as $id => $answer) {
                //if a session message is set the answers must be diabled
                $disabled = (isset($_SESSION['messages']['quiz_results'])) ? 'disabled' : '';
                ob_start();
                include ROOT . 'templates/quiz/answer_list.html';
                $answer_list .= replace_tokens(ob_get_clean(), array('QUESTION_ID' => $question['question_id'], 'ANSWER_ID' => $id, 'ANSWER' => $answer, 'DISABLED' => $disabled));
            }
            //if the session message with the question results is set, we need to show a little text with each question to indicate if the answer was wrong or right
            if(isset($_SESSION['messages']['question_results'])) {
                if($_SESSION['messages']['question_results'][$question['question_id']] == true) {
                    $question_class = 'success';
                    $question_result_text = 'Correct! Well done :-)';
                } elseif($_SESSION['messages']['question_results'][$question['question_id']] == false) {
                    $question_class = 'error';
                    $question_result_text = 'Sorry that is the wrong answer :-(';
                } 
            } else {
                //if that session message is not set we need to hide the p tag showing the message
                $question_class = 'invisible';
                $question_result_text = '';
            }
            //include the template for the questions list and replace the tokens with the right values as loaded above
            ob_start();
            include ROOT . 'templates/quiz/question_list.html';
            $question_list .= replace_tokens(ob_get_clean(), array('QUESTION' => $question['question'], 'ANSWER_LIST' => $answer_list, 'QUESTION_MEDIA' => $question['question_media'], 'QUESTION_CLASS' => $question_class, 'QUESTION_RESULT' => $question_result_text));
        }
        //if the quiz results have been set in the session we need to show a message and disable the submit button
        if(isset($_SESSION['messages']['quiz_results'])) {
            $quiz_message = $_SESSION['messages']['quiz_results'];
            $disabled = 'disabled';
            $message_class = 'info';
            $retake = '';
        } else {
            //if there is no session message we can hide the message and the retake links
            $quiz_message = '';
            $disabled = '';
            $message_class = 'invisible';
            $retake = 'invisible';
        }
        //include the single quiz template and replace the template tokens
        ob_start();
        include ROOT . 'templates/quiz/take_quiz.html';

        echo replace_tokens(ob_get_clean(), array('QUIZ_TITLE' => $quiz->quiz_title, 'QUIZ_DESCRIPTION' => $quiz->quiz_description, 'QUESTION_LIST' => $question_list, 'QUIZ_ID' => $quiz->quiz_id, 'DISABLED' => $disabled, 'QUIZ_MESSAGE' => $quiz_message, 'MESSAGE_CLASS' => $message_class, 'RETAKE' => $retake));
}
//unset the session messages
unset($_SESSION['messages']);
?>