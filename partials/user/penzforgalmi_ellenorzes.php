<?php

/* 
 * 
 * pénzforgalmi ellenörzés adatok tárolása / aznapi kép szövage db ben telephelyenként
 */

require_once './includes/PenzugyiEllenorzesClass.php';

echo '<script type="text/javascript">';
include ("./js/pu_check.js");
include ("./js/ajax.js");
echo '</script>';


echo "<script>
    $(document).ready(function() {
        $('#summernote').summernote();
        });
    </script>";

$penzugyi_check = new PenzugyiEllenorzesClass();
echo $penzugyi_check->penzugyi_check_page(); 