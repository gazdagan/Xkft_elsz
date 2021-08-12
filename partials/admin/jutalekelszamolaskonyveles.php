<?php
/**
 * Jutalék elszámolási lista könyvelés felé drvosok időszaki tétekléei
 * 
 */
require_once './includes/TelephelyClass.inc.php';
require_once './includes/Orvosok_kezelokClass.inc.php';
echo'<script>';
include ("./js/copyclipboard.js");
echo'</script>';

$tableform = new AdminSelectClass();
// ha orvos kérdezi le a jutalékot nem kell a form 

$tableform->select_orvos_idoszaki_jutaléklista();






echo '<script>
    function StartPrtintJutalekPage(){
        
        document.getElementById("HiddenIfPrint").style.display = "none"; 
        
        window.print();

}
</script>'
?>
