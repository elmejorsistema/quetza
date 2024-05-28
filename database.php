<?php

/*
************+++***********************
Created:   Manuel José Contreras Maya
Email:     manuel@agenteel.com
Date       2014
************+++***********************
**************************************
****** Do not share this code ********
       ^^^^^^^^^^^^^^^^^^^^^^
************+++***********************
************+++***********************
*/




class database{

  //stores the host
  public $db_host;

  //stores the db name
  public $db_name;

  //stores the db user
  public $db_user;

  //stores the db user
  public $db_password;

  // stores the connection status
  public $status;

  // stores the connection
  public $link_id;

  // stores the query result
  public $query_result;

  // stores the query number of rows
  public $query_num_rows;

  // stores the row
  public $query_row;

  // stores the field
  public $query_field;

  // stores the assign operation
  public $query_error;



  function __construct($db_host, $db_name, $db_user, $db_password)
  {
    $this->db_host     = $db_host;
    $this->db_name     = $db_name;
    $this->db_user     = $db_user;
    $this->db_password = $db_password;

    $this->query_result   = null;
    $this->query_num_rows = null;
    $this->query_row      = null;
    $this->query_field    = null;
    $this->query_error    = null;

    $this->initialconnect();

  }// end constructor


  // for  now the disconnect function is enough
  // so, this does nothing by now
  function __destruct()
  {
    if($this->link_id){
      $this->link_id = null;
    }

  }// end destructor



  /*
   status codes
   0 - cannot connect to the DBMS
   1 - cannot connect to the database
   2 - connected!
  */

  function initialConnect()
  {
    try {
      $this->link_id = new PDO("mysql:host=$this->db_host;dbname=$this->db_name;charset=utf8mb4", $this->db_user, $this->db_password,
          array(
              PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
          ));
      $this->link_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->status = 2;
      return;
    } catch (PDOException $e) {
      $this->status = 0;
      echo "Connection failed: " . $e->getMessage();
    }
  }


  function connect()
  {
    try {
      $this->link_id = new PDO("mysql:host=$this->db_host;dbname=$this->db_name;charset=utf8mb4", $this->db_user, $this->db_password,
          array(
              PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
          ));
      // Set the PDO error mode to exception
      $this->link_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->status = 2;
      //echo "Connected successfully";
      return;
    } catch (PDOException $e) {
      $this->status = 0;
      echo "Connection failed: " . $e->getMessage();
    }
  }


  function disconnect()
  {
    if($this->link_id)
      $this->link_id = null;
    $this->status         = 0;
    $this->query_result   = null;
    $this->query_num_rows = null;
    $this->query_row      = null;

  }


  function query_rows($s_query)
  {
    //echo $s_query;return;
    $this->link_id->exec("SET SESSION sql_mode=(SELECT REPLACE(@@SESSION.sql_mode,'ONLY_FULL_GROUP_BY',''))");
    $this->query_result   = $this->link_id->query($s_query)->fetchAll();
    if($this->query_result)
      $this->query_num_rows = count($this->query_result);
    else
      $this->query_num_rows = null;
  }

  function query_fetch_row($s_query)
  {
    $this->query_result = $this->link_id->query($s_query)->fetchAll();
    if($this->query_result)
    {
      if(count($this->query_result) != 1) {
        $this->query_row = null;
      }
      else
      {
        $this->query_row = $this->query_result[0];
        return true;
      }
    }
    else
    {
      $this->query_row = null;
    }

    return false;

  }


  // query to get a field from query
  function query_fetch_field($s_query){
    $this->query_result   = $this->link_id->query($s_query)->fetch();
    if($this->query_result){
      $this->query_row = $this->query_result[0];
      //var_dump($this->query_result[0]);
      //var_dump($s_query);
      $this->query_field = $this->query_row;
    }else
      $this->query_field = null;
  }


  // query to make an asignment
  function query_assign($s_query)
  {
    $this->query_result = $this->link_id->query($s_query);
    if($this->query_result == false)
      $this->query_error = true;
    else
      $this->query_error = null;
  }

}

?>
