<?php
/*
 Datei für die Migration der Versandkosten
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
<h1>Migration der Versandkosten</h1>
<h2>Datei für die Migration aktuell hinterlegten Versandkosten</h2>
<h3>Die Migration kann durch Aktivierung des Scripts erfolgen</h3>

<?php
$dblink = new PDO("mysql:host=localhost;", 'root', 'REMOVED4SECURITY');
$dblink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$live_konfiguration_stmt = $dblink->prepare("SELECT * FROM mod_ascasa_live.configuration WHERE (
configuration_key LIKE 'MODULE_SHIPPING_ZONESE_COUNTRIES_%' OR 
configuration_key LIKE 'MODULE_SHIPPING_ZONESE_COST_%' OR 
configuration_key LIKE 'MODULE_SHIPPING_ZONESE_HANDLING_%' OR 
configuration_key LIKE 'MODULE_SHIPPING_ZONESE_ALLOWED' OR
configuration_key LIKE 'MODULE_SHIPPING_ZONESE_TAX_CLASS')");

$check_konfiguration_stmt = $dblink->prepare("SELECT configuration_id FROM ascasa_ng_db.configuration WHERE configuration_key = :key");
$update_konfiguration_stmt = $dblink->prepare('UPDATE ascasa_ng_db.configuration SET configuration_value = :value WHERE configuration_key = :key');

$insert_konfiguration_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.configuration SET 
                                              configuration_key              = :configuration_key,
                                              configuration_value   = :configuration_value,
                                              configuration_group_id = :configuration_group_id,
                                              sort_order    = :sort_order,
                                              last_modified      = :last_modified,
                                              date_added       = :date_added,
                                              set_function = :set_function,
                                              use_function = :use_function
                                              ');

$live_konfiguration_stmt->execute();
while($konfiguration = $live_konfiguration_stmt->fetch()) {
    $check_konfiguration_stmt->execute(array(':key' => $konfiguration['configuration_key']));
    if($check_konfiguration_stmt->rowCount() == 1) {
        $update_konfiguration_stmt->execute(array(':value' => $konfiguration['configuration_value'], ':key' => $konfiguration['configuration_key']));
    }
    else {
        $insert_konfiguration_stmt->execute(array(
            ':configuration_key'  => $konfiguration['configuration_key'],
            ':configuration_value'  => $konfiguration['configuration_value'],
            ':configuration_group_id'  => $konfiguration['configuration_group_id'],
            ':sort_order'  => $konfiguration['sort_order'],
            ':last_modified'  => $konfiguration['last_modified'],
            ':date_added'  => $konfiguration['date_added'],
            ':set_function'  => $konfiguration['set_function'],
            ':use_function'  => $konfiguration['use_function'],
        )) ;
    }
}


