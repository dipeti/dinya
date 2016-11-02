<?php

class User
{

    private $_sql,
            $_loggedIn,
            $_data;

    public function __construct(){

        $this->_loggedIn = false;
        $this->_sql = DB::getInstance();
    }
    public function getData(){
        return $this->_data;
    }

    /**
     * @return Boolean
     */
    public function getLoggedIn()
    {
        return $this->_loggedIn;
    }

    public function findByUsername($username){
        if($username){
            $result = $this->_sql->select('users', ['username','=', $username]);
            if($result->getRowNum()){
                $this->_data = $result->getFirst();
                return true;
            }

        }
        return false;
    }
    public function logIn($username = null, $password = null){
            $found = $this->findByUsername($username);
        if($found){
          if(sha1($password) === $this->getData()->password){
              $_SESSION['user'] = $username;
              return true;
          }
        }
        return false;
    }

    /**
     * @param Boolean $loggedIn
     */
    public function setLoggedIn($loggedIn)
    {
        $this->_loggedIn = $loggedIn;
    }

}