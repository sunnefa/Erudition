<?php
/**
 * Description of pages
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Page {
    /**
     * The id of the selected page
     * @var int 
     */
    public $page_id;
    
    /**
     * The title of the selected page
     * @var string 
     */
    public $page_title;
    
    /**
     * The url of the selected page
     * @var string 
     */
    public $page_url;
    
    /**
     * The name of the selected page
     * @var string 
     */
    public $page_name;
    
    /**
     * The meta description of the selected page
     * @var string 
     */
    public $page_meta_description;
    
    /**
     * The modules on the current page
     * @var array 
     */
    public $page_modules;
    
    /**
     * A reference to the DBWrapper
     * @var type 
     */
    protected $db_wrapper;
    
    /**
     * Constructs the page object
     * @param DBWrapper $db_wrapper
     * @param int $page_id 
     */
    public function __construct(DBWrapper $db_wrapper, $page_id = null) {
        $this->db_wrapper = $db_wrapper;
        
        if($page_id) {
            $this->select_single_page($page_id);
        }
    }
    
    /**
     * Selects a single page from the database
     * @param int $page_id 
     */
    private function select_single_page($page_id) {
        $page = $this->db_wrapper->select_data('pages__pages', '*', 'page_id = ' . $page_id);
        
        if($page) {
            $page = array_flat($page);
            
            $this->page_id = $page['page_id'];
            
            $this->page_title = $page['page_title'];
            
            $this->page_url = $page['page_url'];
            
            $this->page_name = $page['page_name'];
            
            $this->page_meta = $page['page_meta_description'];
        } else {
            echo 'No page found';
        }
    }
    
    /**
     * Loads the modules assigned to that page
     */
    public function load_page_modules() {
        $module_ids = $this->db_wrapper->select_data('pages__pages_modules', 'module_id', "page_id = " . $this->page_id, null, "display_order");
        
        foreach($module_ids as $module) {
            $this->page_modules[] = new Module($this->db_wrapper, $module['module_id']);
        }
    }
    /**
     * TODO: Select multiple pages 
     */
    public function select_multiple_pages() {
        
    }
}

?>
