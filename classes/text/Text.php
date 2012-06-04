<?php
/**
 * An object representing a text module on a page
 * The same page can have many texts but currently the same text can only be on one page. This class only has retrieve methods
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Text {
    /**
     * The id of the selected text
     * @var int
     */
    public $text_id;
    
    /**
     * The name of the selected text
     * @var string
     */
    public $text_name;
    
    /**
     * The actual text
     * @var string
     */
    public $text;
    
    /**
     * The id of the page the text is on - note one page can have more than one text but the same text cannot be on many pages
     * @var int
     */
    public $page_id;
    
    /**
     * Holds a reference to the database wrapper
     * @var DBWrapper 
     */
    protected $db_wrapper;
    
    /**
     * Constructor of the text object
     * @param DBWrapper $db_wrapper
     * @param int $page_id 
     */
    function __construct(DBWrapper $db_wrapper, $page_id = null) {
        $this->db_wrapper = $db_wrapper;
        
        if($page_id) {
            $this->select_text($page_id);
        }
    }
    
    /**
     * Selects the text based on the page_id
     * I decided to have it like that because I find it unlikely you would know the text id and 
     * because of the one-to-many relationship between text and pages
     * @param type $page_id 
     */
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
    
    public function get_text_by_id($text_id) {
        $text = $this->db_wrapper->select_data('pages__text', '*', "text_id = $text_id");
        if($text) {
            $text = array_flat($text);
            
            $this->text_id = $text['text_id'];
            $this->text = $text['text'];
            $this->text_name = $text['text_name'];
            $this->page_id = $text['page_id'];
        }
    }
}

?>
