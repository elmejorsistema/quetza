<?php
include "config/dbconfig.php";
session_name($session_name);
session_start();

$_SESSION = array();
session_destroy();


// Go to the index!
echo "<html><head><script>top.location.href='index.html';</script></head></html>";



?>
