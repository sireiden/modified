<?php
/*
 Datei für die Migration einheitlicher Tabellen
 */

?>
<html>
<head>
<style type="text/css">
html,body { font-family:Verdana; font-size:14px; margin:0 auto; height:100%; width:100%; }
h1 {margin-left:10px;font-size:20px;}
h2 {margin-left:10px;font-size:18px;}
h3 {margin-left:10px;font-size:16px;}
h4 {margin-left:10px;font-size:15px;}
.textcompare{ margin:10px 10px 15px; padding: 7px 4px 7px 6px; width:90%; background-color: #E8E8E8; border:1px solid black}
table {	margin:10px 10px 15px; width: 90%; text-align: left; border:1px solid #000000; border-collapse:collapse; vertical-align:top;}
table th { padding: 7px 4px 7px 6px; vertical-align: top; border:1px solid #000000;font-weight:bold;background-color: #e8e8e8;}
table td { padding: 7px 4px 7px 6px; vertical-align: top; border:1px solid #000000;}
hr { border: 2px solid red; margin:10px 10px 15px; width: 90%; }
</style>
</head>
<body>
<h1>Migration aller Tabellen im Zusammhang mit Ländern, Bundesländern, Steuersätzen und Wöhrungen</h1>
<h2>Folgende Tabellen werden übernommen</h2>
-> geo_zones (Steuerzonen)<br>
-> zones (Bundesländer)<br>
-> zones_to_geo_zones (Land zu Steuerzone<br>
-> tax_class (Steuerklassen)
-> tax_rates (Steuersätze)
-> Währungen
<h3>Die Migration kann durch Aktivierung des Scripts erfolgen</h3>

<?php
$dblink = new PDO("mysql:host=localhost;", 'root', 'REMOVED4SECURITY');
$dblink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$truncate_geo_zones_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`geo_zones`');
$truncate_geo_zones_stmt->execute();

$insert_geo_zones_stmt =  $dblink->prepare('INSERT INTO ascasa_ng_db .geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, geo_zone_info, last_modified, date_added) SELECT geo_zone_id, geo_zone_name, geo_zone_description, 0 as geo_zone_info, last_modified, date_added FROM mod_ascasa_live.geo_zones;');
$insert_geo_zones_stmt->execute();

$truncate_zones_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`zones`');
$truncate_zones_stmt->execute();

$insert_zones_stmt =  $dblink->prepare('INSERT INTO ascasa_ng_db .zones (zone_id, zone_country_id, zone_code, zone_name) SELECT zone_id, zone_country_id, zone_code, zone_name FROM mod_ascasa_live.zones;');
$insert_zones_stmt->execute();


$truncate_zones_to_geo_zones_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`zones_to_geo_zones`');
$truncate_zones_to_geo_zones_stmt->execute();

$insert_zones_to_geo_zones_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db .zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) SELECT association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added FROM mod_ascasa_live.zones_to_geo_zones;');
$insert_zones_to_geo_zones_stmt->execute();

$truncate_tax_class_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`tax_class`');
$truncate_tax_class_stmt->execute();

$insert_tax_class_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db .tax_class (tax_class_id, tax_class_title, tax_class_description, last_modified, date_added) SELECT tax_class_id, tax_class_title, tax_class_description, last_modified, date_added FROM mod_ascasa_live.tax_class;');
$insert_tax_class_stmt->execute();

$truncate_tax_rates_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`tax_rates`');
$truncate_tax_rates_stmt->execute();

$insert_tax_rates_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db .tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_priority, tax_rate, tax_description, last_modified, date_added) SELECT tax_rates_id, tax_zone_id, tax_class_id, tax_priority, tax_rate, tax_description, last_modified, date_added FROM mod_ascasa_live.tax_rates;');
$insert_tax_rates_stmt->execute();

$truncate_currencies_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`currencies`');
$truncate_currencies_stmt->execute();

$insert_currencies_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db .currencies (currencies_id, title, code, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value, last_updated, status) SELECT currencies_id, title, code, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value, last_updated, 1 as status FROM mod_ascasa_live.currencies;');
$insert_currencies_stmt->execute();

