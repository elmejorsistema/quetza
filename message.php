<?php

class message{
  
 
  //stores the message
  public $message;

  function __construct(){   
    $this->messaeg = null;
  }// end constructor


  function __destruct(){
  }// end destruct
 

  function show_message($message_id,$o_database){
     $query="select message from message where id = $message_id";

     $o_database->query_fetch_row($query);
    
     if($o_database->query_row)
       $this->message = $o_database->query_row[0];

     return $this->message;
             
  }
}

?>
