<?php
/**
 * Description of Quiz
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Quiz {
    
    /**
     * The id of the currently selected quiz
     * @var int/string 
     */
    public $quiz_id;
    
    /**
     * The title of the currently selected quiz
     * @var string 
     */
    public $quiz_title;
    
    /**
     * The description of the currently selected quiz
     * @var string 
     */
    public $quiz_description;
    
    /**
     * The questions and answers in the currently selected quiz
     * @var array 
     */
    public $quiz_questions;
    
    /**
     * A reference to the database wrapper
     * @var MySQLWrapper 
     */
    protected $db_wrapper;
    
    /**
     * Constructor
     * @param DBWrapper $db_wrapper
     * @param int $quiz_id 
     */
    public function __construct(DBWrapper $db_wrapper, $quiz_id = null) {
        $this->db_wrapper = $db_wrapper;
        
        if($quiz_id) {
            $this->select_single_quiz($quiz_id);
        }
    }
    
    /**
     * A getter
     * @param string $variable_name
     * @return mixed 
     */
    public function __get($variable_name) {
        return $this->$variable_name;
    }
    
    /**
     * Selects a single quiz from the database
     * @param int $quiz_id 
     */
    private function select_single_quiz($quiz_id) {
        $quiz = $this->db_wrapper->select_data('quiz__quizzes', '*', 'quiz_id = ' . $quiz_id);
        if($quiz != null) {
            $quiz = array_flat($quiz);

            $this->quiz_id = $quiz['quiz_id'];

            $this->quiz_description = $quiz['quiz_description'];

            $this->quiz_title = $quiz['quiz_title'];
        } else {
            echo 'No quiz found';
        }
    }
    
    /**
     * Select multiple quizzes, either all or a number specified
     * @param mixed $quantity 
     * @return mixed
     */
    public function select_multiple_quizzes($quantity = 'all') {
        if($quantity == 'all') {
            $quizzes = $this->db_wrapper->select_data('quiz__quizzes', '*');
        } elseif(is_numeric($quantity)) {
            $quizzes = $this->db_wrapper->select_data('quiz__quizzes', '*', null, $quantity);
        } else {
            $quizzes = 'Invalid argument';
        }
        
        if($quizzes) {
            return $quizzes;
        } else {
            echo 'No quizzes found';
        }
    }
    
    /**
     * Selects all the questions in the database regardless of the quiz selected
     * @return array 
     */
    public function select_questions($all = true) {
        $join_junction = $this->db_wrapper->build_joins('LEFT', array('quiz__questions_answers', 'qa'), array('qa.question_id', 'q.question_id'));
        
        $join_answers = $this->db_wrapper->build_joins('LEFT', array('quiz__answers', 'a'), array('qa.answer_id', 'a.answer_id'));
        
        $join_quiz = "";
        $where = "";
        
        if(!$all) {
            $join_quiz = $this->db_wrapper->build_joins('LEFT', array('quiz__quizzes_questions', 'qq'), array('qq.question_id', 'q.question_id'));
            $where = "qq.quiz_id = " . $this->quiz_id;
        }
        
        $questions = $this->db_wrapper->select_data(array('quiz__questions', 'q'), 'q.question_id, q.question, q.question_media, q.correct_answer, GROUP_CONCAT(a.answer) AS answers, GROUP_CONCAT(a.answer_id) AS answer_ids', $where, null, null, 'q.question_id', $join_junction . " " . $join_answers . " " . $join_quiz);
        
        if($questions != null) {
            
            foreach($questions as $key => $question) {
                $answers = explosion(',', $question['answer_ids'], $question['answers']);
                
                $questions[$key]['answers'] = $answers;
            } 
            $this->quiz_questions = $questions;
            return $questions;
        } else {
            return 'No questions found';
        }
    }
}

?>
