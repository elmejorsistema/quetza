<?php


/*
************+++***********************
Created:   Manuel José Contreras Maya
Email:     manuel@agenteel.com
Date       2008/12/16
************+++***********************
**************************************
****** Do not share this code ********
       ^^^^^^^^^^^^^^^^^^^^^^
************+++***********************
************+++***********************
*/


class security{

  //stores the security_id
  public $id;


  function __construct($session_id){
    $this->idSafe($session_id);
  }

  function __destruct(){
    $this->id = null;
  }


  function idSafe($session_id)
  { 
    $this->id = null;

    for ($counter = 0; $counter < 4; $counter++)
      {
	switch($counter)
	  {
	  case 0:
	    $this->id += ord($session_id[2])*7239;
	    break;
	  case 1:
	    $this->id += ord($session_id[6])*809;
	    break;
	  case 2:
	    $this->id += ord($session_id[0])+564;
	    break;
	  case 3:
	    $this->id += ord($session_id[7])*1279;
	    break;
	  }
      } 
  }


  function checkSession($user_id,$user_security_id,$session_id, $db)
  {
     //creates the security_id with the session_id
     $this->idSafe($session_id);

     //get security_id from user table
     $query = "select security_id from user where id=$user_id";
    
     $db->query_fetch_row($query);
     
     //check the result
     if(!empty($db->query_row)){
       //compares that the session_id  equals to security_id in db and 
        //the session_id is equals to the user security_id
        if($this->id==$db->query_row[0] && $this->id==$user_security_id)
           return true;
        else
	   return false;
     }
     else
      return false;
  }

  
}

?>
