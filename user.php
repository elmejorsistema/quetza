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

class user{
  
  // stores the user id
  public $id;

  // stores the user security session is
  public $security_id;

  // stores the user (human) name
  public $humanname;

  // stores the curp
  public $curp;

  // sores the user type
  public $tipo;


  
    function __construct($user_id, $security_id, $humanname, $curp, $tipo)
  {   
    $this->id          = $user_id;
    $this->security_id = $security_id;
    $this->humanname   = $humanname;
    $this->curp        = $curp;
    $this->tipo        = $tipo;
    
    return;   
  }// end constructor

  function __destruct()
  {
    $this->id         = null;
    $this->session_id = null;
    $this->humanname  = null;
    $this->curp       = null;
  }// end destructor

}

?>