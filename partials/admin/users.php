<?php

/* 
 * Budapest Mozgásszervi Begánrendelő Napi Elszámolás Projek
 * Gazdag András  * 
 * Mérnök Informatikus * 
 * Programozó Informatikus * 
 */
//require_once ( "./includes/MunkaidoClass.php");

$usermodosítas  = new UserClass;
$usermodosítas->admin_insert_user();
$usermodosítas->admin_delete_user();


echo '<script>';
include ("./js/admin_modifi_userdata.js");
echo '</script>';


