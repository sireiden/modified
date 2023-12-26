<?php

/* -----------------------------------------------------------------------------------------
 * Hersteller - Detailseite
 * Michael Förster - brainsquad
 * 2019-03-21
 *
 */

require_once ('includes/application_top.php');

// create smarty elements
$smarty = new Smarty;

// include boxes
require (DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/boxes.php');

$manufacturers_query = "select * from " . TABLE_MANUFACTURERS . " WHERE manufacturers_id = '".(int)$_GET['manufacturers_info_id']."'";
$manufacturers_query_result = xtc_db_query($manufacturers_query);
$manufacturers = xtc_db_fetch_array($manufacturers_query_result);

$manufacturers_page_query = "select * FROM " . TABLE_MANUFACTURERS_PAGES . " WHERE manufacturers_id = '".(int)$_GET['manufacturers_id']."' and page_id = '".(int)$_GET['manufacturers_page']."'";
$manufacturers_page_result = xtc_db_query($manufacturers_page_query);
$manufacturers_page = xtc_db_fetch_array($manufacturers_page_result);

$manufacturer['content_text'] = $manufacturers_page['page_text'];
$manufacturer['content_title'] = remove_word_code($manufacturers_page['page_title']);

$breadcrumb->add('Herstellerübersicht', xtc_href_link('Hersteller.html'));
$breadcrumb->add($manufacturers['page_title'], $manufacturers_page['url'] = xtc_href_link(seo_url_href_mask($manufacturers['manufacturers_name']) . '/' .  seo_url_href_mask($manufacturers_page['page_title']).MAN_OVERVIEW_DIVIDER.$manufacturers['id'].PAG_DIVIDER.$manufacturers_page['page_id'].'.html'));


require (DIR_WS_INCLUDES.'header.php');

$smarty->assign('CONTENT_HEADING', $manufacturer['content_title']);
$smarty->assign('CONTENT_BODY', $manufacturer['content_text']);

$link = 'javascript:history.back(1)';
if (!isset($_SERVER['HTTP_REFERER'])
    || strpos($_SERVER['HTTP_REFERER'], HTTP_SERVER) === false
    )
{
    $link = xtc_href_link(FILENAME_DEFAULT, '', 'NONSSL');
}
$smarty->assign('BUTTON_CONTINUE', '<a href="'.$link.'">'.xtc_image_button('button_back.gif', IMAGE_BUTTON_BACK).'</a>');


// set cache ID
if (!CacheCheck()) {
    $smarty->caching = 0;
    $main_content = $smarty->fetch(CURRENT_TEMPLATE.'/module/manufacturers_page.html');
} else {
    $smarty->caching = 1;
    $smarty->cache_lifetime = CACHE_LIFETIME;
    $smarty->cache_modified_check = CACHE_CHECK;
    $cache_id = $_SESSION['language'].'manufacturers'.$_GET['manufacturers_id'].'_'.$_GET['manufacturers_page'];
    $main_content = $smarty->fetch(CURRENT_TEMPLATE.'/module/manufacturers_page', $cache_id);
}

$smarty->assign('language', $_SESSION['language']);
$smarty->assign('main_content', $main_content);
$smarty->caching = 0;
if (!defined('RM'))
    $smarty->load_filter('output', 'note');
    $smarty->display(CURRENT_TEMPLATE.'/index.html');
    include ('includes/application_bottom.php');
    ?>
