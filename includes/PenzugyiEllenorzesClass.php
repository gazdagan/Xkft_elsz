<?php

/*
 *pénzügyi ellenörzés napi elszámoláshoz
 */

/**
 * Description of PenzugyiEllenorzesClass
 *
 * @author gazda
 */
class PenzugyiEllenorzesClass {
    //put your code here
    
    public  $date; 
    private $dbconn;
    public  $telephely;
    private $recepcios;
    
    
    
    public function __construct() {
        
        $this->date = date("Y-m-d");  
        $this->dbconn = DbConnect();
        
        if (isset($_SESSION['set_telephely'])) {

            $this->telephely = $_SESSION['set_telephely'];
        } else {
            $this->telephely = "Error telephely";
        }
         
        if (isset($_SESSION['real_name'])) {

            $this->recepcios = $_SESSION['real_name'];
        } else {
            $this->recepcios = "Error recepcio";
        }
    }
    public function __destruct() {
        $this->dbconn->close();
    }

 public function penzugyi_check_page(){
     
        
        $html = "";
    $html .='<div class="container">
             <h2>Artéria pénzügyi ellenözés rögzítés.</h2>
                <div class="panel-group">
                  <div class="panel panel-default">
                    <div class="panel-heading">Vágólapról illeszd be a kép tartalmát a szerkesztö felültre!</div>
                    <div class="panel-body">';
                    $html .= $this->penzugyi_check_form();
          $html .= '</div> <div class="panel-footer"></div>'
               . '</div>'
            . '</div>';
     
        return $html;
 }
 
 private function penzugyi_check_form(){
 
    $html ="";
       
                
                $html .= '<div class="form-horizontal">
                
                <div class="form-group">
                  <label class="control-label col-sm-2" for="pwd">Ellenörzés dátuma:</label>
                  <div class="col-sm-3">          
                  <input type="date" class="form-control" id="pe_date" placeholder="'.$this->date.'"  value="'.$this->date.'" name="public_date" readonly>
                  </div>
                  <label class="control-label col-sm-2" for="pwd">Ment:</label>
                  <div class="col-sm-2">
                    <button type="" onclick="penzugyicheck_post()" class="btn btn-danger">OK</button><snap id="safe_check"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-2" for="pwd">Ellenörzés szövege:</label>
                  <div class="col-sm-10">          
                    <textarea id="summernote" name="editordata"></textarea>
                  </div>
                </div>
               
               
                <input type="text"  id="pe_recepcio" value="'.$this->recepcios.'"  hidden>
              </div>';
 
    
    
    
    return $html;
   
 }
 
 
 public function NapiPuCheckView($date,$telephely){
    
    $html = '<h2 class="c6" >Artéria pénzügyi ellenörzés</h2>';
     
    $sql = "SELECT * FROM napi_pu_check WHERE pucheck_date = '$date' AND pucheck_telephely = '$telephely' ORDER BY pucheck_id DESC LIMIT 1 ";
    $result = mysqli_query($this->dbconn, $sql);

    if (mysqli_num_rows($result) > 0) {
      // output data of each row
      while($row = mysqli_fetch_assoc($result)) {
        $html .= $row["pucheck_info"];
      }
    } else {
      $html .=  "Nincs rögzített ellenörzés!";
    } 
      
     return $html;
 }
 
}