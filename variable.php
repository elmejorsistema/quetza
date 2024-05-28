<?php
class Variable
{
  private $cs_id;
  private $o_db;
  private $o_config;
  private $s_created = array();
  private $method;
  public $prefix;

  public function importFiles()
  {
    $css_dir = "../css/spry";
    $js_dir = "../js/spry/1.61/includes_packed";

    //spry/1.61/includes_packed


    echo "<script type=\"text/javascript\" src=\"$js_dir/SpryValidationCheckbox.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"$js_dir/SpryValidationConfirm.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"$js_dir/SpryValidationPassword.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"$js_dir/SpryValidationRadio.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"$js_dir/SpryValidationSelect.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"$js_dir/SpryValidationTextarea.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"$js_dir/SpryValidationTextField.js\"></script>\n";

  
    echo "<link href=\"$css_dir/validation.css\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />\n";
    echo "<link href=\"$css_dir/SpryValidationSelect.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
    echo "<link href=\"$css_dir/SpryValidationPassword.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
    echo "<link href=\"$css_dir/SpryValidationRadio.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
    echo "<link href=\"$css_dir/SpryValidationTextField.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
    echo "<link href=\"$css_dir/SpryValidationCheckbox.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
    echo "<link href=\"$css_dir/SpryValidationConfirm.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
    echo "<link href=\"$css_dir/SpryValidationTextarea.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
  }
  

  public function __construct($cs,$o_database,$o_config,$method)
  {
    $this->cs_id = $cs;
    $this->o_db = $o_database;
    $this->o_db->connect();
    $this->o_config = $o_config;
    $this->method = $method;
  }

  public function reconnect($o_database)
  {
    $this->o_db = $o_database;
    $this->o_db->connect();
  }

  /*
   * Lee las variables de $method y hace
   * un match con la base de datos, de 
   * ser necesario, genera las variables
   * de sesion.
   */
  public function readVariables($method)
  {
    $this->method = $method;

    $sql = "select control_structure_id as cs_id, v.name as var_name, t.name as type_name, min_value, max_value, is_unsigned, create_session from cs_has_variable
            join variable as v on variable_id = v.id
            join type_variable as t on v.type_variable_id=t.id 
            join method on method.id = method_id and method.name = '$method' and control_structure_id = $this->cs_id";
    // echo $sql;
    //return;

    $this->o_db->query_rows($sql);
    $query = $this->o_db->query_result;
    $nVar = $this->o_db->query_num_rows;

    $variablesName = array();
    $c = 0;
    if($method == "post")
      foreach($_POST as $nombre=>$valor)
	{
	  $variablesName[$c] = $nombre;
	  $c++;
	}

    if($method == "get")
      foreach($_POST as $nombre=>$valor)
	{
	  $variablesName[$c] = $nombre;
	  $c++;
	}

    $lang = $this->o_config->language_id;
    $sql = "select control_structure_id, language_id, name from control_structure_has_language where control_structure_id=$this->cs_id and language_id=$lang";
    //echo $sql."<br />";
    $this->o_db->query_rows($sql);
    $q_texto = mysql_fetch_assoc($this->o_db->query_result);
    $texto = $q_texto['name'];
    $prefijo = $texto[0];

    $this->prefix = $prefijo;

    if($nVar > 0)
      {
	while($row = mysql_fetch_assoc($query))
	  {
	    $create_session = $row['create_session'];
	    $name = $row['var_name'];
	    if($create_session == 1)
	      {
		
		if($method == "post")
		  {
		    $_SESSION[$prefijo.'_'.$name] = $_POST[$name];
		    //echo "_SESSION[".$prefijo."_".$name."] = _POST[".$name."];<br />";
		  }

		if($method == "get")
		  {
		    $_SESSION[$prefijo.'_'.$name] = $_GET[$name];
		  }

		$type = $row['type_name'];
		$unsigned = $row['is_unsigned'];
		$min = $row['min_value'];
		$max = $row['max_value'];
		$var = $_SESSION[$prefijo.'_'.$name];
		
		//echo $prefijo.'_'.$name." = ".$var."<br />";
		
		if(!$this->validateVar($var,$type,$min,$max,$unsigned))
		  {
		    //var_dump($var,$type,$min,$max,$unsigned);
		    //echo "<font color=red>elimina $name</font><br />";
		    unset($_SESSION[$prefijo.'_'.$name]);
		  }
		else
		  $this->s_created[$prefijo.'_'.$name] = null;
	      }
	  }
      }

  }


