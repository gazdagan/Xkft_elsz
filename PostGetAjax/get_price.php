<?php

/* 
 * orvosok szolgáltataások árainak lekérdezésa
 */

include ( "../includes/DbConnect.inc.php");
include ( "../includes/Inputvalidation.inc.php");


if (isset($_GET["doctor_name"]) AND isset($_GET["setvice_name"])  ){
    
       
    $conn = DbConnect();
    $docotr_name = test_input($_GET["doctor_name"]);
    $setvice_name = test_input($_GET["setvice_name"]);
    $price = 'Err : SQL';   
        
   
    $sql1 = "SELECT kezeles_ar FROM kezeles_arak_jutalek WHERE kezelo_neve LIKE '%$docotr_name%' AND kezeles_tipus LIKE '%$setvice_name%'";

    $result = $conn->query($sql1);
    
    if ($result->num_rows > 0) {    
        while ($row = $result->fetch_assoc()) {
            
            $price = $row["kezeles_ar"];
        }
    }else{
        
        $price = 'Err : '.$docotr_name.' or '.$setvice_name.'';
        
    }

    echo $price;   
}
?>