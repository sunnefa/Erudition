<?php

if(isset($_POST['form'])) {
    switch($_POST['form']) {
        case 'login_form':
            include ROOT . 'modules/users/login_proc.php';
            break;
        case 'registration_form':
            include ROOT . 'modules/users/reg_proc.php';
            break;
        case 'quiz_form':
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