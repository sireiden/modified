<?php
/*
 Datei für die Migration der Bestellungen
 
 
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
 <h1>Migration der Kunden</h1>
 <h2>Datei für die Migration der Kunden inklusiver zugehörigen Tabellen</h2>
 
 
 <?php
 $dblink = new PDO("mysql:host=localhost;", 'root', 'REMOVED4SECURITY');
 $dblink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
 
 $tables = array('customers', 'customers_basket', 'customers_basket_attributes','customers_info', 'customers_ip', 'customers_memo', 'customer_searches', 'customers_status', 'customers_status_history');
 
 foreach($tables as $table) {
 ${'create_'.$table.'_stmt'} = $dblink->prepare('show create table mod_ascasa_live.'.$table);
 ${'create_'.$table.'_stmt'}->execute();
 ${'create_'.$table} = ${'create_'.$table.'_stmt'}->fetchAll();
 
 ${'create_'.$table.'_ng_stmt'} = $dblink->prepare('show create table ascasa_ng_db.'.$table);
 ${'create_'.$table.'_ng_stmt'}->execute();
 ${'create_'.$table.'_ng'} = ${'create_'.$table.'_ng_stmt'}->fetchAll();
 }
 
 ?>
 <table>
 <tr>
 <th>Bisherige Tabelle</th>
 <th>Neue Tabelle</th>
 </tr>
 <?php foreach($tables as $table) {?>
 <tr>
 <td>
 <p>
 <b><?= $table?></b><br>
 <?= str_replace(',', '<br />', ${'create_'.$table}[0]['Create Table'])?>
 </p>
 </td>
 <td>
 <p>
 <b><?= $table?></b><br>
 <?= str_replace(',', '<br />', ${'create_'.$table.'_ng'}[0]['Create Table'])?>
 </p>
 </td>
 </tr>
 <?php } ?>
 </table>
 <?php
 
 */

$dblink = new PDO("mysql:host=localhost;", 'root', 'REMOVED4SECURITY');
$dblink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$truncate_customers_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`customers`');
$truncate_customers_stmt->execute();

$insert_customers_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.customers (customers_id, customers_cid, customers_vat_id, customers_vat_id_status, customers_warning, customers_status, customers_gender, customers_firstname, customers_lastname, customers_dob, customers_email_address, customers_default_address_id, customers_telephone, customers_fax, customers_password, customers_newsletter, customers_newsletter_mode, member_flag, delete_user, account_type, password_request_key, payment_unallowed, shipping_unallowed, refferers_id, customers_date_added, customers_last_modified, amazon_customer_id) SELECT customers_id, customers_cid, customers_vat_id, customers_vat_id_status, customers_warning, customers_status, customers_gender, customers_firstname, customers_lastname, customers_dob, customers_email_address, customers_default_address_id, customers_telephone, customers_fax, customers_password, customers_newsletter, customers_newsletter_mode, member_flag, delete_user, account_type, password_request_key, payment_unallowed, shipping_unallowed, refferers_id, customers_date_added, customers_last_modified, amazon_customer_id FROM mod_ascasa_live.customers;');
$insert_customers_stmt->execute();

$update_admin_pw_stmt = $dblink->prepare('UPDATE ascasa_ng_db.customers SET customers_password = "$2a$08$OEtIhWSZlWFrFbsoWPcPQOuxY8IdqaSCYcaSeVRV6yuqi0RBPHb0K" where customers_id = 1');
$update_admin_pw_stmt->execute();

$truncate_customers_basket_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`customers_basket`');
$truncate_customers_basket_stmt->execute();

$insert_customers_basket_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.customers_basket (customers_basket_id, customers_id, products_id, customers_basket_quantity, final_price, customers_basket_date_added) SELECT customers_basket_id, customers_id, products_id, customers_basket_quantity, final_price, customers_basket_date_added FROM mod_ascasa_live.customers_basket;');
$insert_customers_basket_stmt->execute();

$truncate_customers_basket_attributes_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`customers_basket_attributes`');
$truncate_customers_basket_attributes_stmt->execute();

$insert_customers_basket_attributes_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.customers_basket_attributes (customers_basket_attributes_id, customers_id, products_id, products_options_id, products_options_value_id) SELECT customers_basket_attributes_id, customers_id, products_id, products_options_id, products_options_value_id FROM mod_ascasa_live.customers_basket_attributes;');
$insert_customers_basket_attributes_stmt->execute();

