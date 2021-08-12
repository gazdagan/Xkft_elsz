<?php

/* 
 *Napon belüli átadás frontend 
 *
 */
$atadas = new napi_elszamolas;
$userselect = new User_Select_NapiElsz;
//$szamla =  new Szamla_befogadasClass;
$html ="";


$html .= '<div class="container">';
$html .= '<div id="HiddenIfPrint" "><a href="#"onclick="StartPrtintPage()" tpye="button" class="btn btn-info" role="button"><i class="fa fa-print" aria-hidden="true"></i> Nyomtat</a></div>';

$html .= '<div class="row"  id="">';
 
$html .= ' <table class="table">
    <thead>
      <tr>
        <th>Átadó: <h2>Medical Point</h2><br>1138 Budapest, <br> Madarász Viktor utca 47-49</th>
        <th>Átvevő: <h2>Medical Plusz Kft.</h2><br>1037 Budapest<br> Bokor utca 15-21. földszint 19.</th>
       
      </tr>
    </thead>
    ';
$html .=  '</div>'; 
echo $html; 
$html = "";
 
echo $userselect ->user_select_all_bevetelektipusok('%');

//$userselect ->user_select_kpkivet_table();
//$szamla ->Visualize_All_Szamla_Table_User("NULL");
echo $userselect -> user_select_napiadat_table('%');



$html .= '<div class="row"  id="">';

$html .= ' <table class="table">
    <thead>
      <tr>
        <th>Átadó:....................................................<br>Medical Point - 1138 Budapest, Madarász Viktor utca 47-49. fsz. </th>
        <th>Átvveő:...................................................<br>Medical Plusz Kft. - 1037 Budapest, Bokor utca 15-21. földszint 19.</th>
       
      </tr>
    </thead></table>';
$html .=  '</div>'; 
echo $html; 

//echo '</div>';
echo '</div>'; 

echo '<script>
function StartPrtintPage(){
    document.getElementById("HiddenIfPrint").style.display = "none"; 
    $(".hideIfPrint").hide();
    //$(".container").css("margin")="10px";
    window.print();
}';   
include ('./js/tablarendezes.js')  ; 

echo'</script>';

?>
