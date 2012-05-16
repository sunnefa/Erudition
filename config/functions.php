<?php
/**
 * Autoloads classes from the classes folder - ALL CLASSES MUST BE IN THE CLASSES FOLDER!!!
 * @param string $class_name 
 */
function __autoload($class_name) {
    $class_folders = array('database', 'erudition', 'forum', 'quiz', 'users', 'core');
    
    foreach($class_folders as $folder) {
        if(file_exists('../classes/' . $folder . '/' . $class_name . '.php')) {
            require_once '../classes/' . $folder . '/' . $class_name . '.php';
        }
    }
}

/**
 * Replaces tokens in a given string with replacements given
 * TODO: Decide wether to use str_replace or preg_replace
 * @param string $text
 * @param array $tokens_replacements
 * @return string 
 */
function replace_tokens($text, $tokens_replacements) {
    foreach($tokens_replacements as $token => $replacement) {
        //$text = str_replace('{' . $token . '}', $replacement, $text);
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

?>