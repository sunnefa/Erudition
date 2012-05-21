<?php
/**
 * Description of User
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class User {
    /**
     * The user's id
     * @var int
     */
    public $user_id;
    
    /**
     * The user's first name
     * @var string
     */
    public $user_first_name;
    
    /**
     * The user's last name
     * @var string
     */
    public $user_last_name;
    
    /**
     * Does this user use gravatar?
     * @var int
     */
    public $user_uses_gravatar;
    
    /**
     * The user's email address
     * @var string
     */
    public $user_email;
    
    /**
     * The user's password
     * @var string
     */
    public $user_password;
    
    /**
     * The day the user registered
     * @var date
     */
    public $user_registered_since;
    
    /**
     * Is the user online right now?
     * @var int
     */
    public $user_is_online;
    
    /**
     * The user's biography
     * @var string
     */
    public $user_bio;
    
    /**
     * The date this user last logged in
     * @var date
     */
    public $user_last_logged_in_date;
    
    /**
     * The ip address this user last logged in from
     * @var string
     */
    public $user_last_logged_in_from;
    
    /**
     * A randomly generated key used when verifying that the user is human and their email address is correct
     * @var string
     */
    public $user_activation_key;
    
    /**
     * Has this user been activated?
     * @var int
     */
    public $user_is_active;
    
    /**
     * The gravatar or default image of this user
     * @var string
     */
    public $user_image;
    
    /**
     * A reference to the Database wrapper
     * @var DBWrapper
     */
    protected $db_wrapper;
    
    /**
     * Constructs the user object
     * @param DBWrapper $db_wrapper
     * @param int $user_id 
     */
    public function __construct(DBWrapper $db_wrapper, $user_id = null) {
        $this->db_wrapper = $db_wrapper;
        
        if($user_id) {
            $this->select_user($user_id);
        }
    }
    
    /**
     * Selects a single user from the database
     * @param int $user_id 
     */
    private function select_user($user_id) {
        $user = $this->db_wrapper->select_data('users__users', '*', "user_id = $user_id");
        
        if($user) {
            $user = array_flat($user);
            $this->assign_values_to_properties($user);
        }
    }
    
    /**
     * Builds up a gravatar url from the user's email address
     * @see http://en.gravatar.com/site/implement/images/php/
     */
    private function build_gravatar_url() {
        $this->user_image = "http://www.gravatar.com/avatar/";
        
        $this->user_image .= md5(strtolower(trim($this->user_email)));
        
        $this->user_image .= "?s=100&d=http://localhost/Erudition/public_html/img/default_user.png&r=g";
    }
    
    /**
     * Checks if the given email and password match and logs the user in if they do
     * @param type $email
     * @param type $password 
     * @return boolean
     */
    public function user_login($email, $password) {
        $user = $this->db_wrapper->select_data('users__users', '*', "user_email = '$email'");
        
        if($user) {
            $user = array_flat($user);
            
            if($password == $user['user_password']) {
                if($user['user_is_active'] == 1) {
                    $this->assign_values_to_properties($user);

                    $online = $this->db_wrapper->update_data('user__users', array('user_is_online' => '1'), 'user_id = ' . $user['user_id']);

                    set_cookies('user', $user['id']);
                    set_session('user', $user['id']);
                    return true;
                } else {
                    return false;
                }
                
            } else {
                return false;
            }
            
        } else {
            return false;
        }
    }
    
    /**
     * Checks that the values needed are in the given $user_array and assigns them to the User object's properties
     * @param type $user_array 
     */
    private function assign_values_to_properties($user_array) {
            $this->user_activation_key = (isset($user_array['user_activation_key'])) ? $user_array['user_activation_key'] : '';
            
            $this->user_bio = (isset($user_array['user_bio'])) ? $user_array['user_bio'] : '';
            
            $this->user_first_name = (isset($user_array['user_first_name'])) ? $user_array['user_first_name'] : '';
            
            $this->user_id = (isset($user_array['user_id'])) ? $user_array['user_id'] : '';
            
            $this->user_is_active = (isset($user_array['user_is_active'])) ? $user_array['user_is_active'] : '';
            
            $this->user_is_online = (isset($user_array['user_is_online'])) ? $user_array['user_is_online'] : '';
            
            $this->user_last_logged_in_date = (isset($user_array['user_last_logged_in_date'])) ? $user_array['user_last_logged_in_date'] : '';
            
            $this->user_last_logged_in_from = (isset($user_array['user_last_logged_in_from'])) ? $user_array['user_last_logged_in_from'] : '';
            
            $this->user_last_name = (isset($user_array['user_last_name'])) ? $user_array['user_last_name'] : '';
            
            $this->user_password = (isset($user_array['user_password'])) ? $user_array['user_password'] : '';
            
            $this->user_registered_since = (isset($user_array['user_registered_since'])) ? $user_array['user_registered_since'] : '';
            
            $this->user_uses_gravatar = (isset($user_array['user_uses_gravatar'])) ? $user_array['user_uses_gravatar'] : '';
            
            $this->user_image = $this->build_gravatar_url();
    }
}

?>
