<?php
/**
 * Abstract database wrapper. All child classes must implement these methods
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
abstract class DBWrapper {
    /**
     * The database username
     * @var string 
     */
    protected $user;
    
    /**
     * The database password
     * @var string 
     */
    protected $pass;
    
    /**
     * The database hostname
     * @var string 
     */
    protected $host;
    
    /**
     * The database name
     * @var string
     */
    protected $data;
    
    /**
     * A reference to the connection
     * @var link identifier 
     */
    protected $conn;
    
    abstract public function select_data($table, $fields, $where = 1, $limit = null, $order = null, $group = null, $join = null);
    
    abstract public function connect();
    
    abstract public function close();
    
    abstract public function execute($query);
    
    abstract public function delete_data($table_name, $where = 1);
    
    abstract public function insert_data($table_name, $fields_data);
    
    abstract public function replace_data($table_name, $fields_data, $where);
    
    abstract public function update_data($table_name, $fields_data, $where);
}

?>
