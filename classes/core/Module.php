<?php
/**
 * Description of modules
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Module {
    public $module_id;
    
    public $module_name;
    
    public $module_path;
    
    public $module_is_active;
    
    protected $db_wrapper;
    
    public function __construct(DBWrapper $db_wrapper, $module_id = null) {
        $this->db_wrapper = $db_wrapper;
        
        if($module_id) {
            $this->select_single_module($module_id);
        }
    }
    
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
