<?php

require_once("db_connect.php");
//get select fields from employee table AND from department table (informal join)
//$sql = "SELECT empId, lastName, firstName, departmentName, position, salary FROM employee, departments WHERE department = deptID";
$sql = "SELECT *  FROM Curves";
$result = mysql_query($sql) or die(mysql_error);

mysql_close();
echo "<b><center>Database Output</center></b><br><br>";

?>

<table border="1" cellspacing="0" cellpadding="1">
<tr> 
<th><font face="Arial, Helvetica, sans-serif">curveID</font></th>
<th><font face="Arial, Helvetica, sans-serif">start</font></th>
<th><font face="Arial, Helvetica, sans-serif">end</font></th>
<th><font face="Arial, Helvetica, sans-serif">section</font></th>
<th><font face="Arial, Helvetica, sans-serif">length</font></th>
</tr>

<?php
$i = 0;
//use while to loop through all rows:
while($row = mysql_fetch_array($result))
{
    
    //2 different ways of querying:
    $curveID = $row['curveID'];   //better in while loop
    $start = $row['startVertID'];
    $end = $row['endVertID'];
    $section = $row['Section'];
    $length = $row['Length'];
    //$position = mysql_result($result,$i,"position");  //better in for loop
    ?>
    
    <tr> 
    <td><font face="Arial, Helvetica, sans-serif"><?php echo "$curveID"; ?></font></td>
    <td><font face="Arial, Helvetica, sans-serif"><?php echo "$start"; ?></font></td>
    <td><font face="Arial, Helvetica, sans-serif"><?php echo "$section"; ?></font></td  
    </tr>
    <?php
    
    ++$i;
    
} 
echo "</table>";
?>
