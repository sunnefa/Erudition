<?php
/**
 * Represents a single module
 * This class only has retrieve methods
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Module {
    /**
     * The id of the module
     * @var int 
     */
    public $module_id;
    
   /**
    * The name of the module
    * @var string
    */
    public $module_name;
    
    /**
     * The path to the module controller file
     * @var string
     */
    public $module_path;
    
    /**
     * A boolean indicating wether this module is active or not
     * @var int 
     */
    public $module_is_active;
    
    /**
     * A reference to DBWrapper
     * @var DBWrapper 
     */
    protected $db_wrapper;
    
    /**
     * Constructor, accepts DBWrapper and an optional module id
     * @param DBWrapper $db_wrapper
     * @param int $module_id 
     */
    public function __construct(DBWrapper $db_wrapper, $module_id = null) {
        $this->db_wrapper = $db_wrapper;
        
        if($module_id) {
            $this->select_single_module($module_id);
        }
    }
    
    /**
     * Select a single module
     * @param int $module_id 
     */
    private function select_single_module($module_id) {
        $module = $this->db_wrapper->select_data('pages__modules', '*', "module_id = " . $module_id);
        
        if($module) {
            $module = array_flat($module);
            $this->module_id = $module['module_id'];
            
            $this->module_is_active = $module['module_is_active'];
            
            $this->module_name = $module['module_name'];
            
            $this->module_path = $module['module_path'];
        } else {
            echo 'No modules found';
        }
    }
}

?>
