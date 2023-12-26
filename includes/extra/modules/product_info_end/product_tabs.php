<?php
/* -----------------------------------------------------------------------------------------
 * Zusätzliche Produkt-Tabs im Datenblatt
 * Michael Förster - brainsquad
 * 2019-04-08
 */

$products_tab_query = xtc_db_query("SELECT * FROM ".TABLE_PRODUCTS_TABS." WHERE products_id = '".$product->data['products_id']."' AND status = 1 ORDER BY sort_order ASC");
$product_tabs = array();
while($product_tab = xtc_db_fetch_array($products_tab_query)) {
    if(!empty($product_tab['tab_title']) && !empty($product_tab['tab_text'])) {
        $product_tabs[] = $product_tab;
        if($product_tab['tab_title'] == 'Produktdatenblatt') {
            $info_smarty->assign('PRODUCTS_DATASHEET', true);
        }
    }
}

$info_smarty->assign('PRODUCTS_TABS', $product_tabs);
