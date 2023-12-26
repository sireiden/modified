<?php
/* --------------------------------------------------------------
 * Verwaltung von Aktionen
 * Aktionen sind individuelle Seiten und bilden den Slider auf der Startseite
 *
 */
// include smarty
include(DIR_FS_BOXES_INC . 'smarty_default.php');

// set cache id
$cache_id = md5($_SESSION['language']);

if (!$box_smarty->is_cached(CURRENT_TEMPLATE.'/boxes/box_main_promotions.html', $cache_id) || !$cache) {
    $promotions_query = "select p.*, m.manufacturers_name from ".TABLE_MAIN_PROMOTIONS." p JOIN " . TABLE_MANUFACTURERS . " m ON p.manufacturers_id = m.manufacturers_id  WHERE p.status = 1 and p.image != '' order by p.sort_order ASC";
    $promotions_query = xtc_db_query($promotions_query);
    while($promotion = xtc_db_fetch_array($promotions_query, true)) {
        $promotion['link'] = xtc_href_link('Aktionen/' . seo_url_href_mask($promotion['manufacturers_name']) . '/' .  seo_url_href_mask($promotion['name']).'-:-'.$promotion['id'].'.html');
        $promotion['image'] = DIR_WS_IMAGES . $promotion['image'];
        $promotions[] = $promotion;
    }
    $box_smarty->assign('promotions', $promotions);
}

if (!$cache) {
    $box_main_promotions = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_main_promotions.html');
} else {
    $box_main_promotions = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_main_promotions.html', $cache_id);
}

$smarty->assign('box_MAIN_PROMOTIONS',$box_main_promotions);
?>