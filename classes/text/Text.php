<?php
/**
 * Description of Text
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Text {
    public $text_id;
    
    public $text_name;
    
    public $text;
    
    public $page_id;
    
    protected $db_wrapper;
    
    function __construct(MySQLWrapper $db_wrapper, $page_id = null) {
        $this->db_wrapper = $db_wrapper;
        
        if($page_id) {
            $this->select_text($page_id);
        }
    }
    
    private function select_text($page_id) {
        $text = $this->db_wrapper->select_data('pages__text', '*', "page_id = $page_id");
        
        if($text) {
            $text = array_flat($text);
            
            $this->text_id = $text['text_id'];
            
            $this->text_name = $text['text_name'];
            
            $this->text = $text['text'];
            
            $this->page_id = $text['page_id'];
            
        }
    }
}

?>