  public function validateVar($var,$type,$min,$max,$unsigned)
  {
    /*
     * mysql> select * from type_variable;
     * +----+-----------+
     * | id | name      |
     * +----+-----------+
     * -|  1 | email     |
     * -|  2 | integer   |
     * -|  3 | decimal   |
     * |  4 | telephone |
     * -|  5 | date      |
     * |  6 | string    |
     * -|  7 | float     |
     * +----+-----------+
    */

    switch($type)
      {
      case "float":
      case "decimal":
	if(!is_numeric($var)){
	  //echo "no numerico";
	  return false;
	}
	if(!($var >= $min and $var <= $max)){
	  //echo "fuera de rango";
	  return false;
	}
	if($unsigned == 1)
	  if($var < 0)
	    return false;
	break;

      case "integer":
	$var = intval($var);
	if(!is_int($var)){
	  //echo "no es entero";
	  return false;
	}
	if($min!=null & $max!=null){
	if(!($var >= $min and $var <= $max)){
	  //echo "fuera de rango ($min,$max)";
	  return false;
	}
	}
	if($unsigned == 1)
	  if($var < 0)
	    return false;
	break;

      case "date":
	if(!split('[/-]', $var)){
	  //echo "fecha invalida";
	  return false;
	}
	break;

      case "email":
	if(!$this->validateEmail($var))
	  return false;
	break;
      }


    //echo "regreso true";
    return true;
  }

function validateEmail($email){
    $mail_correcto = 0;
    //compruebo unas cosas primeras
    if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){
       if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) {
          //miro si tiene caracter .
          if (substr_count($email,".")>= 1){
             //obtengo la terminacion del dominio
             $term_dom = substr(strrchr ($email, '.'),1);
             //compruebo que la terminación del dominio sea correcta
             if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){
                //compruebo que lo de antes del dominio sea correcto
                $antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1);
                $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1);
                if ($caracter_ult != "@" && $caracter_ult != "."){
                   $mail_correcto = 1;
                }
             }
          }
       }
    }
    if ($mail_correcto)
       return true;
    else
       return false;
}

 public function makeNull($var)
 {
   //var_dump($var);
   if($var == null)
     return "&nbsp;";
   else
     return $var;
 }

 public function deleteVars()
 {
   foreach($this->s_created as $nombre=>$nulo)
     {
       unset($_SESSION[$nombre]);
     }
 }

 public function readSession()
 {
   $return = array();
   foreach($this->s_created as $name=>$null)
     {
       if(isset($_SESSION[$name]))
	  $return[$name] = $_SESSION[$name];
	else
	  $return[$name] = null;
	   
     }
   return $return;
 }

 public function getError($var_name,$id)
 {
   //$this->o_db->connect();

   $language_id = $this->o_config->language_id;

   $sql = "select name from cs_has_variable_has_error 
          join validation_error on cs_has_variable_has_error.validation_error_id = validation_error.id and validation_error.id=$id and language_id=$language_id join cs_has_variable on cs_has_variable.id = cs_has_variable_has_error.cs_has_variable_id and cs_has_variable_id = (select id from cs_has_variable where control_structure_id=$this->cs_id and method_id=(select id from method where name='$this->method') and variable_id=(select id from variable where name='$var_name'))";

   // echo $sql."<br />";
   //return;
   //mail('zaira@localhost','Zaira',$sql );
   $this->o_db->query_rows($sql);
   $row = mysql_fetch_assoc($this->o_db->query_result);

   echo $row['name'];
   
 }

 public function openSprySpan($var_name,$input)
 {
   echo "<span id=\"$var_name\">\n
          <label>\n";
   //mail('zaira@localhost','Zaira',$input );
   echo $input;
   echo "</label>\n";

 }

 public function closeSprySpan()
 {
   echo "</span>\n";
 }

 public function getErrorMin($var_name,$id)
 {
   echo "<span class=\"textfieldMinValueMsg\">\n";
   $this->getError($var_name,$id);
   echo "</span>";
 }

 public function getErrorMax($var_name,$id)
 {
   echo "<span class=\"textfieldMaxValueMsg\">\n";
   $this->getError($var_name,$id);
   echo "</span>";   
 }

 public function getErrorRequired($var_name,$id)
 {
   echo "<span class=\"textfieldRequiredMsg\">\n";
  
   //echo $var_name, $id;
   //return;
   $this->getError($var_name,$id);
   echo "</span>"; 
   
 }

 public function getErrorRequiredArea($var_name,$id)
 {
   echo "<span class=\"textareaRequiredMsg\">\n";
   $this->getError($var_name,$id);
   echo "</span>";    
 }

 public function getErrorInvalid($var_name,$id)
 {
   echo "<span class=\"textfieldInvalidFormatMsg\">\n";
   $this->getError($var_name,$id);
   echo "</span>";    
 }


public function getErrorMinChar($var_name,$id)
 {
   echo "<span class=\"textfieldMinCharsMsg\">\n";
   $this->getError($var_name,$id);
   echo "</span>";
 }

