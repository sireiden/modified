<?php
/* 
Nur in der Live-Datenbank
products_shippingcost -> To Be Discussed
products_cod_cost -> To Be Discussed
group_ids, -> Zero for all of them
products_min_order, -> Not in Use any longer
products_shippinginfo, -> Moved to products_fields 
products_cartspecial, -> Not Required anymore
products_cash_discount -> Moved to products_fields 
products_cod_fee, -> Not in Use any longer
products_giropay_fee, -> Not in Use any longer
products_paypal_fee, -> Not in Use any longer
products_rechnung_fee, -> Not in Use any longer
products_sofortueberweisung_fee, -> Not in Use any longer
products_kreditkarte_fee, -> Not in Use any longer
products_debit_fee, -> Not in Use any longer
products_amazon_fee, -> Not in Use any longer
products_efficiency, -> moved to produkttabs
products_efficiency_class, -> Moved to products_fields 
products_datasheet, -> moved to produkttabs
products_tab_1, -> moved to produkttabs
products_tab_1_headline, -> moved to produkttabs
products_tab_2, -> moved to produkttabs
products_tab_2_headline, -> moved to produkttabs
products_tab_3, -> moved to produkttabs
products_tab_3_headline, -> moved to produkttabs
products_tab_4, -> moved to produkttabs
products_tab_4_headline, -> moved to produkttabs
products_tab_5, -> moved to produkttabs
products_tab_5_headline, -> moved to produkttabs
products_extra_text, 
products_best_price, -> Moved to products_fields 
manufacturers_additional_info_1, -> Moved to products_fields 
manufacturers_additional_info_2, -> Moved to products_fields 
manufacturers_additional_info_3, -> Moved to products_fields 
manufacturers_additional_info_4, -> Moved to products_fields 
manufacturers_additional_info_5, -> Moved to products_fields 
direktkauf, -> Moved to products_fields 
direktkauf_range,  -> Moved to products_fields 
direktkauf_fulfillment, -> Moved to products_fields 
garantie_banner, ->  Moved to products_fields
group_permission_5 -> Added

Beide Datenbanken
products_id, products_ean, products_quantity, products_shippingtime, products_model, group_permission_0, group_permission_1, group_permission_2, 
group_permission_3, products_sort, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, 
products_weight, products_status, products_tax_class_id, product_template, options_template, manufacturers_id, products_manufacturers_model, products_ordered, products_fsk18, products_vpe, products_vpe_status, 
products_vpe_value, products_startpage, products_startpage_sort, group_permission_4

        ':products_id'  => $product['products_id'],
        ':products_cash_discount'      => $product['products_cash_discount'],
        ':products_efficiency_class'   => $product['products_efficiency_class'],
        ':products_shippinginfo'       => $product['products_shippinginfo'],
        ':products_best_price'         => $product['products_best_price'],
        ':products_directbuy'          => $product['direktkauf'],
        ':products_directbuy_range'    => $product['direktkauf_range'],
        ':products_directbuy_range'    => $product['direktkauf_fulfillment'],
        
*/

$dblink = new PDO("mysql:host=localhost;", 'root', 'REMOVED4SECURITY');
$dblink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);


$truncate_products_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`products`');
$truncate_products_stmt->execute();

$products_insert_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.products SET
                                              products_id              = :products_id,
                                              products_ean   = :products_ean,
                                              products_quantity = :products_quantity,
                                              products_shippingtime    = :products_shippingtime,
                                              products_model      = :products_model,
                                              group_permission_0       = :group_permission_0,
                                              group_permission_1 = :group_permission_1,
                                              group_permission_2 = :group_permission_2,
                                              group_permission_3              = :group_permission_3,
                                              products_sort   = :products_sort,
                                              products_image = :products_image,
                                              products_price    = :products_price,
                                              products_discount_allowed      = :products_discount_allowed,
                                              products_date_added       = :products_date_added,
                                              products_last_modified = :products_last_modified,
                                              products_date_available = :products_date_available,
                                              products_weight      = :products_weight,
                                              products_status       = :products_status,
                                              products_tax_class_id = :products_tax_class_id,
                                              product_template              = :product_template,
                                              options_template   = :options_template,
                                              manufacturers_id = :manufacturers_id,
                                              products_manufacturers_model    = :products_manufacturers_model,
                                              products_ordered      = :products_ordered,
                                              products_fsk18       = :products_fsk18,
                                              products_vpe = :products_vpe,
                                              products_vpe_status = :products_vpe_status,
                                              products_vpe_value   = :products_vpe_value,
                                              products_startpage = :products_startpage,
                                              products_startpage_sort    = :products_startpage_sort,
                                              group_permission_4      = :group_permission_4,
                                              group_permission_5      = :group_permission_5
                                              ');

