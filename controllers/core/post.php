<?php
/**
 * The controller for all post requests
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com> 
 */

//switch on the name of the form
if(isset($_POST['form'])) {
    switch($_POST['form']) {
        case 'login_form':
            include ROOT . 'modules/users/login_proc.php';
            break;
        case 'registration_form':
            include ROOT . 'modules/users/reg_proc.php';
            break;
        case 'quiz_form':
            include ROOT . 'modules/quiz/quiz_proc.php';
            break;
        case 'new_topic_form':
            break;
        case 'new_post_form':
            include ROOT . 'modules/forum/add_post.php';
            break;
        default:
            break;
    }
}

?>