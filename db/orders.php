<?php
/*
 Datei für die Migration der Bestellungen
 */

$dblink = new PDO("mysql:host=localhost;", 'root', 'REMOVED4SECURITY');
$dblink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

 $additional_order_fields_stmt = $dblink->prepare("ALTER TABLE `ascasa_ng_db`.`orders`
ADD `customers_telephone_optional` VARCHAR(32) NULL AFTER `customers_telephone`, 
ADD `invoice_date` BIGINT(20) NULL DEFAULT '0' AFTER `orders_ident_key`, 
ADD `csv_exported` TINYINT(1) NOT NULL DEFAULT '0' AFTER `invoice_date`,
ADD `referer` TEXT NULL AFTER `csv_exported`,
ADD `idealo_order_number` VARCHAR(40) NOT NULL AFTER `referer`,
ADD `amazon_order_id` VARCHAR(255) NOT NULL AFTER `idealo_order_number`,
ADD `amazon_contract_id` VARCHAR(255) NULL AFTER `amazon_order_id`,
ADD `contact_bewertung` TINYINT(1) NOT NULL DEFAULT '0' AFTER `amazon_contract_id`,
ADD `templateset` VARCHAR(50) NOT NULL AFTER `conctact_bewertung`,
ADD `kvnummer` VARCHAR(20) NOT NULL AFTER `templateset`;");

$additional_order_fields_stmt->execute(array());

$additional_order_products_fields_stmt = $dblink->prepare("ALTER TABLE `ascasa_ng_db`.`orders_products`
ADD `AmazonOrderItemCode` VARCHAR(255) NOT NULL AFTER `products_weight`,
ADD `AmazonShippingCosts` DECIMAL(10,2) NOT NULL AFTER `AmazonOrderItemCode`,
ADD `AmazonCoupon` DECIMAL(10,2) NOT NULL AFTER `AmazonShippingCosts`");
$additional_order_products_fields_stmt->execute(array());
 


// Orders
// customers_gender
//customers_country_iso_code_2
//delivery_gender
//billing_gender
//languages_id


$truncate_orders_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`orders`');
$truncate_orders_stmt->execute();

$insert_orders_stmt = $dblink->prepare("INSERT INTO ascasa_ng_db.orders (orders_id, customers_id, customers_cid, customers_vat_id, customers_status, customers_status_name, customers_status_image, customers_status_discount, customers_name, customers_firstname, customers_lastname, customers_company, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_telephone_optional, customers_email_address, customers_address_format_id, delivery_name, delivery_firstname, delivery_lastname, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_country_iso_code_2, delivery_address_format_id, billing_name, billing_firstname, billing_lastname, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_country, billing_country_iso_code_2, billing_address_format_id, payment_method, comments, last_modified, date_purchased, orders_status, orders_date_finished, currency, currency_value, account_type, payment_class, shipping_method, shipping_class, customers_ip, language, afterbuy_success, afterbuy_id, refferers_id, conversion_type, orders_ident_key, invoice_date, csv_exported, referer, idealo_order_number, amazon_order_id, amazon_contract_id, contact_bewertung, templateset, kvnummer, customers_gender, customers_country_iso_code_2, delivery_gender, billing_gender, languages_id) 
SELECT orders_id, customers_id, customers_cid, customers_vat_id, customers_status, customers_status_name, customers_status_image, customers_status_discount, customers_name, customers_firstname, customers_lastname, customers_company, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_telephone_optional, customers_email_address, customers_address_format_id, delivery_name, delivery_firstname, delivery_lastname, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_country_iso_code_2, delivery_address_format_id, billing_name, billing_firstname, billing_lastname, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_country, billing_country_iso_code_2, billing_address_format_id, payment_method, comments, last_modified, date_purchased, orders_status, orders_date_finished, currency, currency_value, account_type, payment_class, shipping_method, shipping_class, customers_ip, language, afterbuy_success, afterbuy_id, refferers_id, conversion_type, orders_ident_key, invoice_date, csv_exported, referer, idealo_order_number, amazon_order_id, amazon_contract_id, contact_bewertung, templateset, kvnummer, '' as customers_gender, '' as customers_country_iso_code_2, '' as delivery_gender, '' as billing_gender, 2 as languages_id FROM mod_ascasa_live.orders;");
$insert_orders_stmt->execute();

$update_gender_stmt = $dblink->prepare("Update `ascasa_ng_db`.`orders` set customers_gender = (select customers_gender from customers where customers_id = orders.customers_id) where customers_gender = ''");
$update_gender_stmt->execute();

// orders_products
$truncate_orders_products_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`orders_products`');
$truncate_orders_products_stmt->execute();

$insert_orders_products_stmt = $dblink->prepare("INSERT INTO ascasa_ng_db.orders_products (orders_products_id, orders_id, products_id, products_model, products_name, products_price, products_discount_made, products_shipping_time, final_price, products_tax, products_quantity, allow_tax, products_order_description, AmazonOrderItemCode, AmazonShippingCosts, AmazonCoupon, products_price_origin, products_weight) 
SELECT orders_products_id, orders_id, products_id, products_model, products_name, products_price, products_discount_made, products_shipping_time, final_price, products_tax, products_quantity, allow_tax, products_order_description, AmazonOrderItemCode, AmazonShippingCosts, AmazonCoupon, 0 as products_price_origin, 0 as products_weight FROM mod_ascasa_live.orders_products;");
$insert_orders_products_stmt->execute();

// orders products attributes
$truncate_orders_products_attributes_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`orders_products_attributes`');
$truncate_orders_products_attributes_stmt->execute();

$insert_orders_products_attributes_stmt = $dblink->prepare("INSERT INTO ascasa_ng_db.orders_products_attributes (orders_products_attributes_id, orders_id, orders_products_id, products_options, products_options_values, options_values_price, price_prefix, orders_products_options_id, orders_products_options_values_id, products_attributes_id, options_values_weight, weight_prefix) 
SELECT orders_products_attributes_id, orders_id, orders_products_id, products_options, products_options_values, options_values_price, price_prefix, orders_products_options_id, orders_products_options_values_id, products_attributes_id, 0 as options_values_weight, '+' as weight_prefix FROM mod_ascasa_live.orders_products_attributes;");
$insert_orders_products_attributes_stmt->execute();

// orders products downloads
$truncate_orders_products_download_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`orders_products_download`');
$truncate_orders_products_download_stmt->execute();

$insert_orders_products_download_stmt = $dblink->prepare("INSERT INTO ascasa_ng_db.orders_products_download (orders_products_download_id, orders_id, orders_products_id, orders_products_filename, download_maxdays, download_count) SELECT orders_products_download_id, orders_id, orders_products_id, orders_products_filename, download_maxdays, download_count FROM mod_ascasa_live.orders_products_download;");
$insert_orders_products_download_stmt->execute();

// orders recalculate
$truncate_orders_recalculate_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`orders_recalculate`');
$truncate_orders_recalculate_stmt->execute();

$insert_orders_recalculate_stmt = $dblink->prepare("INSERT INTO ascasa_ng_db.orders_recalculate (orders_recalculate_id, orders_id, n_price, b_price, tax, tax_rate, class) SELECT orders_recalculate_id, orders_id, n_price, b_price, tax, tax_rate, class FROM mod_ascasa_live.orders_recalculate;");
$insert_orders_recalculate_stmt->execute();

// orders status
$truncate_orders_status_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`orders_status`');
$truncate_orders_status_stmt->execute();

$insert_orders_status_stmt = $dblink->prepare("INSERT INTO ascasa_ng_db.orders_status (orders_status_id, language_id, orders_status_name, sort_order) SELECT orders_status_id, language_id, orders_status_name, sort_order FROM mod_ascasa_live.orders_status;");
$insert_orders_status_stmt->execute();

// orders status history
$truncate_orders_status_history_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`orders_status_history`');
$truncate_orders_status_history_stmt->execute();

$insert_orders_status_history_stmt = $dblink->prepare("INSERT INTO ascasa_ng_db.orders_status_history (orders_status_history_id, orders_id, orders_status_id, date_added, customer_notified, comments, comments_sent) SELECT orders_status_history_id, orders_id, orders_status_id, date_added, customer_notified, comments, comments_sent FROM mod_ascasa_live.orders_status_history;");
$insert_orders_status_history_stmt->execute();

// orders total
$truncate_orders_status_history_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`orders_total`');
$truncate_orders_status_history_stmt->execute();

$insert_orders_status_history_stmt = $dblink->prepare("INSERT INTO ascasa_ng_db .orders_total (orders_total_id, orders_id, title, text, value, class, sort_order) SELECT orders_total_id, orders_id, title, text, value, class, sort_order FROM mod_ascasa_live.orders_total;");
$insert_orders_status_history_stmt->execute();

