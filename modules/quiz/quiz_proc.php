<?php
/**
 * Controller for the posting of a quiz
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com> 
 */

//we need a quiz object
$quiz = new Quiz($sql, $_POST['quiz_id']);
//load the questions
$questions = $quiz->select_questions(false);
$correct = array();
$num = 0;
//loop through the questions and answers and compare them to the questions we loaded before
foreach($_POST['questions'] as $q_id => $a_id) {
    if($quiz->quiz_questions[$num]['correct_answer'] == $a_id && $quiz->quiz_questions[$num]['question_id'] == $q_id) {
        $correct[$q_id] = true;
    } else {
        $correct[$q_id] = false;
    }
    $num++;
}
//count how many answers were true and how many were false
$num_correct = array_count_booleans($correct);

//send this info as a session message
$_SESSION['messages']['quiz_results'] = 'You answered ' . $num_correct['true'] . ' question correctly and ';
$_SESSION['messages']['quiz_results'] .= $num_correct['false'] . ' questions wrongly';
$_SESSION['messages']['question_results'] = $correct;

//reload the quiz page
reload('quiz/' . $_POST['quiz_id'] . '/');
?>