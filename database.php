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
    //if($this->link_id)
    //mysql_close($this->link_id);
  }// end destructor



  /*
   status codes
   0 - cannot connect to the DBMS
   1 - cannot connect to the database
   2 - connected!
  */
  function initialconnect()
  {
    $this->link_id = mysql_connect($this->db_host, $this->db_user, $this->db_password, true);
    
    if(!$this->link_id) 
      {
	$this->status = 0;
	return;
      }
    
    if(!mysql_select_db($this->db_name))
      {
	$this->status = 1;
	return;
      }
    
    $this->status = 2;

    $this->query_assign("set names 'utf8'");
    //    $this->query_assign("set names 'latin1'");

    return;   
  }


 function connect()
  {
    $this->link_id = mysql_connect($this->db_host, $this->db_user, $this->db_password, false);
    
    if(!$this->link_id) 
      {
	$this->status = 0;
	return;
      }
    
    if(!mysql_select_db($this->db_name))
      {
	$this->status = 1;
	return;
      }
    
    $this->status = 2;
    $this->query_assign("set names 'utf8'");
    return;   
  }











  function disconnect()
  {
    if($this->link_id)
      mysql_close($this->link_id);
    $this->status         = 0;
    $this->query_result   = null;
    $this->query_num_rows = null;
    $this->query_row      = null;

  }
 
  
  // query to get an arrary of results
  function query_rows($s_query)
  {
    $this->query_result   = mysql_query($s_query, $this->link_id);
    if($this->query_result)
      $this->query_num_rows = mysql_num_rows($this->query_result);
    else
      $this->query_num_rows = null;
  }


  // query to get only one result
  function query_fetch_row($s_query)
  {
    $this->query_result   = mysql_query($s_query, $this->link_id);
    if($this->query_result)
      {
	//check that returns only one row
	if(mysql_num_rows($this->query_result) != 1)
	  $this->query_row = null;
	else
	  {
	  $this->query_row = mysql_fetch_row($this->query_result);
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
    $this->query_result   = mysql_query($s_query, $this->link_id);
    if($this->query_result){
      $this->query_row = mysql_fetch_row($this->query_result);
      $this->query_field = $this->query_row[0];
    }else
      $this->query_field = null;
  }


  // query to make an asignment
  function query_assign($s_query)
  {
    $this->query_result = mysql_query($s_query, $this->link_id);
    if($this->query_result == false)
      $this->query_error = true;
    else
      $this->query_error = null;
  }

}

?>
