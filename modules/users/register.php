<?php
if(isset($_GET['activation'])) {
    include 'activation.php';
} else {
  if(!is_logged_in()) {
    if(isset($_SESSION['messages']['reg_success'])) {
        echo '<p class="success">' . $_SESSION['messages']['reg_success'] . '</p>';
    } else {
        ob_start();
        include ROOT . 'templates/users/registration_form.html';
        //if the registration failed, show an error message
        $reg_fail = (isset($_SESSION['messages']['reg_fail'])) ? array('MESSAGE_CLASS' => 'error', 'MESSAGE' => $_SESSION['messages']['reg_fail']) : array('MESSAGE_CLASS' => 'invisible', 'MESSAGE' => '');
        //if the email address is not valid, show an error message
        $email_fail = (isset($_SESSION['messages']['email_fail'])) ? array('EMAIL_CLASS' => 'error', 'EMAIL_MESSAGE' => $_SESSION['messages']['email_fail']) : array('EMAIL_CLASS' => 'invisible', 'EMAIL_MESSAGE' => '');
        //if the password is not valid, show an error message
        $pass_fail = (isset($_SESSION['messages']['pass_fail'])) ? array('PASSWORD_CLASS' => 'error', 'PASSWORD_MESSAGE' => $_SESSION['messages']['pass_fail']) : array('PASSWORD_CLASS' => 'invisible', 'PASSWORD_MESSAGE' => '');
        //if the repeated password does not match, show an error message
        $repeat_fail = (isset($_SESSION['messages']['repeat_fail'])) ? array('REPEAT_CLASS' => 'error', 'REPEAT_MESSAGE' => $_SESSION['messages']['repeat_fail']) : array('REPEAT_CLASS' => 'invisible', 'REPEAT_MESSAGE' => '');

        $old_values = array(
            'FIRST_NAME' => (isset($_SESSION['old_values']['first_name'])) ? $_SESSION['old_values']['first_name'] : '',
            'LAST_NAME' => (isset($_SESSION['old_values']['last_name'])) ? $_SESSION['old_values']['last_name'] : '',
            'EMAIL_ADDRESS' => (isset($_SESSION['old_values']['email_address'])) ? $_SESSION['old_values']['email_address'] : ''
        );

        $tokens = array_merge($reg_fail, $email_fail, $pass_fail, $repeat_fail, $old_values);
        echo replace_tokens(ob_get_clean(), $tokens);
    }
  } else {
      echo 'You are logged in. You cannot register again. Please select another link.';
  }
}
unset($_SESSION['messages']);
unset($_SESSION['old_values']);
?>