<?php
include "utility.php";
include "functions.php";

include "../clases.php";
include "../config/dbconfig.php";

session_name($session_name);
session_start();


// the main object arriving
////////////////////////////////////////////////
if(empty($_SESSION['security']) || empty($_SESSION['config']) || empty($_SESSION['user']) || empty($_SESSION['databaseCredentials'])){
  echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=../logout.php\" ></head></html>";
  return;
}else{
 $o_config   = unserialize($_SESSION['config']);
 $o_user     = unserialize($_SESSION['user']);
 $o_security = unserialize($_SESSION['security']);
 $o_databaseCredentials  = unserialize($_SESSION['databaseCredentials']);
    
}


// VERY IMPORTANT //////////////////////////////
// connect the database since is not possible
// serialize/unserialize resources
$o_database  = new database($o_databaseCredentials->db_host,  $o_databaseCredentials->db_name,
    $o_databaseCredentials->db_user,  $o_databaseCredentials->db_password);

///////////////////////////////////////////////



// Check that this is a valid session
//////////////////////////////////////////////
if(!$o_security->checkSession($o_user->id, $o_user->security_id,session_id(),$o_database)){
  echo "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=index.html\"></head></html>";
  return;
}
header('Content-Type: application/json');


if(empty($_POST['empleado']))
  {
    $data = array("result" => "Error: variable no recibida.");
    echo json_encode($data);
    return;
  }

$empleado = trim($_POST['empleado']);


$q = "select e.*, p.* from employee as e join payroll as p on e.id = p.employee_id  where e.name like \"%$empleado%\" order by p.initial_date desc limit 4";
/*
+----+----------------------------+-----------+---------------+----+-------------+-----------+-------+-------------+--------------+------------+---------+----------------+--------+----------------+--------+----------------+
| id | name                       | rate_hour | cuenta_nomina | id | employee_id | rate_hour | hours | total_hours | initial_date | final_date | extra1  | comment_extra1 | extra2 | comment_extra2 | extra3 | comment_extra3 |
+----+----------------------------+-----------+---------------+----+-------------+-----------+-------+-------------+--------------+------------+---------+----------------+--------+----------------+--------+----------------+
|  2 | Rafael Sánchez Fernández |     25.00 | NULL          |  2 |           2 |     25.00 | 40.00 |     1000.00 | 2014-02-16   | 2014-02-22 |    0.00 | NULL           |   0.00 | NULL           |   0.00 | NULL           |
|  3 | Jimena Fernández R.       |     18.00 | NULL          |  3 |           3 |     18.00 | 40.00 |      720.00 | 2014-02-16   | 2014-02-22 | -100.00 | Pago préstamo |   0.00 | NULL           |   0.00 | NULL           |
+----+----------------------------+-----------+---------------+----+-------------+-----------+-------+-------------+--------------+------------+---------+----------------+--------+----------------+--------+----------------+
2 rows in set (0.00 sec)
*/



$o_database->query_rows($q);
$result = $o_database->query_result;
$data = array();
foreach($result as $row)
  {
    $label = "[".$row[9]."]  => [".$row[10]."] ".$row[1];
    $data[] = array("value" => $row[1], "label" => $label, "name" => $row[1], "e_id" => $row[0], "rate_hour" =>  $row[6], "p_id" => $row[4], "hours" => $row[7], "total_hours" => $row[8], "initial" => $row[9], "final" => $row[10], "extra_1" => $row[11], "comment_extra_1" => $row[12], "extra_2" => $row[13], "comment_extra_2" => $row[14], "extra_3" => $row[15], "comment_extra_3" => $row[16]);
  }

echo json_encode($data);
return;

?>