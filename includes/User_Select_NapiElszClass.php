<?php

/**
 * User_Select_NapiElsz
 * Napon belüli átadáshoz szükséges SELECT funkcionalitások
 */

class User_Select_NapiElsz {
   /**
    *
    *  public $napikpkiadas;    napi kiadás és bevételi oldlak 
    *  Public $napikpbevetel;
    */    

  
   
   public $MedicalPlusName = "Medical Plus Kft.";
   public $napikpkiadas;
   public $napikpbevetel; 
   public $rogzito; 
   public $telephely;
   public $date;

   function __construct() {
    
            if (isset($_SESSION['real_name'])){
                
                $this->rogzito = $_SESSION['real_name'];
                
            }else{
                $this->rogzito="Error rogzito name";
            }
    
            if (isset($_SESSION['set_telephely'])){
                
                $this->telephely = $_SESSION['set_telephely'];
                
            }else{
                $this->telephely="Error telephely";
            }
            $this->date = date("y-m-d");

        }
   
    //napi medport számlák
   public function user_napi_medportszamla() { //napi medport számlák
        $conn = DbConnect();
        $null ="";
        
        echo'<h1>Rendelő: ' . $this->telephely . ' / ' . $this->date . '</h1><h2>Medport számlák</h2>';
        echo '<table class="table table-striped">
                <thead>
                    <tr>
                        <th>Páciens Neve</th>
                        <th>Kezelő / Orvos</th>
                        <th>Szolgáltatás tipusa</th>
                        <th>Fizetés módja</th>
                        <th>EP lista</th>
                        <th>Fizetés Összege</th>
                        <th>Jutalék</th>
                        <th>Bérlet</th>
                        <th>Számlaszám</th>
                    </tr>
                </thead>
                <tbody>';
        
        
        // az aktuális napi bevételek lekérdezése
        $sql = "SELECT * FROM napi_elszamolas where telephely = '$this->telephely' AND "
                . "date = '$this->date' AND torolt_szamla = '0' AND rogzito ='$this->rogzito'  "
                . "AND lezart_szamla ='0' AND szamlaszam <> '' AND kezelo_orvos_id <> '$this->MedicalPlusName' ";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>"
                . $row["paciens_neve"] . "</td><td> "
                . $row["kezelo_orvos_id"] . "</td><td>"
                . $row["szolgaltatas_id"] . "</td><td>"
                . $row["bevetel_tipusa_id"] . "</td><td>"
                . $row["ep_tipus"] . "</td><td>"
                . $row["bevetel_osszeg"] . "</td><td>"
                . $row["jutalek_osszeg"] . "</td><td>"
                . $row["berlet_adatok"] . "</td><td>"
                . $row["szamlaszam"] . "</td><td>";
            }
        } else {
             echo "<tr><td>Nincs rögzített adat</td></tr>";
        }

        $sql = "SELECT sum(bevetel_osszeg) as sum_bevetel,sum(jutalek_osszeg) as sum_jutalek FROM napi_elszamolas where telephely = '$this->telephely' AND "
                . "date = '$this->date' AND torolt_szamla = '0' AND rogzito ='$this->rogzito'  AND lezart_szamla ='0' AND kezelo_orvos_id <> '$this->MedicalPlusName' ";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                echo '<tr class="info"><td>
               </td><td> 
                </td><td>
                </td><td>
                </td><td>
                Összesen:</td><td>'
                . $row["sum_bevetel"] . '</td><td>'
                . $row["sum_jutalek"] . '</td><td>
                </td><td>
                </td><td>';
            }
            
        } else {
             echo "<tr><td>Nincs rögzített adat</td></tr>";
        }

        echo "</tbody></table>";

        mysqli_close($conn);
    }
   /**
    * user_select_all_bevetelektipusok
    * felhasználó lekérdezi összesítve a kp , bankkártya ..stb. módon a napi forgalmakat
    * az napon belüli átadás táblákhoz kellő adatok
    * használatban
    */
   public function user_select_all_bevetelektipusok($user) {
        $recepciosKpBevetel = 0;
        $recepcioKpKivet = 0;
        $conn = DbConnect();
        $this->rogzito = $user;
        $html = "";
        $null ="";
        // táblázat visszaolvasása
        $html .= '<div class="container">';
        $html .= '<h2> Rendelő napi forgalom átadás átvételi jegyzőkönyv</h2><p>Rendelő: ' . $this->telephely . ' / ' . date("Y/m/d H:i:s") . '</p>';
        $html .= '<table class="table table-striped">
                <thead>
                    <tr>
                        <th>Bevétel típusok</th>
                        <th>Összeg(HUF)</th>
                        <th>Darabszám</th>
                    </tr>
                </thead>';
        // az aktuális napi bevételek lekérdezése

        $html .= '<tr>';
                // készpénz adatok összesítése    
                $html .= '<td>Készpénz (számla + nyugta)</td>';
                // ósszesített összegek
                $sql1 = "SELECT sum(bevetel_osszeg) as sum_kp_bevetel FROM napi_elszamolas WHERE telephely = '$this->telephely' AND "
                        . "date = '$this->date' AND torolt_szamla = '0' AND rogzito LIKE '$this->rogzito' AND lezart_szamla ='0' "
                        . "AND ( bevetel_tipusa_id = 'kp-nyugta' OR bevetel_tipusa_id = 'kp-számla' )";

                $result = $conn->query($sql1);
                    if ($result->num_rows > 0) {

                        while ($row1 = $result->fetch_assoc()) {
                            $html .= '<td>'.$row1["sum_kp_bevetel"].'</td>';
                            $recepciosKpBevetel = $row1["sum_kp_bevetel"];
                        }
                        
                    } else {
                         $html .= "<td>Nincs rögzített adat</td>";
                    }
                // összesített darabszám
               $sql1 = "SELECT count(*) as db_kp_bevetel FROM napi_elszamolas WHERE telephely = '$this->telephely' AND "
                        . "date = '$this->date' AND torolt_szamla = '0' AND rogzito LIKE '$this->rogzito'  AND lezart_szamla ='0' "
                        . "AND ( bevetel_tipusa_id = 'kp-nyugta' OR bevetel_tipusa_id = 'kp-számla' )";

                $result = $conn->query($sql1);
                    if ($result->num_rows > 0) {

                        while ($row1 = $result->fetch_assoc()) {
                            $html .= '<td>'.$row1["db_kp_bevetel"].'</td>';
                        }
                        
                    } else {
                         $html .= "<td>Nincs rögzített adat</td>";
                    }
                
         $html .= '</tr>';
         $html .= '<tr>';               
            // Bankkártya forgalomi  adatok összesítése    
                $html .= '<td>Bankkártya (számla + nyugta)</td>';
                // ósszesített összegek
                $sql1 = "SELECT sum(bevetel_osszeg) as sum_kp_bevetel FROM napi_elszamolas WHERE telephely = '$this->telephely' AND "
                        . "date = '$this->date' AND torolt_szamla = '0' AND rogzito LIKE '$this->rogzito'  AND lezart_szamla ='0' "
                        . "AND ( bevetel_tipusa_id = 'bankkártya-nyugta' OR bevetel_tipusa_id = 'bankkártya-számla' )";

                $result = $conn->query($sql1);
                    if ($result->num_rows > 0) {

                        while ($row1 = $result->fetch_assoc()) {
                            $html .= '<td>'.$row1["sum_kp_bevetel"].'</td>';
                        }
                        
                    } else {
                         $html .= "<td>Nincs rögzített adat</td>";
                    }
                // összesített darabszám
               $sql1 = "SELECT count(*) as db_kp_bevetel FROM napi_elszamolas WHERE telephely = '$this->telephely' AND "
                        . "date = '$this->date' AND torolt_szamla = '0' AND rogzito LIKE '$this->rogzito'  AND lezart_szamla ='0' "
                        . "AND ( bevetel_tipusa_id = 'bankkártya-nyugta' OR bevetel_tipusa_id = 'bankkártya-számla' )";

                $result = $conn->query($sql1);
                    if ($result->num_rows > 0) {

                        while ($row1 = $result->fetch_assoc()) {
                            $html .= '<td>'.$row1["db_kp_bevetel"].'</td>';
                        }
                        
                    } else {
                         $html .= "<td>Nincs rögzített adat</td>";
                    }    
            $html .= '</tr>';
            $html .= '<tr>';               
            //Egészségpénztári forgalomi  adatok összesítése    
                $html .= '<td>Egészségpénztár (számla + kártya)</td>';
                // ósszesített összegek
                $sql1 = "SELECT sum(bevetel_osszeg) as sum_kp_bevetel FROM napi_elszamolas WHERE telephely = '$this->telephely' AND "
                        . "date = '$this->date' AND torolt_szamla = '0' AND rogzito LIKE '$this->rogzito'  AND lezart_szamla ='0' "
                        . "AND  bevetel_tipusa_id LIKE '%egészségpénztár%'";

                $result = $conn->query($sql1);
                    if ($result->num_rows > 0) {

                        while ($row1 = $result->fetch_assoc()) {
                            $html .= '<td>'.$row1["sum_kp_bevetel"].'</td>';
                        }
                        
                    } else {
                         $html .= "<td>Nincs rögzített adat</td>";
                    }
                // összesített darabszám
               $sql1 = "SELECT count(*) as db_kp_bevetel FROM napi_elszamolas WHERE telephely = '$this->telephely' AND "
                        . "date = '$this->date' AND torolt_szamla = '0' AND rogzito LIKE '$this->rogzito'  AND lezart_szamla ='0' "
                        . "AND  bevetel_tipusa_id LIKE '%egészségpénztár%'";

                $result = $conn->query($sql1);
                    if ($result->num_rows > 0) {

                        while ($row1 = $result->fetch_assoc()) {
                            $html .= '<td>'.$row1["db_kp_bevetel"].'</td>';
                        }
                        
                    } else {
                         $html .= "<td>Nincs rögzített adat</td>";
                    }    
            $html .= '</tr>';
            $html .= '<tr>';               
            //EuropeAssistance forgalomi  adatok összesítése    
                $html .= '<td>Europe Assistance</td>';
                // ósszesített összegek
                $sql1 = "SELECT sum(bevetel_osszeg) as sum_kp_bevetel FROM napi_elszamolas WHERE telephely = '$this->telephely' AND "
                        . "date = '$this->date' AND torolt_szamla = '0' AND rogzito LIKE '$this->rogzito'  AND lezart_szamla ='0' "
                        . "AND  bevetel_tipusa_id = 'europe assistance'";

                $result = $conn->query($sql1);
                    if ($result->num_rows > 0) {

                        while ($row1 = $result->fetch_assoc()) {
                            $html .= '<td>'.$row1["sum_kp_bevetel"].'</td>';
                        }
                        
                    } else {
                         $html .= "<td>Nincs rögzített adat</td>";
                    }
                // összesített darabszám
               $sql1 = "SELECT count(*) as db_kp_bevetel FROM napi_elszamolas WHERE telephely = '$this->telephely' AND "
                        . "date = '$this->date' AND torolt_szamla = '0' AND rogzito LIKE '$this->rogzito'  AND lezart_szamla ='0' "
                        . "AND  bevetel_tipusa_id = 'europe assistance'";

                $result = $conn->query($sql1);
                    if ($result->num_rows > 0) {

                        while ($row1 = $result->fetch_assoc()) {
                            $html .= '<td>'.$row1["db_kp_bevetel"].'</td>';
                        }
                        
                    } else {
                         $html .= "<td>Nincs rögzített adat</td>";
                    }    
            $html .= '</tr>';
            $html .= '<tr>';               
            //Adv Medical forgalomi  adatok összesítése    
                $html .= '<td>TELADOC</td>';
                // ósszesített összegek
                $sql1 = "SELECT sum(bevetel_osszeg) as sum_kp_bevetel FROM napi_elszamolas WHERE telephely = '$this->telephely' AND "
                        . "date = '$this->date' AND torolt_szamla = '0' AND rogzito LIKE '$this->rogzito'  AND lezart_szamla ='0' "
                        . "AND  bevetel_tipusa_id = 'TELADOC'";

                $result = $conn->query($sql1);
                    if ($result->num_rows > 0) {

                        while ($row1 = $result->fetch_assoc()) {
                            $html .= '<td>'.$row1["sum_kp_bevetel"].'</td>';
                        }
                        
                    } else {
                         $html .= "<td>Nincs rögzített adat</td>";
                    }
                // összesített darabszám
               $sql1 = "SELECT count(*) as db_kp_bevetel FROM napi_elszamolas WHERE telephely = '$this->telephely' AND "
                        . "date = '$this->date' AND torolt_szamla = '0' AND rogzito LIKE '$this->rogzito'  AND lezart_szamla ='0' "
                        . "AND  bevetel_tipusa_id = 'advance medical'";

                $result = $conn->query($sql1);
                    if ($result->num_rows > 0) {

                        while ($row1 = $result->fetch_assoc()) {
                            $html .= '<td>'.$row1["db_kp_bevetel"].'</td>';
                        }
                        
                    } else {
                         $html .= "<td>Nincs rögzített adat</td>";
                    }    
            $html .= '</tr>';
            
             $html .= '<tr>';               
            //átutalás forgami adatok összesítése    
                $html .= '<td>Átutalás</td>';
                // ósszesített összegek
                $sql1 = "SELECT sum(bevetel_osszeg) as sum_kp_bevetel FROM napi_elszamolas WHERE telephely = '$this->telephely' AND "
                        . "date = '$this->date' AND torolt_szamla = '0' AND rogzito LIKE '$this->rogzito'  AND lezart_szamla ='0' "
                        . "AND  bevetel_tipusa_id = 'átutalás'";

                $result = $conn->query($sql1);
                    if ($result->num_rows > 0) {

                        while ($row1 = $result->fetch_assoc()) {
                            $html .= '<td>'.$row1["sum_kp_bevetel"].'</td>';
                        }
                        
                    } else {
                         $html .= "<td>Nincs rögzített adat</td>";
                    }
                // összesített darabszám
               $sql1 = "SELECT count(*) as db_kp_bevetel FROM napi_elszamolas WHERE telephely = '$this->telephely' AND "
                        . "date = '$this->date' AND torolt_szamla = '0' AND rogzito LIKE '$this->rogzito'  AND lezart_szamla ='0' "
                        . "AND  bevetel_tipusa_id = 'átutalás'";

                $result = $conn->query($sql1);
                    if ($result->num_rows > 0) {

                        while ($row1 = $result->fetch_assoc()) {
                            $html .= '<td>'.$row1["db_kp_bevetel"].'</td>';
                        }
                        
                    } else {
                         $html .= "<td>Nincs rögzített adat</td>";
                    }    
            $html .= '</tr>';
            
             $html .= '<tr>';               
            //ajándék forgami adatok összesítése    
                $html .= '<td>Ajándék</td>';
                
                // ósszesített összegek
                $sql1 = "SELECT sum(bevetel_osszeg) as sum_kp_bevetel FROM napi_elszamolas WHERE telephely = '$this->telephely' AND "
                        . "date = '$this->date' AND torolt_szamla = '0' AND rogzito LIKE '$this->rogzito'  AND lezart_szamla ='0' "
                        . "AND  bevetel_tipusa_id = 'ajándék'";

                $result = $conn->query($sql1);
                    if ($result->num_rows > 0) {

                        while ($row1 = $result->fetch_assoc()) {
                            $html .= '<td>'.$row1["sum_kp_bevetel"].'</td>';
                        }
                        
                    } else {
                         $html .= "<td>Nincs rögzített adat</td>";
                    }
                // összesített darabszám
               $sql1 = "SELECT count(*) as db_kp_bevetel FROM napi_elszamolas WHERE telephely = '$this->telephely' AND "
                        . "date = '$this->date' AND torolt_szamla = '0' AND rogzito LIKE '$this->rogzito'  AND lezart_szamla ='0' "
                        . "AND  bevetel_tipusa_id = 'ajándék'";

                $result = $conn->query($sql1);
                    if ($result->num_rows > 0) {

                        while ($row1 = $result->fetch_assoc()) {
                            $html .= '<td>'.$row1["db_kp_bevetel"].'</td>';
                        }
                        
                    } else {
                         $html .= "<td>Nincs rögzített adat</td>";
                    }    
            $html .= '</tr>';
            
            
        
        // készpénz kivét összesítve  
        // mai napon kivett a recepciós álltali összes kivet
            $sql2 = "SELECT sum(kivet_osszeg) as sum_kivet FROM kp_kivet where kivet_telephely = '$this->telephely' AND kivet_torolve = '0' AND kivet_datum = '$this->date' AND kivevo_neve LIKE '$this->rogzito'";
            
            $result2 = $conn->query($sql2);
            
            if ($result2->num_rows == 1) {
                   while ($row1 = $result2->fetch_assoc()) {
                    $recepcioKpKivet= $row1["sum_kivet"];
                }
            } else {
                 $recepcioKpKivet = 0;
            }
            $kpEgyenleg = $recepciosKpBevetel - $recepcioKpKivet;
            
            $null .= '<tr class=""><td>Készpénz forgalom (kp bevétel - kp kiadás)</td><td>'.$recepciosKpBevetel.' - '.$recepcioKpKivet.' = '.$kpEgyenleg.' </td><td></tr>';
        
            
            
        $html .='<tr>';
                // készpénz ajándék összesítése    
                $html .= '<td>Készpénz partner</td>';
                // ósszesített összegek
                $sql1 = "SELECT sum(bevetel_osszeg) as sum_kp_bevetel FROM napi_elszamolas WHERE telephely = '$this->telephely' AND "
                        . "date = '$this->date' AND torolt_szamla = '0' AND rogzito LIKE '$this->rogzito' AND lezart_szamla ='0' "
                        . "AND  bevetel_tipusa_id = 'kp-partner'";

                $result = $conn->query($sql1);
                    if ($result->num_rows > 0) {

                        while ($row1 = $result->fetch_assoc()) {
                            $html .= '<td>'.$row1["sum_kp_bevetel"].'</td>';
                            $recepciosKpBevetel = $row1["sum_kp_bevetel"];
                        }
                        
                    } else {
                         $html .= "<td>Nincs rögzített adat</td>";
                    }
                // összesített darabszám
               $sql1 = "SELECT count(*) as db_kp_bevetel FROM napi_elszamolas WHERE telephely = '$this->telephely' AND "
                        . "date = '$this->date' AND torolt_szamla = '0' AND rogzito LIKE '$this->rogzito'  AND lezart_szamla ='0' "
                        . "AND bevetel_tipusa_id = 'kp-partner'";

                $result = $conn->query($sql1);
                    if ($result->num_rows > 0) {

                        while ($row1 = $result->fetch_assoc()) {
                            $html .= '<td>'.$row1["db_kp_bevetel"].'</td>';
                        }
                        
                    } else {
                         $html .= "<td>Nincs rögzített adat</td>";
                    }
                
         $html .= '</tr>';    
            
            
            
        $html .= "</tbody></table></div>";
       

        mysqli_close($conn);
        return $html;
    }
    
    /**
     * public function user_select_kpkivet()
     * 
     * készpénz forgalom elszámolása napon belöli átadáskor
     * 
     * használatban
     */
        public function user_select_kpkivet_table(){
        
        $conn = DbConnect();
        $recepcioKivet =0;       
        
        $sql = "SELECT * FROM kp_kivet WHERE kivet_datum = '$this->date' AND kivet_telephely ='$this->telephely' AND kivet_torolve ='0' AND kivevo_neve ='$this->rogzito'";
        $result = $conn->query($sql);

            
            echo'<table class="table table-striped">
                <thead>
                    <tr><th>Készpénz kivétek</th></tr>
                    <tr>
                      <th>No.:</th>
                      <th>Kiadó</th>
                      <th>Kivét Oka</th>
                      <th>Kivét Összege(Ft)</th>
                      <th>Kivét Átvevő</th>
                      <th>Megjegyzés</th>
                    </tr>
                  </thead><tbody>';
            
        if ($result->num_rows > 0) {     
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>"
                . $row["kivet_id"] . "</td><td> "
                . $row["kivevo_neve"] . "</td><td>"
                . $row["kivet_oka"] . "</td><td>"
                . $row["kivet_osszeg"] . "</td><td>"   
                . $row["kivet_atvevo"] . "</td><td>"
                . $row["kivet_megjegyzes"] . "</td></tr>"
               ;
            }
        } else {
            echo "<tr><td>Nincs rögzített adat</td></tr>";
        } 
        // mai napon kivett a recepciós álltali összes kivet
            $sql2 = "SELECT sum(kivet_osszeg) as sum_kivet FROM kp_kivet where kivet_telephely = '$this->telephely' AND kivet_torolve = '0' AND kivet_datum = '$this->date' AND kivevo_neve ='$this->rogzito'";
            
            $result2 = $conn->query($sql2);
            
            if ($result2->num_rows == 1) {
                   while ($row1 = $result2->fetch_assoc()) {
                    $recepcioKivet= $row1["sum_kivet"];
                }
            } else {
                 $recepcioKivet = 0;
            }
            echo '<tr class="info"><td></td><td></td><td>Összesen: </td><td>'.$recepcioKivet.'</td><td></td><td></td></tr>';
        
        
        echo "</tbody></table>";
        
              
             mysqli_close($conn);
    }   
    
   
    
   /**
    * user_select_medport_jutalekok
    *
    * felhasználó kiválogatj a napi adatok közül a jutalékos szolgáltatásokat és összesítve megjeleníti
    * főablakban kitéve a páciensliat alá
    */
   public function user_select_medport_jutalekok() {
        $conn = DbConnect();
       
    // táblázat visszaolvasása
        echo '<div class="container">';
        echo'<h1> Napi jutalékok:</h1>';
        echo'<p>Rendelő: ' . $this->telephely . ' / ' . $this->date . '</p>';
        echo '<table class="table table-striped">
                <thead>
                    <tr>
                        <th>Orvosok - Kezelők</th>
                        <th>Összeg(HUF)</th>
                    </tr>
                </thead>
                <tbody>';
          
        // az aktuális napi bevételek lekérdezése

        echo'<tr>';
        $sql = "SELECT DISTINCT kezelo_orvos_id FROM `napi_elszamolas` WHERE telephely = '$this->telephely' AND "
                . "date = '$this->date' AND torolt_szamla = '0' AND rogzito ='$this->rogzito'  AND lezart_szamla ='0'"
                . "AND kezelo_orvos_id <> '$this->MedicalPlusName'";

        $result1 = $conn->query($sql);
        if ($result1->num_rows > 0) {
            while ($row = $result1->fetch_assoc()) {
                echo '<tr> <td>' . $row['kezelo_orvos_id'] . '</td>';
                $kezelo = $row['kezelo_orvos_id'];

                $sql1 = "SELECT sum(jutalek_osszeg) as sum_jutalek FROM napi_elszamolas WHERE telephely = '$this->telephely' AND "
                        . "date = '$this->date' AND torolt_szamla = '0' AND rogzito ='$this->rogzito'  AND lezart_szamla ='0'"
                        . "AND kezelo_orvos_id = '$kezelo' ";

                $result = $conn->query($sql1);
                if ($result->num_rows > 0) {


                    while ($row1 = $result->fetch_assoc()) {
                        echo '<td>' . $row1["sum_jutalek"] . '</td>';
                    }
                } else {
                     echo "<tr><td>Nincs rögzített adat</td></tr>";
                }
                echo'</tr>';
            }
        } else {
            echo "<tr><td>Nincs rögzített adat</td></tr>";
        }
        echo "</tbody></table></div>";

        mysqli_close($conn);
    }
 
   public function user_select_medicalplus_beveteltipusok() {
        $conn = DbConnect();
        // táblázat visszaolvasása
        echo'<h1>Rendelő: ' . $this->telephely . ' / ' . $this->date . '</h1><h2>'.$this->MedicalPlusName. ' Segédeszköz</h2>';
        echo '<table class="table table-striped">
                <thead>
                    <tr>
                        <th>Bevétel tipusok</th>
                        <th>Összeg(HUF)</th>
                    </tr>
                </thead>
                <tbody>';
        
  
        // az aktuális napi bevételek lekérdezése
        echo'<tr>';
        $sql = "SELECT * FROM `bevetel_tipusok` ORDER BY bevetel_id ";
        $result1 = $conn->query($sql);

        if ($result1->num_rows > 0) {
            while ($row = $result1->fetch_assoc()) {
                echo '<tr> <td>' . $row['bevetel_neve'] . '</td><td>';

                $bevetel_tipus = $row['bevetel_neve'];

                $sql1 = "SELECT sum(bevetel_osszeg) as sum_bevetel FROM napi_elszamolas where telephely = '$this->telephely' AND "
                        . "date = '$this->date' AND torolt_szamla = '0' AND rogzito ='$this->rogzito'  AND lezart_szamla ='0' AND  	bevetel_tipusa_id = '$bevetel_tipus'"
                        . "AND kezelo_orvos_id = '$this->MedicalPlusName' ";

                $result = $conn->query($sql1);
                if ($result->num_rows > 0) {


                    while ($row1 = $result->fetch_assoc()) {
                        echo $row1["sum_bevetel"];
                    }
                } else {
                     echo "<tr><td>Nincs rögzített adat</td></tr>";
                }
                echo'</td>';
            }
        } else {
             echo "<tr><td>Nincs rögzített adat</td></tr>";
        }
        echo "</tbody></table>";

        mysqli_close($conn);
    }
    
    // tábla tartalm megjelenítés telephely dátum szerint szűrve saját számlákat fel
    // felhasználva telephelyi adatbevitel uj paciensnél
   public function user_select_napiadat_table($user) {
        $html ="";      
        $conn = DbConnect();
        $this->rogzito = $user;   
        // táblázat visszaolvasása
        $html.= '<div class="">';
        $html.= '<h2>'.$this->telephely. ' rendelő összes páciense:</h2>';
        $html.= '<p>Rendelő: ' . $this->telephely . ' / ' . date("Y/m/d H:i:s") . '</p>';

        $html.= '<table class="table table-striped" id="OrderedTable">
                <thead>
                    <tr>
                        <th> Páciens Neve</th>
                        <th> Kezelő / Orvos</th>
                        <th> Szolgáltatás típusa</th>
                        <th>Fizetés módja</th>
                        <th>EP</th>
                        <th>FT</th>
                        <th>Számlaszám</th>
                        <th>Bankkártya Slip</th>
                        <th>Ellenőrizve</th>
                    </tr>
                    
                </thead>
                <tbody>';
      
      
        // az aktuális napi bevételek lekérdezése
        $sql = "SELECT * FROM napi_elszamolas where telephely = '$this->telephely' AND "
                . "date = '$this->date' AND torolt_szamla = '0' AND rogzito LIKE '$this->rogzito'  AND lezart_szamla ='0' AND bevetel_osszeg != 0 ORDER BY szamlaszam DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
    // output data of each row

            while ($row = $result->fetch_assoc()) {
                $html.= "<tr><td>"
                . $row["paciens_neve"] . "</td><td> "
                . $row["kezelo_orvos_id"] . "</td><td>"
                . $row["szolgaltatas_id"] . "</td><td>"
                . $row["bevetel_tipusa_id"] . "</td><td>"
                . $row["ep_tipus"] . "</td><td>"
                . $row["bevetel_osszeg"] . "</td><td>"
                . $row["szamlaszam"] . "</td><td>"
                . $row["slipsz"] . "</td><td>"
                .'<div class="checkbox">
                 <label><input type="checkbox" value="">Átvettem</label>
                 </div></td></tr>';   
               
            }
        } else {
            $html.= "0 results";
        }
        
        $sql = "SELECT sum(bevetel_osszeg) as sum_bevetel,sum(jutalek_osszeg) as sum_jutalek FROM napi_elszamolas where telephely = '$this->telephely' AND "
                . "date = '$this->date' AND torolt_szamla = '0' AND rogzito LIKE '$this->rogzito'  AND lezart_szamla ='0' AND kezelo_orvos_id <> '$this->MedicalPlusName'  ";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                $html.= '<tr class="info"><td>
               </td><td> 
                </td><td>
                </td><td>
                </td><td>
                Összesen:</td><td>'
                . $row["sum_bevetel"] . '</td>
                </td><td>
                </td><td>
                </td><td>
                </tr>';
            }
            
        } else {
             $html.= "<tr><td>Nincs rögzített adat</td></tr>";
        }
        $html.= "</tbody></table></div>";

        mysqli_close($conn);
        return $html;
    }
    /**
     * user_select_napikp
     * 
     * a rendelkezésre álló napi kp állomány lekérdezése a kp kivét funkcióba 
     * 
     * 
     */
   public function user_select_napikp(){
   
            $conn = DbConnect();
                     
            /**
             * Mai napi kp bevétel összesen
             */
           $sql1 = "SELECT sum(bevetel_osszeg) as sum_kp_bevetel FROM napi_elszamolas WHERE telephely = '$this->telephely' AND "
                        . "date = '$this->date' AND torolt_szamla = '0' "
                        . "AND  bevetel_tipusa_id LIKE '%kp%'";
           
            $result = $conn->query($sql1);
            if ($result->num_rows == 1) {
                   while ($row1 = $result->fetch_assoc()) {
                    $this->napikpbevetel= $row1["sum_kp_bevetel"];
}
            } else {
                $this->napikpbevetel = "0";
            }
            
            // mai napon kivett összes kivet
            $sql2 = "SELECT sum(kivet_osszeg) as sum_kivet FROM kp_kivet where kivet_telephely = '$this->telephely' AND kivet_torolve = '0' AND kivet_datum = '$this->date' ";
            
            $result2 = $conn->query($sql2);
            
            if ($result2->num_rows == 1) {
                   while ($row1 = $result2->fetch_assoc()) {
                    $this->napikpkiadas= $row1["sum_kivet"];
                }
            } else {
                $this->napikpkiadas = "nulla";
            }
                        
            mysqli_close($conn);
            
            $result =  $this->napikpbevetel - $this->napikpkiadas;
            return $result;
                    
    }
}
