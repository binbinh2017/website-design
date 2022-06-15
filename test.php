<?php

$host = 'mars.cs.qc.cuny.edu';
$root = 'hubi8176';
$pwd = '23748176';
$db = 'hubi8176';

$con = mysqli_connect($host,$root,$pwd,$db);
if($con == false){
	echo "fail";
}else{
	echo "success";
}

$query = "Select * from users";
$results = mysqli_query($con, $query);
		

    while($row=mysql_fetch_row($results)){
        //echo '<tr>';
        foreach($row as $data){
            echo $data.'';
        }
		echo '<br>';
        //echo "</tr>";

    }
        
mysqli_close($con);
?>