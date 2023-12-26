<?php
/*
 * --------------------------------------------------------------------------
 * @file      web0null_attribute_price_updater.php
 * @date      18.10.17
 *
 *
 * LICENSE:   Released under the GNU General Public License
 * --------------------------------------------------------------------------
 */
//BOF - web0null_attribute_price_updater
if (defined('MODULE_WEB0NULL_ATTRIBUTE_PRICE_UPDATER_STATUS') && MODULE_WEB0NULL_ATTRIBUTE_PRICE_UPDATER_STATUS == 'true') {
  $products_options_data[$row]['DATA'][$col]['JSON_ATTRDATA'] = str_replace(
    '"', '&quot;', json_encode(
      [
        'pid'          => (int)$product->data['products_id'],
        'gprice'       => $products_price,
        'oprice'       => $xtPrice->xtcFormat($xtPrice->xtcAddTax($xtPrice->getPprice((int)$product->data['products_id']), $xtPrice->TAX[$product->data['products_tax_class_id']]), false),
        'cleft'        => $xtPrice->currencies[$_SESSION['currency']]['symbol_left'],
        'cright'       => $xtPrice->currencies[$_SESSION['currency']]['symbol_right'],
        'prefix'       => $products_options['price_prefix'],
        'aprice'       => $xtPrice->xtcFormat($price, false),
        'vpetext'      => encode_htmlentities(($json_vpetext = xtc_get_vpe_name($product->data['products_vpe'])) ? $json_vpetext : TEXT_PRODUCTS_VPE),
        'vpevalue'     => (($product->data['products_vpe_status'] && (double)$product->data['products_vpe_value']) ? (double)$product->data['products_vpe_value'] : false),
        'attrvpevalue' => (($product->data['products_vpe_status'] && (double)$products_options['attributes_vpe_value']) ? (double)$products_options['attributes_vpe_value'] : false),
        'onlytext'     => $json_onlytext ? $json_onlytext : TXT_ONLY,
        'protext'      => $json_protext ? $json_protext : TXT_PER,
        'insteadtext'  => $json_insteadtext ? $json_insteadtext : TXT_INSTEAD,
      ]
    )
  );
}
//EOF - web0null_attribute_price_updater