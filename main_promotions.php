<?php
/* -----------------------------------------------------------------------------------------
 * Sonderaktionen - Übersichtsseite
 * Michael Förster - brainsquad
 * 2019-03-07
 *----------------------------------------------------------------------------------------- */

require_once ('includes/application_top.php');

// create smarty elements
$smarty = new Smarty;

// include boxes
require (DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/boxes.php');

$promotions_query = "select * from ".TABLE_MAIN_PROMOTIONS." WHERE status = 1 order by sort_order ASC";
$promotions_query = xtc_db_query($promotions_query);

while ($promotion = xtc_db_fetch_array($promotions_query)) {
    $manufacturers_query = "select * FROM " . TABLE_MANUFACTURERS . " WHERE manufacturers_id = '".$promotion['manufacturers_id']."'";
    $manufacturers_result = xtc_db_query($manufacturers_query);
    $manufacturers = xtc_db_fetch_array($manufacturers_result);
    
    if ($manufacturers['manufacturers_image'] != '') {
        $manufacturers_image = DIR_WS_IMAGES.$manufacturers['manufacturers_image'];
    }
    else {
        $manufacturers_image = '';
    }
    
    $promotion['manufacturers_image'] = $manufacturers_image;
    $promotion['manufacturers_url'] = xtc_href_link(FILENAME_DEFAULT, xtc_manufacturer_link((int) $manufacturers['manufacturers_id'], $manufacturers['manufacturers_name']));
    $promotion['manufacturers_name'] = $manufacturers['manufacturers_name'];
    
    if ($promotion['image'] != '') {
        $promotion_image = DIR_WS_IMAGES.$promotion['image'];
    }
    else {
        $promotion_image = '';
    }
    
    $promotion['clean_image'] = $promotion_image;
    
    $promotion['link'] = xtc_href_link('Aktionen/' . seo_url_href_mask($promotion['manufacturers_name']) . '/' .  seo_url_href_mask($promotion['name']).'-:-'.$promotion['id'].'.html');
    
    $promotions[] = $promotion;
}

$breadcrumb->add('Sonderaktionen', xtc_href_link('Aktionen.html'));

require (DIR_WS_INCLUDES.'header.php');

$smarty->assign('PROMOTIONS', $promotions);
$smarty->assign('language', $_SESSION['language']);

// set cache ID
if (!CacheCheck()) {
    $smarty->caching = 0;
    $main_content = $smarty->fetch(CURRENT_TEMPLATE.'/module/main_promotions.html');
} else {
    $smarty->caching = 1;
    $smarty->cache_lifetime = CACHE_LIFETIME;
    $smarty->cache_modified_check = CACHE_CHECK;
    $cache_id = $_SESSION['language'].'main_promotions';
    $main_content = $smarty->fetch(CURRENT_TEMPLATE.'/module/main_promotions.html', $cache_id);
}

$smarty->assign('language', $_SESSION['language']);
$smarty->assign('main_content', $main_content);
$smarty->caching = 0;
if (!defined('RM'))
    $smarty->load_filter('output', 'note');
$smarty->display(CURRENT_TEMPLATE.'/index.html');
include ('includes/application_bottom.php');
?>