$products_stmt = $dblink->prepare('SELECT * FROM mod_ascasa_live.products ORDER BY products_id ASC');
$products_stmt->execute(array());
while($product = $products_stmt->fetch()) {
    if($product['products_image'] == null) {
        $product['products_image'] = '';
    }
    
    $products_insert_stmt->execute(array(
        ':products_id'                 => $product['products_id'],
        ':products_ean'      => $product['products_ean'],
        ':products_quantity'   => $product['products_quantity'],
        ':products_shippingtime'       => $product['products_shippingtime'],
        ':products_model'         => $product['products_model'],
        ':group_permission_0'          => $product['group_permission_0'],
        ':group_permission_1'    => $product['group_permission_1'],
        ':group_permission_2'    => $product['group_permission_2'],
        ':group_permission_3' => $product['group_permission_3'],
        ':products_sort'      => $product['products_sort'],
        ':products_image'   => $product['products_image'],
        ':products_price'       => $product['products_price'],
        ':products_discount_allowed'         => $product['products_discount_allowed'],
        ':products_date_added'          => $product['products_date_added'],
        ':products_last_modified'    => $product['products_last_modified'],
        ':products_date_available'    => $product['products_date_available'],
        ':products_weight' => $product['products_weight'],
        ':products_status'      => $product['products_status'],
        ':products_tax_class_id'      => $product['products_tax_class_id'],
        ':product_template'   => $product['product_template'],
        ':options_template'       => $product['options_template'],
        ':manufacturers_id'         => $product['manufacturers_id'],
        ':products_manufacturers_model'          => $product['products_manufacturers_model'],
        ':products_ordered'    => $product['products_ordered'],
        ':products_fsk18'    => $product['products_fsk18'],
        ':products_vpe' => $product['products_vpe'],
        ':products_vpe_status'         => $product['products_vpe_status'],
        ':products_vpe_value'          => $product['products_vpe_value'],
        ':products_startpage'    => $product['products_startpage'],
        ':products_startpage_sort'    => $product['products_startpage_sort'],
        ':group_permission_4' => $product['group_permission_4'],
        ':group_permission_5' => $product['group_permission_5'],
    )) ;
}


// Product Description
$truncate_products_description_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`products_description`');
$truncate_products_description_stmt->execute();

$insert_products_description_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.products_description (products_id, language_id, products_name, products_heading_title, products_description, products_short_description, products_keywords, products_meta_title, products_meta_description, products_meta_keywords, products_url, products_viewed, products_order_description) SELECT products_id, language_id, products_name, substring(products_name,1, 43) as products_heading_title, products_description, products_short_description, products_keywords, products_meta_title, products_meta_description, products_meta_keywords, products_url, products_viewed, products_order_description FROM mod_ascasa_live.products_description;');
$insert_products_description_stmt->execute();


// Produkt-Attribute
$truncate_products_attributes_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`products_attributes`');
$truncate_products_attributes_stmt->execute();

