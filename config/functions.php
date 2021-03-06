<?php
/**
 * Some important helper functions
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */

/**
 * Autoloads classes from the classes folder - ALL CLASSES MUST BE IN THE CLASSES FOLDER!!!
 * @param string $class_name 
 */
function __autoload($class_name) {
    $class_folders = array('database', 'erudition', 'forum', 'quiz', 'users', 'core', 'text');
    
    foreach($class_folders as $folder) {
        if(file_exists('../classes/' . $folder . '/' . $class_name . '.php')) {
            require_once '../classes/' . $folder . '/' . $class_name . '.php';
        }
    }
}

/**
 * Replaces tokens in a given string with replacements given
 * @param string $text
 * @param array $tokens_replacements
 * @return string 
 */
function replace_tokens($text, $tokens_replacements) {
    foreach($tokens_replacements as $token => $replacement) {
        $text = preg_replace('(\{' . $token . '\})', $replacement, $text);
    }
    
    return $text;
}

/**
 * Turns a multidimensional array into a single dimensional array
 * @param array $array
 * @return array 
 */
function array_flat($array) {
    $single = array();
    foreach($array as $one) {
        foreach($one as $key => $value) {
            $single[$key] = $value;	
        }
    }
    return $single;
}

/**
 * Takes two string and explodes them, making the values in the first string the keys in the second one
 * The two strings must use the same delimeter and they must have the same number of elements after exloding
 * @param string $delimeter
 * @param string $keys
 * @param string $values
 * @return array 
 */
function explosion($delimeter, $keys, $values) {
    $returning = array();
    
    $key_array = explode($delimeter, $keys);
    
    $value_array = explode($delimeter, $values);
    
    foreach($key_array as $key => $val) {
        $returning[$val] = $value_array[$key];
    }
    
    return $returning;
}

/**
 * Shuffles an associative array while making sure the values keep with the keys
 * @param array $array 
 */
function shuffle_assoc(&$array) { 
   $random_array = array();
   $keys = array_keys($array);
   shuffle($keys);
   foreach($keys as $key) {
       $random_array[$key] = $array[$key];
       unset($array[$key]);
   }
   $array = $random_array;
} 

/**
 * Counts how many booleans there are in an array and returns an array with those numbers
 * @param array $array
 * @return array 
 */
function array_count_booleans($array) {
    $returns = array('true' => 0, 'false' => 0);
    
    foreach($array as $arr) {
        if($arr == true) {
            $returns['true'] += 1;
        } elseif($arr == false) {
            $returns['false'] += 1;
        }
    }
    
    return $returns;
}

/**
 * Sets a cookie with the name and value given that stays alive for the number of days given
 * @param string $cookie_name
 * @param mixed $cookie_value
 * @param int $days_alive 
 */
function set_cookies($cookie_name, $cookie_value, $days_alive = 30) {
    $expires = time() + 60 * 60 * 24 * $days_alive;
    setcookie($cookie_name, $cookie_value, $expires, '/');
}

/**
 * Unsets a cookie by its name
 * @param string $cookie_name 
 */
function unset_cookie($cookie_name) {
    $expires = time() - 30 * 24 * 60 * 60;
    setcookie($cookie_name, '', $expires, '/');
}

/**
 * Sets a session variable with the name and value given if a session has been started
 * @param string $session_name
 * @param mixed $session_value
 * @return boolean 
 */
function set_session($session_name, $session_value) {
    if(isset($_SESSION)) {
        $_SESSION[$session_name] = $session_value;
        return true;
    } else {
        return false;
    }
}

/**
 * Executes a header reload
 * @param string $where
 * @return void 
 */
function reload($where = '') {
    $base = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    $base = (isset($_GET['page'])) ? str_replace($_GET['page'], '', $base) : $base;
    $base = str_replace('index.php', '', $base);
    if(!empty($where)) {
        header('Location: ' . $base . $where);
        exit;
    } elseif(isset($_GET['page'])) {
        header('Location: /' . $base . $_GET['page']);
        exit;
    } else {
        header('Location: ' . $base);
        exit;
    }
}

/**
 * Checks if a password is strong, ie has 8 characters, 1 lowercase and 1 uppercase
 * @param string $password
 * @return boolean 
 */
function password_is_strong($password) {
    if(preg_match('(^.*(?=.{8})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$)', $password)) return true;
    return false;
}

/**
 * Checks a given email against a regular expression to validate that it is in fact an email address
 * @param string $email
 * @return boolean 
 */
function is_email_address($email) {
    if(preg_match('(^.*[a-zA-Z0-9-_\.]+@.*[a-zA-Z0-9-_\.]+[\.]+.*[a-z\.]{2,6})', $email)) return true;
    return false;
}

/**
 * Checks if a session or cookie is set, which means a user is logged in
 * @return boolean 
 */
function is_logged_in() {
    if(isset($_SESSION['e_user']) || isset($_COOKIE['user'])) return true;
    else return false;
}

function erudition_errors($error_code, $error_desc, $file, $line) {
    @ob_end_clean();
    $message = <<<EOT
    The following error occured in $file on line $line: \r\n
        Error $error_code: $error_desc\r\n
        \r\n 
EOT;
    if(function_exists('error_log')) {
        error_log($message, 3, 'error_log');
    } else {
        if(file_exists('error_log')) {
            $contents = file_get_contents('error_log');
            file_put_contents('error_log', $contents . $message);
        } else {
            file_put_contents('error_log', $message);
        }
    }
    reload('404/');
}

?>