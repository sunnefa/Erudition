<?php
/**
 * The controller for the registrations/signup
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com> 
 */

//if this is an activation request, we load the activation controller
if(isset($_GET['activation'])) {
    include 'activation.php';
//if not we show the registration form
} else {
    //if the user is not logged in
  if(!is_logged_in()) {
      //if the registration was successful previously we only show that message
    if(isset($_SESSION['messages']['reg_success'])) {
        echo '<p class="success">' . $_SESSION['messages']['reg_success'] . '</p>';
    } else {
        //if registration was not successful before we need to show the form
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
        
        //if registration failed before, the old values of the form will be saved in the session
        $old_values = array(
            'FIRST_NAME' => (isset($_SESSION['old_values']['first_name'])) ? $_SESSION['old_values']['first_name'] : '',
            'LAST_NAME' => (isset($_SESSION['old_values']['last_name'])) ? $_SESSION['old_values']['last_name'] : '',
            'EMAIL_ADDRESS' => (isset($_SESSION['old_values']['email_address'])) ? $_SESSION['old_values']['email_address'] : ''
        );
        
        //the tokens are a merge of all the above arrays
        $tokens = array_merge($reg_fail, $email_fail, $pass_fail, $repeat_fail, $old_values);
        //replace the tokens and echo
        echo replace_tokens(ob_get_clean(), $tokens);
    }
  } else {
      //if the user is logged in it makes no sense to show the registration form because they are already registered
      echo 'You are logged in. You cannot register again. Please select another link.';
  }
}
//unset the session values
unset($_SESSION['messages']);
unset($_SESSION['old_values']);
?>