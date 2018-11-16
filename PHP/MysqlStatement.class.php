<?php

class MysqlStatement {

  private $error_msg;
  private $error_code;
  private $query = false;
  private $insert_id = false;
  private $num_rows = false;
  private $sql = false;
  private $db_connection = false;

  /**
   * Constructor
   */
  public function __construct($db_connection, $sql) {
    
    $this->sql = $sql;
    $this->db_connection = $db_connection;
    
  }

  /**
   * Getter Function for inserted id; num rows; error msg and error code
   */
  public function __get($name) {
    switch ($name) {
      
      case'insert_id':
        return $this->insert_id;
        break;

      case'num_rows':
        return $this->num_rows;
        break;

      case'sql':
        return $this->sql;
        break;
      
      case'error_msg':
        return $this->error_msg;
        break;
      
      case'error_code':
        return $this->error_code;
        break;
      
    }
  }

  /**
   * Execute mySQL Statement
   */
  public function execute() {
    
    $args = func_get_args();
    
    $patter = "!:(\d)(\d)?!";
    $replace = ":::\\1\\2";
    $this->sql = preg_replace($patter, $replace, $this->sql);

    foreach ($args as $key => $value) {

      $pattern = "!:::" . $key . "\b!";

      $this->sql = preg_replace($pattern, "'" . 
                                $this->db_connection->real_escape_string($value) . "'", 
                                $this->sql);
    }

    @$this->query = $this->db_connection->query($this->sql);
    $this->num_rows = $this->query->num_rows;
    $this->insert_id = $this->db_connection->insert_id;
    $this->error_msg = $this->db_connection->error;
    $this->error_code =  $this->db_connection->errno;
    
  }

  /**
   * Check if Database entry exist
   */
  public function exist() {
    
    return $this->num_rows ? true : false;
    
  }

  /**
   * Fetch array from Database
   */
  public function fetchArray() {
    
    return $this->query ? $this->query->fetch_array() : false;
    
  }

}

?>
