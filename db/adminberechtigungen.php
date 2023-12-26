<?php


echo "ALTER TABLE `admin_access` ADD `main_promotions` INT(1) NOT NULL DEFAULT '0' AFTER `paypal_module`;".PHP_EOL;
echo "UPDATE admin_access SET main_promotions = 1;".PHP_EOL;
echo "ALTER TABLE `admin_access` ADD `ajax_request` INT(1) NOT NULL DEFAULT '0' AFTER `main_promotions`;".PHP_EOL;
echo "UPDATE admin_access SET ajax_request = 1;".PHP_EOL;
echo "ALTER TABLE `admin_access` ADD `transfer_product_attachments` INT(1) NOT NULL DEFAULT '0' AFTER `ajax_request`;".PHP_EOL;
echo "UPDATE admin_access SET transfer_product_attachments = 1;".PHP_EOL;
echo "ALTER TABLE admin_access ADD (lexikon int(1) NOT NULL default '0');".PHP_EOL;
echo "UPDATE admin_access SET lexikon='1' WHERE customers_id='1';".PHP_EOL;

echo "ALTER TABLE admin_access ADD (csv_metatags int(1) NOT NULL default '0');".PHP_EOL;
echo "UPDATE admin_access SET csv_metatags = 1;".PHP_EOL;

echo "ALTER TABLE admin_access ADD (csv_shippinginfo int(1) NOT NULL default '0');".PHP_EOL;
echo "UPDATE admin_access SET csv_shippinginfo = 1;".PHP_EOL;

echo "ALTER TABLE admin_access ADD (csv_preisanalyse int(1) NOT NULL default '0');".PHP_EOL;
echo "UPDATE admin_access SET csv_preisanalyse = 1;".PHP_EOL;

echo "ALTER TABLE admin_access ADD (csv_products int(1) NOT NULL default '0');".PHP_EOL;
echo "UPDATE admin_access SET csv_products = 1;".PHP_EOL;

echo "ALTER TABLE admin_access ADD (csv_prices int(1) NOT NULL default '0');".PHP_EOL;
echo "UPDATE admin_access SET csv_prices = 1;".PHP_EOL;

echo "ALTER TABLE admin_access ADD (stats_referer int(1) NOT NULL default '0');".PHP_EOL;
echo "UPDATE admin_access SET stats_referer = 1;".PHP_EOL;

echo "ALTER TABLE admin_access ADD (csv_orders int(1) NOT NULL default '0');".PHP_EOL;
echo "UPDATE admin_access SET csv_orders = 1;".PHP_EOL;

echo "ALTER TABLE admin_access ADD (csv_billing int(1) NOT NULL default '0');".PHP_EOL;
echo "UPDATE admin_access SET csv_billing = 1;".PHP_EOL;

echo "ALTER TABLE admin_access ADD (csv_countryorders int(1) NOT NULL default '0');".PHP_EOL;
echo "UPDATE admin_access SET csv_countryorders = 1;".PHP_EOL;

echo "ALTER TABLE admin_access ADD (stats_customers_searches int(1) NOT NULL default '0');".PHP_EOL;
echo "UPDATE admin_access SET stats_customers_searches = 1;".PHP_EOL;

echo "ALTER TABLE admin_access ADD (products_quick_list int(1) NOT NULL default '0');".PHP_EOL;
echo "UPDATE admin_access SET products_quick_list = 1;".PHP_EOL;

echo "ALTER TABLE admin_access ADD (products_quick_edit int(1) NOT NULL default '0');".PHP_EOL;
echo "UPDATE admin_access SET products_quick_edit = 1;".PHP_EOL;

echo "ALTER TABLE admin_access ADD (csv_maschinenversicherung int(1) NOT NULL default '0');".PHP_EOL;
echo "UPDATE admin_access SET csv_maschinenversicherung = 1;".PHP_EOL;

echo "ALTER TABLE admin_access ADD (csv_yield int(1) NOT NULL default '0');".PHP_EOL;
echo "UPDATE admin_access SET csv_yield = 1;".PHP_EOL;

// Megamenü
echo "ALTER TABLE admin_access ADD (megamenu int(1) NOT NULL default '0');".PHP_EOL;
echo "UPDATE admin_access SET megamenu = 1;".PHP_EOL;
echo "ALTER TABLE admin_access ADD (megamenu_edit int(1) NOT NULL default '0');".PHP_EOL;
echo "UPDATE admin_access SET megamenu_edit = 1;".PHP_EOL;