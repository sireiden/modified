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
<h1>Migration von Tabellen, welche 1:1 übernommen werden können</h1>
<h2>Datei für die Migration von Tabellen, welche 1:1 übernommen werden können. Derzeit sind dies folgende:</h2>
-> order_status (Bestellstatus)<br>
-> shipping_status (Lieferstatus)<br>
-> Lexikon<br>
-> Kundensuchen
<h3>Die Migration kann durch Aktivierung des Scripts erfolgen</h3>

<?php
$dblink = new PDO("mysql:host=localhost;", 'root', 'REMOVED4SECURITY');
$dblink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$truncate_shipping_status_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`shipping_status`');
$truncate_shipping_status_stmt->execute();

$insert_shipping_status_stmt =  $dblink->prepare('INSERT INTO ascasa_ng_db
.shipping_status (shipping_status_id, language_id, shipping_status_name, shipping_status_image) SELECT shipping_status_id, language_id, shipping_status_name, shipping_status_image FROM mod_ascasa_live.shipping_status;');
$insert_shipping_status_stmt->execute();


$truncate_lexikon_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`lexikon`');
$truncate_lexikon_stmt->execute();

$insert_lexikon_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.lexikon (id, keyword, description) SELECT id, keyword, description FROM mod_ascasa_live.lexikon; ');
$insert_lexikon_stmt->execute();

$truncate_mail_template_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`mail_templates`');
$truncate_mail_template_stmt->execute();

$insert_mail_template_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.mail_templates (id, keyword, description) SELECT id, title, mail_text FROM mod_ascasa_live.mail_templates; ');
$insert_mail_template_stmt->execute();

$truncate_countries_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`countries`');
$truncate_countries_stmt->execute();

$insert_countries_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id, `status`, required_zones) SELECT countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id, `status`, 0 as required_zones FROM mod_ascasa_live.countries WHERE countries_id < 240; ');
$insert_countries_stmt->execute();

$additional_country_stmt = $dblink->prepare("INSERT INTO `ascasa_ng_db`.`countries` (`countries_id`, `countries_name`, `countries_iso_code_2`, `countries_iso_code_3`, `address_format_id`, `status`, `required_zones`) VALUES
(240, 'Serbien', 'RS', 'SRB', 1, 0, 0);");
$additional_country_stmt->execute();

$truncate_paypal_payment_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`paypal_payment`');
$truncate_paypal_payment_stmt->execute();

$insert_paypal_payment_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.paypal_payment (paypal_id, orders_id, payment_id, payer_id, transactions_id) SELECT paypal_id, orders_id, payment_id, payer_id, "" as transactions_id FROM mod_ascasa_live.paypal_payment;');
$insert_paypal_payment_stmt->execute();

$truncate_heidelpay_transaction_data_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`heidelpay_transaction_data`');
$truncate_heidelpay_transaction_data_stmt->execute();

$insert_heidelpay_transaction_data_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.heidelpay_transaction_data (uniqueID, orderId, paymentmethod, shortId) SELECT uniqueID, orderId, paymentmethod, shortId FROM mod_ascasa_live.heidelpay_transaction_data; ');
$insert_heidelpay_transaction_data_stmt->execute();

$truncate_amz_transactions_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`amz_transactions`');
$truncate_amz_transactions_stmt->execute();

$insert_amz_transactions_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db .amz_transactions (amz_tx_id, amz_tx_order_reference, amz_tx_type, amz_tx_time, amz_tx_expiration, amz_tx_amount, amz_tx_amount_refunded, amz_tx_status, amz_tx_reference, amz_tx_amz_id, amz_tx_last_change, amz_tx_last_update, amz_tx_order, amz_tx_customer_informed, amz_tx_admin_informed, amz_tx_merchant_id, amz_tx_mode, amz_tx_currency) SELECT amz_tx_id, amz_tx_order_reference, amz_tx_type, amz_tx_time, amz_tx_expiration, amz_tx_amount, amz_tx_amount_refunded, amz_tx_status, amz_tx_reference, amz_tx_amz_id, amz_tx_last_change, amz_tx_last_update, amz_tx_order, amz_tx_customer_informed, amz_tx_admin_informed, amz_tx_merchant_id, amz_tx_mode, amz_tx_currency FROM mod_ascasa_live.amz_transactions;');
$insert_amz_transactions_stmt->execute();

$truncate_campaigns_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`campaigns`');
$truncate_campaigns_stmt->execute();

$insert_campaigns_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db .campaigns (campaigns_id, campaigns_name, campaigns_refID, campaigns_leads, date_added, last_modified) SELECT campaigns_id, campaigns_name, campaigns_refID, campaigns_leads, date_added, last_modified FROM mod_ascasa_live.campaigns;');
$insert_campaigns_stmt->execute();

$truncate_campaigns_ip_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`campaigns_ip`');
$truncate_campaigns_ip_stmt->execute();

$insert_campaigns_ip_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db .campaigns_ip (user_ip, time, campaign) SELECT user_ip, time, campaign FROM mod_ascasa_live.campaigns_ip;');
$insert_campaigns_ip_stmt->execute();

$truncate_cm_file_flags_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`cm_file_flags`');
$truncate_cm_file_flags_stmt->execute();

$insert_cm_file_flags_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db .cm_file_flags (file_flag, file_flag_name) SELECT file_flag, file_flag_name FROM mod_ascasa_live.cm_file_flags;');
$insert_cm_file_flags_stmt->execute();



