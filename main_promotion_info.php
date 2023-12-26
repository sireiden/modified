<?php
/* -----------------------------------------------------------------------------------------
 * Sonderaktionen - Detailseite
 * Michael Förster - brainsquad
 * 2019-03-11
 *----------------------------------------------------------------------------------------- */

require_once ('includes/application_top.php');

// create smarty elements
$smarty = new Smarty;

// include boxes
require (DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/boxes.php');

$promotion_query = "select * from ".TABLE_MAIN_PROMOTIONS." WHERE id = '".(int)$_GET['promotion_id']."' AND status = 1";
$promotion_query_result = xtc_db_query($promotion_query);


if($promotion = xtc_db_fetch_array($promotion_query_result)) {
    $manufacturers_query = "select * from " . TABLE_MANUFACTURERS . " WHERE manufacturers_id = '".(int)$promotion['manufacturers_id']."'";
    $manufacturers_query_result = xtc_db_query($manufacturers_query);
    $manufacturers = xtc_db_fetch_array($manufacturers_query_result);
    
    $breadcrumb->add('Sonderaktionen', xtc_href_link('Aktionen.html'));
    $breadcrumb->add($promotion['name'], xtc_href_link('Aktionen/' . seo_url_href_mask($manufacturers['manufacturers_name']) . '/' .  seo_url_href_mask($promotion['name']).'-:-'.$promotion['id'].'.html'));
}
else {
    $site_error = TEXT_CONTENT_NOT_FOUND;
    $promotion['name'] = TEXT_CONTENT_NOT_FOUND; 
    $breadcrumb->add('Sonderaktionen', xtc_href_link('Aktionen.html'));
}

if ($promotion['image'] != '') {
    $promotion_image = HTTPS_SERVER.'/'.DIR_WS_IMAGES.$promotion['image'];
}
else {
    $promotion_image = '';
}
    
require (DIR_WS_INCLUDES.'header.php');
  
$smarty->assign('CONTENT_HEADING', $promotion['name']);
$smarty->assign('CONTENT_BODY', remove_word_code($promotion['description']));
$smarty->assign('CONTENT_IMAGE', $promotion_image);
$smarty->assign('language', $_SESSION['language']);
    
if (!CacheCheck()) {
    $smarty->caching = 0;
    $main_content = $smarty->fetch(CURRENT_TEMPLATE.'/module/main_promotion_info.html');
} 
else {
    $smarty->caching = 1;
    $smarty->cache_lifetime = CACHE_LIFETIME;
    $smarty->cache_modified_check = CACHE_CHECK;
    $cache_id = md5($_SESSION['language'].$_SESSION['customers_status']['customers_status'].$promotion['id'].((isset($_REQUEST['error'])) ? $_REQUEST['error'] : ''));
    $main_content = $smarty->fetch(CURRENT_TEMPLATE.'/module/main_promotion_info.html', $cache_id);
}
    
$smarty->assign('language', $_SESSION['language']);
$smarty->assign('main_content', $main_content);
$smarty->caching = 0;
if (!defined('RM')) 
    $smarty->load_filter('output', 'note');
$smarty->display(CURRENT_TEMPLATE.'/index.html');
include ('includes/application_bottom.php');
 