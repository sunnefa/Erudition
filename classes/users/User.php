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

                    $online = $this->db_wrapper->update_data('users__users', array('user_is_online' => '1'), 'user_id = ' . $user['user_id']);

                    set_cookies('user', $user['user_id']);
                    set_session('user', $user['user_id']);
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
            $this->user_email (isset($user_array['user_email'])) ? $user_array['user_email'] : '';
            
            $this->user_image = $this->build_gravatar_url();
    }
    
    public function load_multiple_users($number = all, $order_by = 'user_id') {
        $limit = (is_integer($number)) ? $number : '';
        
        $users = $this->db_wrapper->select_data('users__users', '*', null, $limit, $order_by);
        
        if($users) {
            foreach($users as $key => $user) {
                $users[$key]['image'] = "http://www.gravatar.com/avatar/";
        
                $users[$key]['image'] .= md5(strtolower(trim($user['user_email'])));
        
                $users[$key]['image'] .= "?s=100&d=http://localhost/Erudition/public_html/img/default_user.png&r=g";
            }
            return $users;
        } else {
            echo 'No users found';
        }
    }
    
    /**
     * Adds a new user to the database
     * @param string $first_name
     * @param string $last_name
     * @param string $email
     * @param string $password
     * @return boolean 
     */
    public function register_user($first_name, $last_name, $email, $password) {
        $activation_key = $this->generate_activation_key($email);
        $reg = $this->db_wrapper->insert_data('users__users', array(
            'user_first_name' => $first_name,
            'user_last_name' => $last_name,
            'user_password' => $password,
            'user_email' => $email,
            'user_registered_since' => date('Y-m-d H:i:s'),
            'user_last_logged_in_date' => '00-00-00 00:00:00',
            'user_is_active' => 0,
            'user_uses_gravatar' => 1,
            'user_is_online' => 0,
            'user_activation_key' => $activation_key
            ));
        
        if($reg) {
            $this->send_activation_email($first_name, $last_name, $email, $activation_key);
            return true;
        }
        return false; 
    }
    
    /**
     * Sends an activation email to a user
     * @param string $first_name
     * @param string $last_name
     * @param string $email
     * @param string $activation_key
     * @return boolean 
     */
    private function send_activation_email($first_name, $last_name, $email, $activation_key) {
        $admin_email = 'regs@erudition.com';
        $subject = 'Welcome to Erudition ' . $first_name . '!';
        $to = $email;
        $url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $url = str_replace('/index.php', '', $url);
        $message = <<<EOT
        <p>Hi $first_name $last_name!</p>
            <p>Welcome to Erudition. This email is the first on your journey to become an Erudite. Before you can login, we need to verify your account. Please click the link below to activate your account and login to start enjoying our courses and becoming an Erudite of the highest quality.</p>
            <p><a href="$url/signup/?activation=$activation_key">Activate my account!</a></p>
                <p>Best regards,</p>
                <p>The Erudition team</p>
EOT;
        $message = wordwrap($message, 70);
        $headers = "From: The Erudition team<" . $admin_email . ">\r\n";
        $headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
        $sending = mail($to, $subject, $message, $headers);
        if(!$sending) return false; else return true;
    }
    
    /**
     * Generates an activation key by randomizing, then hashing the user's email address
     * @param type $string
     * @return type 
     */
    private function generate_activation_key($string) {
        return md5(sha1(str_shuffle($string)));
    }
}

?>
