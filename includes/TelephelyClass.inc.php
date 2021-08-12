<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class telephely_db{
    
    // pillanatnyilag nam hasznĂˇlt funkciĂł
    public function select_telephely(){
        echo '<div class"container"> <h1>Telephely kiválasztása</h1>';
        
        echo '<div class="row">
                <div class="col-sm-3">';
            echo '<form method="post" action="'.$_SERVER["PHP_SELF"].'">';

                echo '<select class = "form-control" name = "telephely">';
                $connect = DbConnect();
                $sql = "SELECT * FROM telephelyek";
                $result = $connect->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                       
                        echo '<option value="' . $row["telephely_neve"] . '">' . $row["telephely_neve"] . '</option>';
                    
                    }
                } else {
                    echo "0 results";
                }

                echo '</select><br>';
            echo '<button type="submit"  class="btn btn-info">kiválaszt</button>';;
            echo '</form>';
        echo '</div>
                <div class="col-sm-3"></div>
                <div class="col-sm-3"></div>
                <div class="col-sm-3"></div>
          </div> ';
         
        echo'</div>';        
  
    }
    
    
    
    
    
    
  public function set_telephely(){
        
        if (isset($_POST['telephely'])){
            $telephely = $_SESSION["set_telephely"] = $_POST["telephely"];
                     
            setcookie("rendelo_user", $telephely, time() + 14400, "/");  
            
            $logtxt = 'telephely - '.$_SESSION["set_telephely"];
            
            if (isset($_SESSION['real_name'])){$user  = $_SESSION['real_name'];}
                else {$user  = "login user"; }
                
            $log = new SystemlogClass($user, $logtxt);
            $log->writelog(); 
            header("Refresh:0; url=index.php"); 
          }
    
  }  
     public function checkbox_telephely(){
      $html = '';
                $connect = DbConnect();
                $sql = "SELECT * FROM telephelyek";
                $result = $connect->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        
                        $html.= '<div class="radio">';
                        $html.= '<label><input type="radio" name="szamla_vevo" value="'.$row["telephely_neve"].'" checked> '.$row["CegNeve"].' ( '.$row["telephely_neve"].' )</label></div>';
                       
                      //  echo '<option value="' . $row[telephely_neve] . '">' . $row[telephely_neve] . '</option>';
                    
                    }
                } else {
                    echo "0 results";
                }

      return $html;    
    }
    
    public function admin_post_delete_telephely() {
        
        if (isset($_POST['delete_telephely_id'])) {
            $deleteid = test_input($_POST['delete_telephely_id']);
            echo $deleteid;
            $conn = DbConnect();

            $sql = "DELETE FROM telephelyek WHERE telephely_id='$deleteid'";

            if (mysqli_query($conn, $sql)) {
                // echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
    }

    public function admin_post_insert_telephely() {
        
        if (isset($_POST["telephely_neve"]) AND isset($_POST["CegNeve"])  AND isset($_POST["CegCim"]) AND isset($_POST["RendeloCim"]) AND isset($_POST["Adoszam"])) {

            $conn = DbConnect();
            // adatbĂˇzisba Ă­rĂˇs
            // tĂˇrolandĂł adatok bemeneti ellenĂ¶rzĂ©se
            $telephely_neve = test_input($_POST["telephely_neve"]);
            $CegNeve = test_input($_POST["CegNeve"]);
            $CegCim = test_input($_POST["CegCĂ­m"]);
            $RendeloCim = test_input($_POST["RendeloCim"]);
            $Adoszam = test_input($_POST["Adoszam"]);
                        
            $sql = "INSERT INTO telephelyek (telephely_neve, CegNeve, CegCim, RendeloCim, Adoszam) VALUES ('$telephely_neve','$CegNeve','$CegCim','$RendeloCim','$Adoszam')";

            if (mysqli_query($conn, $sql)) {
                 echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            mysqli_close($conn);
            }
    }
    
    public function select_LoginCegNeve(){
        
        $conn = DbConnect();
        $return = "";

        $sql = "SELECT * FROM telephelyek LIMIT 1";
        
        $result = $conn->query($sql);
            if ($result->num_rows > 0) {
    
            while ($row = $result->fetch_assoc()) {

                $return = $row["CegNeve"];
        
            }
        } else {
            echo "0 results";
        }
        return $return;
       
    }
    
    
    public function select_CegNeve($telephely){
        
        $conn = DbConnect();
        $return = "";

        $sql = "SELECT * FROM telephelyek WHERE telephely_neve = '$telephely'";
        
        $result = $conn->query($sql);
            if ($result->num_rows > 0) {
    
            while ($row = $result->fetch_assoc()) {

                $return = $row["CegNeve"];
        
            }
        } else {
            echo "0 results";
        }
        return $return;
       
    }
        
    public function select_CegCim ($telephely){
        $conn = DbConnect();
        $return = "";

        $sql = "SELECT * FROM telephelyek WHERE telephely_neve = '$telephely'";
        
        $result = $conn->query($sql);
            if ($result->num_rows > 0) {
    
            while ($row = $result->fetch_assoc()) {

                $return = $row["CegCim"];
        
            }
        } else {
            echo "0 results";
        }
        return $return;
    }
    
    public function select_RendeloCim ($telephely){
        $conn = DbConnect();
        $return = "";

        $sql = "SELECT * FROM telephelyek WHERE telephely_neve = '$telephely'";
        
        $result = $conn->query($sql);
            if ($result->num_rows > 0) {
    
            while ($row = $result->fetch_assoc()) {

                $return = $row["RendeloCim"];
        
            }
        } else {
            echo "0 results";
        }
        return $return;
    }
    
    public function select_Adoszam ($telephely){
        $conn = DbConnect();
        $return = "";

        $sql = "SELECT * FROM telephelyek WHERE telephely_neve = '$telephely'";
        
        $result = $conn->query($sql);
            if ($result->num_rows > 0) {
    
            while ($row = $result->fetch_assoc()) {

                $return = $row["Adoszam"];
        
            }
        } else {
            echo "0 results";
        }
        return $return;
    }
    
    public function select_mkido_kezdes_ora ($telephely){
        $conn = DbConnect();
        $return = "";

        $sql = "SELECT * FROM telephelyek WHERE telephely_neve = '$telephely'";
        
        $result = $conn->query($sql);
            if ($result->num_rows > 0) {
    
            while ($row = $result->fetch_assoc()) {

                $return = $row["mkido_kezdes_ora"];
        
            }
        } else {
            echo "0 results";
        }
        return $return;
    }
    
    public function select_mkido_kezdes_perc ($telephely){
        $conn = DbConnect();
        $return = "";

        $sql = "SELECT * FROM telephelyek WHERE telephely_neve = '$telephely'";
        
        $result = $conn->query($sql);
            if ($result->num_rows > 0) {
    
            while ($row = $result->fetch_assoc()) {

                $return = $row["mkido_kezdes_perc"];
        
            }
        } else {
            echo "0 results";
        }
        return $return;
    }
    
    
     
    public function select_bev_pbiz_name ($telephely){
        $conn = DbConnect();
        $return = "";

        $sql = "SELECT * FROM telephelyek WHERE telephely_neve = '$telephely'";
        
        $result = $conn->query($sql);
            if ($result->num_rows > 0) {
    
            while ($row = $result->fetch_assoc()) {

                $return = $row["bev_pbiz_dbname"];
        
            }
        } else {
            echo "0 results";
        }
        return $return;
    }
    
    public function select_ki_pbiz_name ($telephely){
        $conn = DbConnect();
        $return = "";

        $sql = "SELECT * FROM telephelyek WHERE telephely_neve = '$telephely'";
        
        $result = $conn->query($sql);
            if ($result->num_rows > 0) {
    
            while ($row = $result->fetch_assoc()) {

                $return = $row["ki_pbiz_dbname"];
        
            }
        } else {
            echo "0 results";
        }
        return $return;
    }
    
    public function admin_insert_telephely() {
        $conn = DbConnect();
        
        $date = date("y-m-d");

        echo'<h1>Rendelők felvétele törlése</h1>';
        echo '<table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width:100px;">Id</th>
                        <th>Rendelő neve (egyedi)</th>
                        <th>Cég neve</th>
                        <th>Cég címe</th>
                        <th>Rendelő címe</th>
                        <th>Adószáma</th>
                        <th>Hozzáad</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                    <form action="' . $_SERVER['PHP_SELF'] . '?pid=page84" method="post">
                    <td> <input type="tetx" class="form-control" name="telephely_id" disabled></td>
                    <td> <input type = "text" class = "form-control" name = "telephely_neve" placeholder="BMM"></td>
                    <td> <input type = "text" class = "form-control" name = "CegNeve" placeholder="XYZ. kft"></td>
                    <td> <input type = "text" class = "form-control" name = "CegCim" placeholder="Budapest KFT u 27"></td>
                    <td> <input type = "text" class = "form-control" name = "RendeloCim" placeholder="Budapest RendelĹ‘ utca u 88"></td>    
                    <td> <input type = "text" class = "form-control" name = "Adoszam" placeholder="12345678-1-12"></td> 
                    <td><button type = "submit" value = "Submit" type = "button" class = "btn btn-success">Save</button></td>
                    </form></tr>';
        // echo'</tbody></table>';    

        mysqli_close($conn);
    }

    public function admin_delete_telephely() {
        $conn = DbConnect();

        $sql = "SELECT * FROM telephelyek";
        $result = $conn->query($sql);
        echo '<form action="' . $_SERVER['PHP_SELF'] . '?pid=page84" method="post">';
        if ($result->num_rows > 0) {
 
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>"
                . $row["telephely_id"] . "</td><td> "
                . $row["telephely_neve"] . "</td><td>"
                . $row["CegNeve"] . "</td><td>"
                . $row["CegCim"] . "</td><td>"
                . $row["RendeloCim"] . "</td><td>"
                . $row["Adoszam"] . "</td><td>"
                . '<input type="radio" name="delete_telephely_id" value="' . $row["telephely_id"] . '"></td></tr>'
                ;
            }
        } else {
            echo "0 results";
        }
        echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td>'
        . '<button type = "submit" value = "Submit" type = "button" class = "btn btn-warning">Delete</button></td>';
        echo "</form></tbody></table>";

        mysqli_close($conn);
    }
    
}