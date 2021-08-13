


Date:2021.08.13
Title:Napi elszámolás összevetése Artéria zárással //Artéria pénzügyi ellenörzés

Files:
includes/PenzugyiEllenorzesClass.php // new class
includes/User_Select_NapiOsszesitoClass.php
includes/Menu_class.php
js/pu_check.js
postGetAjax/post_napi_pu_check.php // new
partials/users/napi_osszesito.php
partials/users/penzforgalmi_ellenorzes.php // new
partials/admin/rendeloknapiriport.php

DB:
CREATE TABLE `napi_pu_check` (
  `pucheck_id` int(11) NOT NULL COMMENT 'napi check id',
  `pucheck_telephely` varchar(254) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'rendelő ID',
  `pucheck_date` date NOT NULL COMMENT 'ellenörzés dátuma',
  `pucheck_info` mediumtext COLLATE utf8_hungarian_ci NOT NULL COMMENT 'adat ellenörző kép tartalom',
  `pucheck_recepcio` varchar(254) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'recepció rögzítő',
  `pucheck_timestamp` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'timestamp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;


--------------------
Feltöltve először 2021.08.12 - 16:11