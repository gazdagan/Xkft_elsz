<?php

/* 
 * ORVOS NAPI JUTALÉKLISTA NYOMTATÁSA
 */

require_once './includes/TelephelyClass.inc.php';
require_once './includes/Orvosok_kezelokClass.inc.php';


$jutalekform = new User_Select_NapiOsszesito;
$jutalekform->select_orvos_naipjutaléklista();
echo '<script>
    function StartPrtintJutalekPage(){
        
        document.getElementById("HiddenIfPrint").style.display = "none"; 
        
        window.print();

}
</script>';