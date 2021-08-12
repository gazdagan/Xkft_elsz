<?php

/* 
 * készletek áthelyezése felhasználóknak
 */



include ("./includes/keszletnyilvantartasClass.php");
echo'<script>';
include ("./js/copyclipboard.js");
include ("./js/ajax.js");
include ("./js/keszletkezeles.js");
echo '</script>';

echo'<div class="container" id ="store_content">';

$keszletmozgas = new keszletnyilvantartasClass();
echo $keszletmozgas -> szallitolevel_rendelok_kozott();
echo '</div>';