$insert_products_attributes_stmt = $dblink->prepare('INSERT INTO `ascasa_ng_db`.`products_attributes` 
(products_attributes_id, products_id, options_id, options_values_id, options_values_price, price_prefix, attributes_model, attributes_stock, options_values_weight, weight_prefix, sortorder, attributes_ean, attributes_vpe_id, attributes_vpe_value) 
SELECT products_attributes_id, products_id, options_id, options_values_id, options_values_price, price_prefix, attributes_model, attributes_stock, options_values_weight, weight_prefix, sortorder, attributes_ean, 0 as attributes_vpe_id, 0.0000 as attributes_vpe_value FROM mod_ascasa_live.products_attributes;');
$insert_products_attributes_stmt->execute();

// Produkt-Attribute Downloads
$truncate_products_attributes_download_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`products_attributes_download`');
$truncate_products_attributes_download_stmt->execute();

$insert_products_attributes_download_stmt = $dblink->prepare('INSERT INTO `ascasa_ng_db`.`products_attributes_download`
(products_attributes_id, products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount)
SELECT products_attributes_id, products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount FROM mod_ascasa_live.products_attributes_download;');
$insert_products_attributes_download_stmt->execute();

// Produkt Content
$truncate_products_content_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`products_content`');
$truncate_products_content_stmt->execute();

$insert_products_content_stmt = $dblink->prepare('INSERT INTO `ascasa_ng_db`.`products_content`
(content_id, products_id, group_ids, content_name, content_file, content_link, languages_id, content_read, file_comment)
SELECT content_id, products_id, group_ids, content_name, content_file, content_link, languages_id, content_read, file_comment
FROM mod_ascasa_live.products_content;');
$insert_products_content_stmt->execute();

// Produkt Graduated Prices
$truncate_products_graduated_prices_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`products_graduated_prices`');
$truncate_products_graduated_prices_stmt->execute();

$insert_products_graduated_prices_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.products_graduated_prices (products_id, quantity, unitprice) SELECT products_id, quantity, unitprice FROM mod_ascasa_live.products_graduated_prices;');
$insert_products_graduated_prices_stmt->execute();

// Produkt Images
$truncate_products_images_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`products_images`');
$truncate_products_images_stmt->execute();

$insert_products_images_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.products_images (image_id, products_id, image_nr, image_name) SELECT image_id, products_id, image_nr, image_name FROM mod_ascasa_live.products_images;');
$insert_products_images_stmt->execute();

// Produkt Notifications
$truncate_notifications_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`products_notifications`');
$truncate_notifications_stmt->execute();

$insert_notifications_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.products_notifications (products_id, customers_id, date_added) SELECT products_id, customers_id, date_added FROM mod_ascasa_live.products_notifications;');
$insert_notifications_stmt->execute();

// Produkt Optionen
$truncate_options_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`products_options`');
$truncate_options_stmt->execute();

$insert_options_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.products_options (products_options_id, language_id, products_options_name, products_options_sortorder) SELECT products_options_id, language_id, products_options_name, products_options_sortorder FROM mod_ascasa_live.products_options;');
$insert_options_stmt->execute();

// Produkt Options Werte
$truncate_options_values_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`products_options_values`');
$truncate_options_values_stmt->execute();

$insert_options_values_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.products_options_values (products_options_values_id, language_id, products_options_values_name, products_options_values_sortorder) SELECT products_options_values_id, language_id, products_options_values_name, 0 as products_options_values_sortorder FROM mod_ascasa_live.products_options_values;');
$insert_options_values_stmt->execute();

// Produkt Optionen Zuordnung
$truncate_products_options_values_to_products_options_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`products_options_values_to_products_options`');
$truncate_products_options_values_to_products_options_stmt->execute();

$insert_products_options_values_to_products_options_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.products_options_values_to_products_options (products_options_values_to_products_options_id, products_options_id, products_options_values_id) SELECT products_options_values_to_products_options_id, products_options_id, products_options_values_id FROM mod_ascasa_live.products_options_values_to_products_options;');
$insert_products_options_values_to_products_options_stmt->execute();


// Produkte zu Anhängen
$drop_products_tabs_stmt = $dblink->prepare('DROP TABLE IF EXISTS `ascasa_ng_db`.`products_to_attachments`');
$drop_products_tabs_stmt->execute();

$create_products_attachments_stmt = $dblink->prepare("CREATE TABLE `ascasa_ng_db`.`products_to_attachments` (
  `products_id` int(11) NOT NULL DEFAULT '0',
  `attachment_id` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
$create_products_attachments_stmt->execute(array());

$create_products_attachments_index_stmt = $dblink->prepare("ALTER TABLE `ascasa_ng_db`.`products_to_attachments`
  ADD PRIMARY KEY (`products_id`,`attachment_id`),
  ADD KEY `idx_attachment_id` (`attachment_id`);");
$create_products_attachments_index_stmt->execute(array());

$insert_products_attachments_index_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.products_to_attachments (products_id, attachment_id, sort) SELECT products_id, attachment_id, sort FROM mod_ascasa_live.products_to_attachments;');
$insert_products_attachments_index_stmt->execute(array());

// Produkte zu Kategorien
$truncate_products_to_categories_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`products_to_categories`');
$truncate_products_to_categories_stmt->execute();

$insert_products_to_categories_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.products_to_categories (products_id, categories_id) SELECT products_id, categories_id FROM mod_ascasa_live.products_to_categories;');
$insert_products_to_categories_stmt->execute();

// Produkte VPE
$truncate_products_vpe_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`products_vpe`');
$truncate_products_vpe_stmt->execute();

$insert_products_vpe_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.products_vpe (products_vpe_id, language_id, products_vpe_name) SELECT products_vpe_id, language_id, products_vpe_name FROM mod_ascasa_live.products_vpe;');
$insert_products_vpe_stmt->execute();

// Produkte XSell
$truncate_products_xsell_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`products_xsell`');
$truncate_products_xsell_stmt->execute();

$insert_products_xsell_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.products_xsell (ID, products_id, products_xsell_grp_name_id, xsell_id, sort_order) SELECT ID, products_id, products_xsell_grp_name_id, xsell_id, sort_order FROM mod_ascasa_live.products_xsell;');
$insert_products_xsell_stmt->execute();

// Personal offers
$truncate_personal_offers_by_customers_status_0_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`personal_offers_by_customers_status_0`');
$truncate_personal_offers_by_customers_status_0_stmt->execute();

$insert_personal_offers_by_customers_status_0_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.personal_offers_by_customers_status_0 (price_id, products_id, quantity, personal_offer) SELECT price_id, products_id, quantity, personal_offer FROM mod_ascasa_live.personal_offers_by_customers_status_0;');
$insert_personal_offers_by_customers_status_0_stmt->execute();

$truncate_personal_offers_by_customers_status_1_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`personal_offers_by_customers_status_1`');
$truncate_personal_offers_by_customers_status_1_stmt->execute();

$insert_personal_offers_by_customers_status_1_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.personal_offers_by_customers_status_1 (price_id, products_id, quantity, personal_offer) SELECT price_id, products_id, quantity, personal_offer FROM mod_ascasa_live.personal_offers_by_customers_status_1;');
$insert_personal_offers_by_customers_status_1_stmt->execute();

$truncate_personal_offers_by_customers_status_2_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`personal_offers_by_customers_status_2`');
$truncate_personal_offers_by_customers_status_2_stmt->execute();

$insert_personal_offers_by_customers_status_2_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.personal_offers_by_customers_status_2 (price_id, products_id, quantity, personal_offer) SELECT price_id, products_id, quantity, personal_offer FROM mod_ascasa_live.personal_offers_by_customers_status_2;');
$insert_personal_offers_by_customers_status_2_stmt->execute();

$truncate_personal_offers_by_customers_status_3_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`personal_offers_by_customers_status_3`');
$truncate_personal_offers_by_customers_status_3_stmt->execute();

$insert_personal_offers_by_customers_status_3_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.personal_offers_by_customers_status_3 (price_id, products_id, quantity, personal_offer) SELECT price_id, products_id, quantity, personal_offer FROM mod_ascasa_live.personal_offers_by_customers_status_3;');
$insert_personal_offers_by_customers_status_3_stmt->execute();

$truncate_personal_offers_by_customers_status_4_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`personal_offers_by_customers_status_4`');
$truncate_personal_offers_by_customers_status_4_stmt->execute();

$insert_personal_offers_by_customers_status_4_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.personal_offers_by_customers_status_4 (price_id, products_id, quantity, personal_offer) SELECT price_id, products_id, quantity, personal_offer FROM mod_ascasa_live.personal_offers_by_customers_status_4;');
$insert_personal_offers_by_customers_status_4_stmt->execute();

$truncate_personal_offers_by_customers_status_5_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`personal_offers_by_customers_status_5`');
$truncate_personal_offers_by_customers_status_5_stmt->execute();

$insert_personal_offers_by_customers_status_5_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.personal_offers_by_customers_status_5 (price_id, products_id, quantity, personal_offer) SELECT price_id, products_id, quantity, personal_offer FROM mod_ascasa_live.personal_offers_by_customers_status_5;');
$insert_personal_offers_by_customers_status_5_stmt->execute();

// Kategorien
$truncate_categories_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`categories`');
$truncate_categories_stmt->execute();

$insert_categories_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.categories (categories_id, categories_image, parent_id, categories_status, categories_template, group_permission_0, group_permission_1, group_permission_2, group_permission_3, listing_template, sort_order, products_sorting, products_sorting2, date_added, last_modified, group_permission_4, group_permission_5) SELECT categories_id, categories_image, parent_id, categories_status, categories_template, group_permission_0, group_permission_1, group_permission_2, group_permission_3, listing_template, sort_order, products_sorting, products_sorting2, date_added, last_modified, group_permission_4, group_permission_5 FROM mod_ascasa_live.categories;');
$insert_categories_stmt->execute();

// Kategoriebeschreibung
$truncate_categories_description_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`categories_description`');
$truncate_categories_description_stmt->execute();

$insert_categories_description_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.categories_description (categories_id, language_id, categories_name, categories_heading_title, categories_description, categories_meta_title, categories_meta_description, categories_meta_keywords) SELECT categories_id, language_id, categories_name, categories_heading_title, categories_description, categories_meta_title, categories_meta_description, categories_meta_keywords FROM mod_ascasa_live.categories_description;');
$insert_categories_description_stmt->execute();

include('produktfelder.php');
include('produkttabs.php');



