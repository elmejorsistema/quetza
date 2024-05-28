<?php

class session{
  
  // stores the connection status
  public $status;
  // stores the connection
  public $link_id;

  /*
   status codes
   0 - cannot connect to the DBMS
   1 - cannot connect to the database
   2 - connected!
  */
  function __construct($db_host, $db_name, $db_user, $db_password)
  {   
   $this->link_id = mysql_connect($db_host, $db_user, $db_password);
   
   if(!$this->link_id) 
     {
      $this->status =  0;
      return;
     }

   if(!mysql_select_db($db_name))
     {
       $this->status =  1;
       return;
     }

   $this->status =  2;
   return;   
  }// end constructor

  function __destruct()
  {
    if($this->status > 0)
      mysql_close( $this->link_id);
  }// end destructor
}

?>