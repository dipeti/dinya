<?php

class DB
{
    /**
     * @var DB null
     */
    private static $_instance = null;

    private $_handler,
            $_query,
            $_results,
            $_error = false,
            $_rowNum = 0;

    private function __construct()
    {
       try{
           $connStr=  "mysql:host=".DBHOST.";dbname=".DBNAME;
            $this->_handler = new PDO($connStr,DBUSER,DBPASSWD);
       }
       catch(PDOException $e){
        die($e->getMessage());
        }
    }
    public static function getInstance(){
        if(!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }

    public function selectAll($table){
       return $this->action("SELECT *", $table);
    }
    private function execute($sql, $params = array()){
        $this->_error = false;
        if($this->_query = $this->_handler->prepare($sql)){
            $i = 1;
            foreach ($params as $param) {
                $this->_query->bindValue($i++,$param);
            }
        }
        if($this->_query->execute()){
            $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
            $this->_rowNum = $this->_query->rowCount();
        }
        else{
            $this->_error = true;
        }
        return $this; //comes in handy when checking error
    }
    private function action($action, $table, $where = array()){
        if(count($where)==3){
            $operators  = array('=','>', '<', '>=', '<=');
            $field      = $where[0];
            $operator   = $where[1];
            $value      = $where[2];
            if(in_array($operator, $operators)){
                $sql =  "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if(!$this->execute($sql,array($value))->_error){
                    return $this;
                }
            }
        }
        elseif(count($where)==0){
            $sql = "{$action} FROM {$table}";
            if(!$this->execute($sql)->_error){
                return $this;
            }
        }
        return false;

    }

    public function select($table, $where = array()){
       return $this->action("SELECT *", $table, $where);
    }
    public function insert($table, array $params){
        $values = array();
        foreach ($params as $param) {
            $values[] = "?";
        }
        $sql = "INSERT INTO {$table} (" .implode(', ',array_keys($params)).") VALUES (".implode(', ', $values).")";
        if(!$this->execute($sql, $params)->_error){
            return true;
        }
        return false;

    }
    public function delete($table, $where = array()){
       return $this->action("DELETE", $table, $where)->_error;
    }
    public function update($table, $params, $id){
        $updates = array();
        foreach ($params as $key => $param) {
            $updates[] = $key. "=". "?";
        }
        $field = $this->getFields($table);
        $sql = "UPDATE {$table} SET ". implode(', ', $updates) ." WHERE {$field[0]} = {$id}";

        if(!$this->execute($sql, $params)->_error){
            return true;
        }
        return false;
    }
    public function getFields($table){
        $fields = array();
        $sql = "DESC {$table}";

        if( $statement = $this->_handler->query($sql, PDO::FETCH_ASSOC)){
        foreach ($statement as $item) {
            $fields[] = $item['Field'];
        }
        return $fields;
        }
        return false;
    }
    public function getError()
    {
        return $this->_error;
    }
    public function getRowNum()
    {
        return $this->_rowNum;
    }
    public function getLastID()
    {
        return $this->_handler->lastInsertId();
    }
    public function getFirst(){
       return $this->_results[0];
    }
    public function __toString()
    {
        $string = "";
        foreach ($this->getResults() as $result) {
            foreach ($result as $key => $prop) {
                $string.=$key. " - ".$prop;
                $string.= " | ";
            }
            $string.=nl2br("\n");
        }
        return $string;
    }


    public function getResults()
    {
        return $this->_results;
    }

}