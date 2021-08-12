<?php

/* 
 * pénzügyi ellenörzőlisat insert db
 *  
 */

include ( "../includes/DbConnect.inc.php");

if (isset($_POST["pe_date"]) AND isset($_POST["pe_recepcio"]) AND  isset($_POST["pe_info"]) AND  isset($_POST["pe_telephely"])){
    
      
    $pucheck_telephely = $_POST["pe_telephely"];
    $pucheck_date = $_POST["pe_date"];
    $pucheck_recepcio = $_POST["pe_recepcio"];
    $pucheck_info = $_POST["pe_info"];
    
$conn = DbConnect();
           
    $sql = "INSERT INTO napi_pu_check (pucheck_telephely, pucheck_date, pucheck_recepcio, pucheck_info)
        VALUES ('$pucheck_telephely','$pucheck_date','$pucheck_recepcio','$pucheck_info')";

        if ($conn->query($sql) === TRUE) {
          echo "Mentés OK";
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
        }     
 
   
$conn->close();    

    
}
