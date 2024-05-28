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


class config{

  // stores the first control structure id
  public $control_structure_id1;

  // stores the seconf control structure id
  public $control_structure_id2;

  // stores the company name
  public $company_name;

  // stores el ciclo
  public $ciclo;

  // stores el ciclo
  public $tipo;
    
  // stores the student id
  public $alumno_id;

  // muestra o no todos los ciclos (para pagos)  
  public $todos_los_ciclos;

  // El folio actual  
  public $folio;

  // Environment
    public $environment;

    // Domain
    public $domain;

    

  function __construct($control_structure_id1, $control_structure_id2, $company_name, $ciclo, $tipo, $environment)
  {   
    $this->control_structure_id1 = $control_structure_id1;
    $this->control_structure_id2 = $control_structure_id2;
    $this->company_name          = $company_name;
    $this->ciclo                 = $ciclo;
    $this->tipo                  = $tipo;
    $this->alumno_id             = null;
    $this->todos_los_ciclos      = false;
    $this->folio                 = null;
    $this->environment           = $environment;
    if($environment == 'DEVELOPMENT') {
        $this->domain = "http://localhost/~manuel/quetza/img/";
    }else{
        $this->domain = "https://intranet.quetza.edu.mx/img/";
    }

    return;   
  }// end constructor

  function __destruct()
  {
    //
  }// end destructor
 
}

?>