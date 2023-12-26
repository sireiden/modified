<?php
/* --------------------------------------------------------------
 * Hersteller Slider
 *
 */
// include smarty
include(DIR_FS_BOXES_INC . 'smarty_default.php');

// set cache id
$cache_id = md5($_SESSION['language']);

if (!$box_smarty->is_cached(CURRENT_TEMPLATE.'/boxes/box_manufacturers_slider.html', $cache_id) || !$cache) {
    $manufacturers_query = "select * from " . TABLE_MANUFACTURERS . " WHERE manufacturers_status = 1 and manufacturers_slider_status = 1 order by manufacturers_name";
    $manufacturers_query = xtc_db_query($manufacturers_query);
    while($manufacturer = xtc_db_fetch_array($manufacturers_query, true)) {
        if($manufacturer['manufacturers_image'] == '') {
            continue;
        }
        $manufacturer['image'] = DIR_WS_IMAGES.$manufacturer['manufacturers_image'];
        $manufacturer['link'] = xtc_href_link(FILENAME_DEFAULT, xtc_manufacturer_link($manufacturer['manufacturers_id'],$manufacturer['manufacturers_name']));
        $manufacturers[] = $manufacturer;
    }
    $box_smarty->assign('manufacturers', $manufacturers);

}

if (!$cache) {
    $box_manufacturers_slider = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_manufacturers_slider.html');
} else {
    $box_manufacturers_slider = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_manufacturers_slider.html', $cache_id);
}

$smarty->assign('box_MANUFACTURERS_SLIDER',$box_manufacturers_slider);
?>