$truncate_customers_info_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`customers_info`');
$truncate_customers_info_stmt->execute();

$insert_customers_info_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.customers_info (customers_info_id, customers_info_date_of_last_logon, customers_info_number_of_logons, customers_info_date_account_created, customers_info_date_account_last_modified, global_product_notifications) SELECT customers_info_id, customers_info_date_of_last_logon, customers_info_number_of_logons, customers_info_date_account_created, customers_info_date_account_last_modified, global_product_notifications FROM mod_ascasa_live.customers_info;');
$insert_customers_info_stmt->execute();

$truncate_customers_ip_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`customers_ip`');
$truncate_customers_ip_stmt->execute();

$insert_customers_ip_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.customers_ip (customers_ip_id, customers_id, customers_ip, customers_ip_date, customers_host, customers_advertiser, customers_referer_url) SELECT customers_ip_id, customers_id, customers_ip, customers_ip_date, customers_host, customers_advertiser, customers_referer_url FROM mod_ascasa_live.customers_ip;');
$insert_customers_ip_stmt->execute();

$truncate_customers_memo_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`customers_memo`');
$truncate_customers_memo_stmt->execute();

$insert_customers_memo_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.customers_memo (memo_id, customers_id, memo_date, memo_title, memo_text, poster_id) SELECT memo_id, customers_id, memo_date, memo_title, memo_text, poster_id FROM mod_ascasa_live.customers_memo;');
$insert_customers_memo_stmt->execute();

$truncate_customer_searches_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`customers_searches`');
$truncate_customer_searches_stmt->execute();

$insert_customer_searches_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.customers_searches (keyword, counter) SELECT keyword, counter FROM mod_ascasa_live.customers_searches; ');
$insert_customer_searches_stmt->execute();

$truncate_customers_status_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`customers_status`');
$truncate_customers_status_stmt->execute();

$insert_customers_status_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.customers_status (customers_status_id, language_id, customers_status_name, customers_status_public, customers_status_min_order, customers_status_max_order, customers_status_image, customers_status_discount, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_graduated_prices, customers_status_show_price, customers_status_show_price_tax, customers_status_add_tax_ot, customers_status_payment_unallowed, customers_status_shipping_unallowed, customers_status_discount_attributes, customers_fsk18, customers_fsk18_display, customers_status_write_reviews, customers_status_read_reviews, customers_status_reviews_status, customers_status_specials) SELECT customers_status_id, language_id, customers_status_name, customers_status_public, customers_status_min_order, customers_status_max_order, customers_status_image, customers_status_discount, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_graduated_prices, customers_status_show_price, customers_status_show_price_tax, customers_status_add_tax_ot, customers_status_payment_unallowed, customers_status_shipping_unallowed, customers_status_discount_attributes, customers_fsk18, customers_fsk18_display, 0 as customers_status_write_reviews, 0 as customers_status_read_reviews, 0 as customers_status_reviews_status, 1 as customers_status_specials FROM mod_ascasa_live.customers_status;');
$insert_customers_status_stmt->execute();

$truncate_customers_status_history_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`customers_status_history`');
$truncate_customers_status_history_stmt->execute();

$insert_customers_status_history_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.customers_status_history (customers_status_history_id, customers_id, new_value, old_value, date_added, customer_notified) SELECT customers_status_history_id, customers_id, new_value, old_value, date_added, customer_notified FROM mod_ascasa_live.customers_status_history;');
$insert_customers_status_history_stmt->execute();

$truncate_address_book_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`address_book`');
$truncate_address_book_stmt->execute();

$insert_address_book_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.address_book (address_book_id, customers_id, entry_gender, entry_company, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_country_id, entry_zone_id, address_date_added, address_last_modified) SELECT address_book_id, customers_id, entry_gender, entry_company, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_country_id, entry_zone_id, address_date_added, address_last_modified FROM mod_ascasa_live.address_book;');
$insert_address_book_stmt->execute();

$truncate_address_format_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`address_format`');
$truncate_address_format_stmt->execute();

$insert_address_format_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.address_format (address_format_id, address_format, address_summary) SELECT address_format_id, address_format, address_summary FROM mod_ascasa_live.address_format;');
$insert_address_format_stmt->execute();



