public function getErrorMaxChar($var_name,$id)
 {
   echo "<span class=\"textfieldMaxCharsMsg\">\n";
   $this->getError($var_name,$id);
   echo "</span>";
 }



 public function openInstances()
 {
 
   echo "\n<script type=\"text/javascript\">";
   echo "\n<!--";
   echo "\n//\n"; 
 }


 public function createInstance($var_name,$is_required = "true",$min = null,$max = null)
 {
   $sql = " select v.name as var_name,min_value,max_value,is_unsigned,t.name as type_name, min_char, max_char 
            from cs_has_variable 
            join variable as v on v.id=variable_id  and v.name='$var_name'
            join type_variable as t on t.id = v.type_variable_id 
            and cs_has_variable.method_id=(select id from method where name='$this->method') and control_structure_id=$this->cs_id";


   //echo $sql;

   //mail("zaira@localhost", "MundosPosibles", $sql);
   //exit;
   //return;

   $this->o_db->query_rows($sql);
   $row = mysql_fetch_assoc($this->o_db->query_result);

   $type_name = $row['type_name'];
   $min_value = $row['min_value'];
   $max_value = $row['max_value'];
   $is_unsigned = $row['is_unsigned'];

   //Se añade mínimo y máxim número de caracteres

   $min_char = $row['min_char'];
   $max_char = $row['max_char'];
 


   //mail("zaira@localhost", "sip_core", "$sql"."$min_char"."$max_char");
   //convert framework type_name to spry type_name
   switch($type_name)
     {
     case "string":
       $type_name = "none";
       break;
     case "telephone":
       $type_name = "phone_number";
       break;
    
     case "decimal":
       $type_name = "real";
       break;
     }

   if($is_unsigned == 1)
     {
       if($min_value == null)
	 {
	   $min_value = 0;
	 }
     }

  $text = "var $var_name = new Spry.Widget.ValidationTextField(\"$var_name\", \"$type_name\", {validateOn:[\"blur\"], isRequired: $is_required";

   // $text = "var $var_name = new Spry.Widget.ValidationTextField(\"$var_name\", \"none\", {validateOn:[\"blur\"], isRequired: $is_required";
  //mail("zaira@localhost", "MundosPosibles", $text);



   //minChars:20, maxChars:180

     if($min_char != null)
       $text = $text.", minChars:$min_char";

   if($max_char != null)
     $text = $text.", maxChars:$max_char";

   if($min_value != null)
     $text = $text.", minValue:$min_value";

   if($max_value != null)
     $text = $text.", maxValue:$max_value";

   if($type_name == "phone_number")
     $text = $text.", format:\"phone_custom\", pattern:\"0000000000\"";
   //$text = $text.", format:\"phone_custom\", pattern:\"+52.000.0000000\"";

   if($type_name == "date")
     $text = $text.",format:\"yyyy-mm-dd\"";
 


   $text = $text."});\n";

   echo $text;
 }

 public function createInstanceArea($var_name,$is_required = "true")
 {
   $sql = " select v.name as var_name,min_value,max_value,is_unsigned,t.name as type_name 
            from cs_has_variable 
            join variable as v on v.id=variable_id  and v.name='$var_name'
            join type_variable as t on t.id = v.type_variable_id 
            and cs_has_variable.method_id=(select id from method where name='$this->method') and control_structure_id=$this->cs_id";


   //echo $sql;

   $this->o_db->query_rows($sql);
   $row = mysql_fetch_assoc($this->o_db->query_result);

   $type_name = $row['type_name'];
   //mail("zaira@localhost", "MundosPosibles", $type_name);
   $min_value = $row['min_value'];
   //mail("zaira@localhost", "MundosPosibles", $min_value);
   $max_value = $row['max_value'];
   $is_unsigned = $row['is_unsigned'];


   //convert framework type_name to spry type_name
   switch($type_name)
     {
     case "string":
       $type_name = "none";
       //mail("zaira@localhost", "MundosPosibles", $sql);
       break;
     case "telephone":
       $type_name = "phone_number";
       //mail("zaira@localhost", "MundosPosibles", $sql);
       break;
     }

   if($is_unsigned == 1)
     {
       if($min_value == null)
	 {
	   $min_value = 0;
	 }
     }

   $text = "var $var_name = new Spry.Widget.ValidationTextarea(\"$var_name\", {validateOn:[\"change, blur\"], isRequired: $is_required";

   if($min_value != null)
     $text = $text.",minValue:$min_value";

   if($max_value != null)
     $text = $text.",maxValue:$max_value";

   if($type_name == "phone_number")
     $text = $text.", format:\"phone_custom\", pattern:\"0000000000\"";
     // $text = $text.", format:\"phone_custom\", pattern:\"+52.000.0000000\"";


   if($type_name == "date")
     $text = $text.",format:\"dd/mm/yy\"";

   //mail("zaira@localhost", "MundosPosibles", $type_name);
   $text = $text."});\n";

   echo $text;
 }


 public function closeInstances()
 {

   echo "//\n//-->\n</script>\n";
 }

 public function parseInt($string) {
   if(preg_match('/(\d+)/', $string, $array)) {
     return $array[1];
   } else {
     return 0;
   }
 } 

}

?>