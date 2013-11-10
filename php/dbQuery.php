<?php
    ini_set('display_errors', 'On');
    require_once("db_connect.php");
    $ttid = $_REQUEST['name'];
    
    //get select fields from employee table AND from department table
    $sql = "SELECT * FROM FIMdata WHERE TTid = ". $ttid;
    $result = mysql_query($sql) or die('Problem, dude');
    
    //use while to loop through all rows:
    while($row = mysql_fetch_array($result))
    {
        $queryResult = json_encode($row); 
    }
    echo $queryResult;
?>