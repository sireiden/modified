<?php
/* -----------------------------------------------------------------------------------------
 * Zusätzliche Produkt-Felder im Datenblatt
 * Michael Förster - brainsquad
 * 2019-05-12
 */

$products_fields_query = xtc_db_query("SELECT * FROM ".TABLE_PRODUCTS_FIELDS." WHERE products_id = '".$product->data['products_id']."' ");
$products_fields = xtc_db_fetch_array($products_fields_query);

// Energieeffizenzklasse
if($products_fields['products_efficiency_class']) {
    if(stripos($products_fields['products_efficiency_class'], 'DAH') !== false) {
        $products_fields['products_efficiency_spectrum'] = '(Spektrum A bis G)';
    }
    else {
        $products_fields['products_efficiency_spectrum'] = '(Spektrum A+++ bis D)';
    }
}
// Tiefpreisgarantie
if($products_fields['products_best_price']) {
    $products_fields['products_best_price_link'] = '<a rel="nofollow" target="_blank" href="'.xtc_href_link(FILENAME_POPUP_CONTENT, 'coID=106', $request_type).'" title="Information" class="'.TPL_POPUP_CONTENT_LINK_CLASS.'" style="text-decoration:underline;font-weight:bold;font-size:12px;" >Tiefpreis-Garantie</a>';
    $products_fields['products_best_price_text'] = 'Finden Sie einen Artikel der dieses Siegel tr&auml;gt, auf einer Internetseite oder Ladengesch&auml;ft mit Sitz in Deutschland zu einem g&uuml;nstigeren Preis als bei uns, ziehen wir mit dem Preis der Konkurrenz gleich: Wir bieten Ihnen garantiert immer den tiefsten Preis und erstatten den Differenzbetrag zum Preis der Konkurrenz.
  <a rel="nofollow" target="_blank" href="'.xtc_href_link(FILENAME_POPUP_CONTENT, 'coID=106', $request_type).'" title="Information" class="'.TPL_POPUP_CONTENT_LINK_CLASS.'" style="text-decoration:none;font-weight:bold;font-size:11px;" >Bedingungen Tiefpreisgarantie</a>';
}
// Sonderpreisanfrage
if($products_fields['products_specialrequest']) {
    $info_smarty->assign('ADD_SPECIAL_REQUEST_BUTTON', '<a rel="nofollow" target="_blank" href="'.xtc_href_link('popup_sonderpreis.php', 'products_id='.$product->data['products_id'], $request_type).'" title="Sonderpreis anfragen" class="'.TPL_POPUP_CONTENT_LINK_CLASS.'" style="text-decoration:none;font-weight:bold;font-size:11px;" >Sonderpreis Anfragen</a>');
}
// Skonto
if($products_fields['products_cash_discount'] > 0) { //  && MODULE_ORDER_TOTAL_CASH_DISCOUNT_STATUS == 'true'
    $cash_discount_sum = ($product->data['products_price'] / 100 * $products_fields['products_cash_discount']);
    $discount_tax = xtc_get_tax_rate(MODULE_ORDER_TOTAL_CASH_DISCOUNT_TAX_CLASS, 81, 0);
    $cash_discount_value= xtc_add_tax($cash_discount_sum, $discount_tax);
    $cash_discount = $xtPrice->xtcFormat($cash_discount_value,true);
    $products_fields['products_cash_discount_sum'] = $cash_discount;
}
else {
    $products_fields['products_cash_discount_sum'] = false;
}
// Bedingungen zur Herstellergarantie
if($products_fields['products_warranty_conditions'] == 1) {
    $warranty_info = '<a rel="nofollow" target="_blank" href="'.xtc_href_link(FILENAME_POPUP_CONTENT, 'coID=97', $request_type).'" title="Bedingungen 5 Jahres Garantie" class="'.TPL_POPUP_CONTENT_LINK_CLASS.'" ><img src="/images/btn_garantie.jpg" style="width:100%" alt="Bedingungen 5 Jahres Garantie" /></a>';
    $products_fields['products_warranty_conditions'] = $warranty_info;
}
else {
    $products_fields['products_warranty_conditions'] = false;
} 


