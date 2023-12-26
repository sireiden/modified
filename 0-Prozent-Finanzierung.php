<?php

include ('includes/application_top.php');

// create smarty elements
$smarty = new Smarty;

// include boxes
require (DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/boxes.php');

$manufacturers_id  = $_GET['manufacturers_id'] = isset($_GET['manufacturers_id']) && xtc_not_null($_GET['manufacturers_id']) ? (int)$_GET['manufacturers_id'] : false;

// build breadcrumb
$breadcrumb->add('Artikel mit 0% Finanzierung', xtc_href_link('0-Prozent-Finanzierung.php', xtc_get_all_get_params()));

// default values
$tax_where    = '';
$cats_list    = '';
$left_join    = '';

// fsk18 lock
$fsk_lock = $_SESSION['customers_status']['customers_fsk18_display'] == '0' ? " AND p.products_fsk18 != '1' " : "";

// group check
$group_check = GROUP_CHECK == 'true' ? " AND p.group_permission_".$_SESSION['customers_status']['customers_status_id']."=1 " : "";

$from = '';
$select = '';

// We are asked to show only specific manufacturer
if (isset($_GET['filter_id']) && xtc_not_null($_GET['filter_id'])) {
    $select .= "m.manufacturers_name, ";
    $from   .= "JOIN ".TABLE_MANUFACTURERS." m
                       ON p.manufacturers_id = m.manufacturers_id
                          AND m.manufacturers_id = '".(int)$_GET['filter_id']."' ";
}

  //build query
  $add_select = 'p.products_manufacturers_model, ';
  $select_str = "SELECT 
                    $add_select.
                    $select
                    p.products_id,
                    p.products_ean,
                    p.products_quantity,
                    p.products_shippingtime,
                    p.products_model,
                    p.products_image,
                    p.products_price AS order_price,
                    p.products_weight,
                    p.products_discount_allowed,
                    p.products_tax_class_id,
                    p.products_fsk18,
                    p.products_vpe,
                    p.products_vpe_status,
                    p.products_vpe_value,
                    p.manufacturers_id,
                    pd.products_name,
                    pd.products_short_description,
                    pd.products_description,
                    p.products_price AS price ";

  $from_str  = "FROM ".TABLE_PRODUCTS." AS p LEFT JOIN ".TABLE_PRODUCTS_DESCRIPTION." AS pd ON (p.products_id = pd.products_id) ";
  $from_str .= "JOIN ".TABLE_PRODUCTS_FIELDS." pf ON (p.products_id = pf.products_id) ";
  $from_str .= $from;

  $min_price = COMMERZFINANZ_MINIMUM_PRICE_TITLE / 1.19;
  //where-string
  $where_str = "
  WHERE p.products_status = 1
  AND p.products_price  >= '".$min_price."'  
  AND pf.products_cash_discount > 0"				 
  .$fsk_lock
  .$group_check;
  
  $sorting = ' ORDER BY pd.products_name ASC';
  $sorting = ((isset($_SESSION['filter_sorting'])) ? $_SESSION['filter_sorting'] : $sorting);
  
  
  $listing_sql = $select_str.$from_str.$where_str.$sorting; 
  
  require (DIR_WS_MODULES.FILENAME_PRODUCT_LISTING);
  require (DIR_WS_INCLUDES.'header.php');

$smarty->assign('language', $_SESSION['language']);
$smarty->caching = 0;
if (!defined('RM')) {
  $smarty->load_filter('output', 'note');
}
$smarty->display(CURRENT_TEMPLATE.'/index.html');
include ('includes/application_bottom.php');
?>