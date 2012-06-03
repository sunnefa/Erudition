<?php
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
    $quiz = new Quiz($sql, $_GET['id']);
    $questions = $quiz->select_questions(false);
    if(!isset($_GET['submit'])) {

        $question_list = "";
        foreach($questions as $question) {
            $answer_list = "";
            shuffle_assoc($question['answers']);
            foreach($question['answers'] as $id => $answer) {
                ob_start();
                include ROOT . 'templates/quiz/answer_list.html';
                $list = ob_get_clean();
                $answer_list .= replace_tokens($list, array('QUESTION_ID' => $question['question_id'], 'ANSWER_ID' => $id, 'ANSWER' => $answer, 'DISABLED' => ''));
            }

            ob_start();
            include ROOT . 'templates/quiz/question_list.html';
            $quest = ob_get_clean();
            $question_list .= replace_tokens($quest, array('QUESTION' => $question['question'], 'ANSWER_LIST' => $answer_list, 'QUESTION_MEDIA' => $question['question_media'], 'QUESTION_CLASS' => 'invisible', 'QUESTION_RESULT' => ""));
        }

        ob_start();
        include ROOT . 'templates/quiz/take_quiz.html';
        $take_quiz = ob_get_clean();

        echo replace_tokens($take_quiz, array('QUIZ_TITLE' => $quiz->quiz_title, 'QUIZ_DESCRIPTION' => $quiz->quiz_description, 'QUESTION_LIST' => $question_list, 'QUIZ_ID' => $quiz->quiz_id, 'DISABLED' => ''));
    } else {
        if(!isset($_POST['quiz_process'])) {
            echo 'No quiz submitted';
        } else {
            $correct = array();
            $num = 0;
            foreach($_POST['questions'] as $q_id => $a_id) {
                if($quiz->quiz_questions[$num]['correct_answer'] == $a_id && $quiz->quiz_questions[$num]['question_id'] == $q_id) {
                    $correct[$q_id] = true;
                } else {
                    $correct[$q_id] = false;
                }
                $num++;
            }
            
            $num_correct = array_count_booleans($correct);
            echo '<p>You answered ' . $num_correct['true'] . ' questions correctly';
            echo ' and ' . $num_correct['false'] . ' questions wrongly</p>';
            
            $question_list = "";
            foreach($questions as $question) {
                $answer_list = "";
                shuffle_assoc($question['answers']);
                foreach($question['answers'] as $id => $answer) {
                    ob_start();
                    include ROOT . 'templates/quiz/answer_list.html';
                    $list = ob_get_clean();
                    $answer_list .= replace_tokens($list, array('QUESTION_ID' => $question['question_id'], 'ANSWER_ID' => $id, 'ANSWER' => $answer, 'DISABLED' => 'disabled'));
                }
                
                if($correct[$question['question_id']] == true) {
                    $question_class = 'success';
                    $question_result_text = 'Correct! Well done :-)';
                } elseif($correct[$question['question_id']] == false) {
                    $question_class = 'error';
                    $question_result_text = 'Sorry that is the wrong answer :-(';
                }

                ob_start();
                include ROOT . 'templates/quiz/question_list.html';
                $quest = ob_get_clean();
                $question_list .= replace_tokens($quest, array('QUESTION' => $question['question'], 'ANSWER_LIST' => $answer_list, 'QUESTION_MEDIA' => $question['question_media'], 'QUESTION_CLASS' => $question_class, 'QUESTION_RESULT' => $question_result_text));
            }

            ob_start();
            include ROOT . 'templates/quiz/take_quiz.html';
            $take_quiz = ob_get_clean();

            echo replace_tokens($take_quiz, array('QUIZ_TITLE' => $quiz->quiz_title, 'QUIZ_DESCRIPTION' => $quiz->quiz_description, 'QUESTION_LIST' => $question_list, 'QUIZ_ID' => $quiz->quiz_id, 'DISABLED' => 'disabled="disabled"'));
            
        }
    }
}
?>