// Zusätzlicher Tabs der Herstellerseiten und für die Anzeige von SMEG
$manufacturers_pages = array();
if(!empty($products_fields['products_manufacturers_pages'])) {
    $page_ids = str_replace('|', ',', $products_fields['products_manufacturers_pages']);
    $page_ids = substr($page_ids, 0, -1);
    
    $manufacturers_pages_query = xtc_db_query("select * FROM " . TABLE_MANUFACTURERS_PAGES . " WHERE manufacturers_id = '".$product->data['manufacturers_id']."' AND page_id IN (".$page_ids.") ORDER BY sort_order");
    while($manufacturers_page = xtc_db_fetch_array($manufacturers_pages_query)) {
        if(!empty($manufacturers_page['page_title']) && !empty($manufacturers_page['page_text'])) {
            $manufacturers_page['page_text'] = remove_word_code($manufacturers_page['page_text']);
            $manufacturers_page['url'] = xtc_href_link(seo_url_href_mask($manu['manufacturers_name']) . '/' .  seo_url_href_mask($manufacturers_page['page_title']).MAN_OVERVIEW_DIVIDER.$manu['manufacturers_id'].PAG_DIVIDER.$manufacturers_page['page_id'].'.html');
            $manufacturers_pages[] = $manufacturers_page;
        }
    }
}
$info_smarty->assign('MANUFACTURERS_PAGES', $manufacturers_pages);
 
 // Anzeige der Versandkosten dieses Produkts
 for ($i=1; $i<=26; $i++) {
     $countries_table = constant('MODULE_SHIPPING_ZONESE_COUNTRIES_' . $i);
     $countries_table  = preg_replace("'[\r\n\s]+'",'',$countries_table);
     $country_zones = explode(",", $countries_table);
     
     if (in_array('DE', $country_zones)) {
         $dest_zone = $i;
         break;
     }
 }
 $zonese_cost = constant('MODULE_SHIPPING_ZONESE_COST_' . $dest_zone);
 $zonese_table = preg_split("/[:,]/" , $zonese_cost);
 $size = sizeof($zonese_table);
 for ($i=0; $i<$size; $i+=2) {
     if ($product->data['products_weight'] <= $zonese_table[$i]) {
         $shipping_cost = $zonese_table[$i+1];
         break;
     }
 }

 if (MODULE_SHIPPING_ZONESE_TAX_CLASS > 0) {
     $shipping_tax = xtc_get_tax_rate(MODULE_SHIPPING_ZONESE_TAX_CLASS, 81, 0);
 }
 $shipping_fee = $xtPrice->xtcFormat(xtc_add_tax($shipping_cost, $shipping_tax), true, 0, true);
 

 
 if (!defined('POPUP_SHIPPING_LINK_PARAMETERS')) {
     define('POPUP_SHIPPING_LINK_PARAMETERS', '&KeepThis=true&TB_iframe=true&height=400&width=600');
 }
 if (!defined('POPUP_SHIPPING_LINK_CLASS')) {
     define('POPUP_SHIPPING_LINK_CLASS', 'thickbox');
 }
 $link_parameters = defined('TPL_POPUP_SHIPPING_LINK_PARAMETERS') ? TPL_POPUP_SHIPPING_LINK_PARAMETERS : POPUP_SHIPPING_LINK_PARAMETERS;
 $link_class = defined('TPL_POPUP_SHIPPING_LINK_CLASS') ? TPL_POPUP_SHIPPING_LINK_CLASS : POPUP_SHIPPING_LINK_CLASS;
 
 $shipping_fee_text = ' '.SHIPPING_EXCL.' <a rel="nofollow" target="_blank" href="'.xtc_href_link(FILENAME_POPUP_CONTENT, 'coID='.SHIPPING_INFOS.$link_parameters, $request_type).'" title="Information" class="'.$link_class.'" style="text-decoration:underline;">'.$shipping_fee.' '.SHIPPING_COSTS.'</a>';
 $shipping_fee_table = '<a rel="nofollow" target="_blank" href="'.xtc_href_link(FILENAME_POPUP_CONTENT, 'coID='.SHIPPING_INFOS.$link_parameters, $request_type).'" title="Information" class="'.$link_class.'" style="text-decoration:underline;">siehe Tabelle</a>';
 $products_fields['shipping_fee_text'] = $shipping_fee_text;
 $products_fields['shipping_fee_table'] = $shipping_fee_table;
 
 // Anzeige der Lieferdauer
 $shipping_duration_table = '(Lieferzeit Ausland und Berechnung <a rel="nofollow" target="_blank" href="'.xtc_href_link(FILENAME_POPUP_CONTENT, 'coID=108'.$link_parameters, $request_type).'" title="Information" class="'.$link_class.'" style="text-decoration:underline;">siehe Tabelle</a>)';
 $products_fields['shipping_duration_table'] = $shipping_duration_table;
 
$info_smarty->assign('PRODUCTS_FIELDS', $products_fields);

// Anzeige des Smeg bzw. Gaggenau - Bereichs
if($product->data['manufacturers_id'] == 66 || $product->data['manufacturers_id'] == 57) {
    $brandshop_pages = array();
    if(!empty($products_fields['products_manufacturers_pages'])) {
        $brandshop_pages_query = xtc_db_query("select mp.*, m.manufacturers_name FROM " . TABLE_MANUFACTURERS_PAGES . " mp JOIN ".TABLE_MANUFACTURERS." m ON (m.manufacturers_id = mp.manufacturers_id) WHERE mp.manufacturers_id = '".$product->data['manufacturers_id']."' ORDER BY mp.sort_order");
        while($brandshop_page = xtc_db_fetch_array($brandshop_pages_query)) {
            if(!empty($brandshop_page['page_title']) && !empty($brandshop_page['page_text'])) {           
                $brandshop_page['url'] = xtc_href_link(seo_url_href_mask($brandshop_page['manufacturers_name']) . '/' .  seo_url_href_mask($brandshop_page['page_title']).MAN_OVERVIEW_DIVIDER.$brandshop_page['manufacturers_id'].PAG_DIVIDER.$manufacturers_page['page_id'].'.html');
                if($product->data['manufacturers_id'] == 57) {
                //    $info_smarty->assign('MANUFACTURER_IMAGE', DIR_WS_IMAGES.'/gaggenau_partner.png');
                }
                
                $brandshop_pages[] = $brandshop_page;
            }
        }
    }
    $info_smarty->assign('BRANDSHOP_PAGES', $brandshop_pages);
}
else {
    $info_smarty->assign('PRODUCTS_IS_SMEG', false);
}

// Finanzierungsmodul
if(defined('COMMERZFINANZ_STATUS') && COMMERZFINANZ_STATUS == 'true') {
    if($products_fields['products_cash_discount'] > 0) {
        require_once(DIR_FS_INC.'inc.commerz_finanz.php');
        $commerz_finanz = commerz_finanz($product->data['products_id'], $product->data['products_tax_class_id'], false);
        $info_smarty->assign('COMMERZFINANZ_PRICE', $commerz_finanz['price']);
        $info_smarty->assign('COMMERZFINANZ_MODUL', $commerz_finanz['table']);
    }
}



