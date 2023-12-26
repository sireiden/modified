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
<h1>Migration von Amazon Login and Pay</h1>
<h2>Datei für die Migration der Amazon Transaktionen sowie der Konfiguration</h2>
<h3>Da sich die Konfigurationstabelle in der Struktur nicht unterscheidet, ist kein Löschen oder ähnliches erforderlich. Es ist jedoch obligatorisch, dass das Amazon Modul vollständig deinstalliert wird.</h3>
<h3>Die Migration kann durch Aktivierung des Scripts erfolgen</h3>
<?php 
$dblink = new PDO("mysql:host=localhost;", 'root', 'REMOVED4SECURITY');
$dblink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$insert_configuration_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.configuration SET
                                              configuration_key   = :configuration_key,
                                              configuration_value = :configuration_value,
                                              configuration_group_id    = :configuration_group_id,
                                              sort_order      = :sort_order,
                                              last_modified       = :last_modified,
                                              date_added = :date_added,
                                              use_function = :use_function,
                                              set_function = :set_function');

$select_configuration_stmt = $dblink->prepare('SELECT * FROM mod_ascasa_live.configuration WHERE configuration_key LIKE "AMZ_%" OR configuration_key LIKE "%MODULE_PAYMENT_AM_APA%"');
$select_configuration_stmt->execute();

while($configuration = $select_configuration_stmt->fetch(PDO::FETCH_ASSOC)) {
    $insert_configuration_stmt->execute(array(
        ':configuration_key' => $configuration['configuration_key'],
        ':configuration_value' => $configuration['configuration_value'],
        ':configuration_group_id' => $configuration['configuration_key'],
        ':sort_order' => $configuration['sort_order'],
        ':last_modified' => $configuration['last_modified'],
        ':date_added' => $configuration['date_added'],
        ':use_function' => $configuration['use_function'],
        ':set_function' => $configuration['set_function'],
    ));
}

$truncate_amz_transactions_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`amz_transactions`');
$truncate_amz_transactions_stmt->execute();

$insert_amz_transactions_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.amz_transactions SET
                                              amz_tx_id              = :amz_tx_id,
                                              amz_tx_order_reference   = :amz_tx_order_reference,
                                              amz_tx_type = :amz_tx_type,
                                              amz_tx_time    = :amz_tx_time,
                                              amz_tx_expiration      = :amz_tx_expiration,
                                              amz_tx_amount       = :amz_tx_amount,
                                              amz_tx_amount_refunded = :amz_tx_amount_refunded,
                                              amz_tx_status = :amz_tx_status,
                                              amz_tx_reference = :amz_tx_reference,
                                              amz_tx_amz_id = :amz_tx_amz_id,
                                              amz_tx_last_change = :amz_tx_last_change,
                                              amz_tx_last_update = :amz_tx_last_update,
                                              amz_tx_order = :amz_tx_order,
                                              amz_tx_customer_informed = :amz_tx_customer_informed,
                                              amz_tx_admin_informed = :amz_tx_admin_informed,
                                              amz_tx_merchant_id = :amz_tx_merchant_id,
                                              amz_tx_mode = :amz_tx_mode,
                                              amz_tx_currency = :amz_tx_currency');

$select_amz_transactions_stmt = $dblink->prepare('SELECT * FROM mod_ascasa_live.amz_transactions');
$select_amz_transactions_stmt->execute();

while($amz_transaction = $select_amz_transactions_stmt->fetch(PDO::FETCH_ASSOC)) {
    $insert_amz_transactions_stmt->execute($amz_transaction);
}

?>
