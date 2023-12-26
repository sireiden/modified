<?php

/* -----------------------------------------------------------------------------------------
 * Herstellerübersichtsseite
 * Michael Förster - brainsquad
 * 2019-03-21
 *
 */
require_once ('includes/application_top.php');

// create smarty elements
$smarty = new Smarty;

// include boxes
require (DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/boxes.php');

$manufacturers_array_total = array();

$manufacturers_query = "select * from " . TABLE_MANUFACTURERS . " WHERE manufacturers_status = 1 order by manufacturers_name";
$manufacturers_query = xtc_db_query($manufacturers_query);

while ($manufacturers = xtc_db_fetch_array($manufacturers_query)) {
    $manufacturers_pages_query = "select page_title, page_id FROM " . TABLE_MANUFACTURERS_PAGES . " WHERE manufacturers_id = '".$manufacturers['manufacturers_id']."' ORDER BY sort_order";
    $manufacturers_pages_result = xtc_db_query($manufacturers_pages_query);
    while($manufacturers_page = xtc_db_fetch_array($manufacturers_pages_result)) {
        $manufacturers_page['url'] = xtc_href_link(seo_url_href_mask($manufacturers['manufacturers_name']) . '/' .  seo_url_href_mask($manufacturers_page['page_title']).MAN_OVERVIEW_DIVIDER.$manufacturers['manufacturers_id'].PAG_DIVIDER.$manufacturers_page['page_id'].'.html');
        $manufacturers['pages'][] = $manufacturers_page;        
    }
    
    if ($manufacturers['manufacturers_image'] != '') {
        $manufacturers_image = DIR_WS_IMAGES.$manufacturers['manufacturers_image'];
    }
    else {
        $manufacturers_image = '';
    }
    
    $manufacturers['url'] = xtc_href_link(FILENAME_DEFAULT, xtc_manufacturer_link($manufacturers['manufacturers_id'],$manufacturers['manufacturers_name']));
    
    $manufacturers['clean_image'] = $manufacturers_image;
    $manufacturers_array_total[] = $manufacturers;
}

$breadcrumb->add('Herstellerübersicht', xtc_href_link('Hersteller.html'));

require (DIR_WS_INCLUDES.'header.php');

$smarty->assign('MANUFACTURERS', $manufacturers_array_total);
$smarty->assign('language', $_SESSION['language']);

// set cache ID
if (!CacheCheck()) {
    $smarty->caching = 0;
    $main_content = $smarty->fetch(CURRENT_TEMPLATE.'/module/manufacturers.html');
} else {
    $smarty->caching = 1;
    $smarty->cache_lifetime = CACHE_LIFETIME;
    $smarty->cache_modified_check = CACHE_CHECK;
    $cache_id = $_SESSION['language'].'manufacturers';
    $main_content = $smarty->fetch(CURRENT_TEMPLATE.'/module/manufacturers.html', $cache_id);
}

$smarty->assign('language', $_SESSION['language']);
$smarty->assign('main_content', $main_content);
$smarty->caching = 0;
if (!defined('RM')) {
    $smarty->load_filter('output', 'note');
}
$smarty->display(CURRENT_TEMPLATE.'/index.html');
include ('includes/application_bottom.php');
    