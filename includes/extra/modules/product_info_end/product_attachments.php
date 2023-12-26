<?php
/* -----------------------------------------------------------------------------------------
 * Zubehörartikel im im Datenblatt
 * Michael Förster - brainsquad
 * 2019-04-08
 */
// include needed functions
require_once (DIR_FS_INC.'xtc_row_number_format.inc.php');

$products_attachments_query = xtc_db_query("SELECT  p.products_id,
                                                    p.products_image,
                                                    p.products_price,
                                                    p.products_vpe,
                                                    p.products_vpe_status,
                                                    p.products_vpe_value,
                                                    p.products_tax_class_id,
                                                    pd.products_name
                                            FROM ".TABLE_PRODUCTS." p
                                            JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd
                                                ON p.products_id = pd.products_id
                                                AND pd.language_id = '".(int)$_SESSION['languages_id']."'
                                                AND trim(pd.products_name) != ''
                                            JOIN ".TABLE_PRODUCTS_TO_ATTACHMENTS." p2a
                                                ON p.products_id = p2a.attachment_id
                                            WHERE p.products_status = 1
                                                AND p2a.products_id = '".$product->data['products_id']."'
                                            ORDER BY p2a.sort ASC");
$products_attachments_count = xtc_db_num_rows($products_attachments_query, true);
if ($products_attachments_count > 0) {
    $rows = 0;
    $products_attachments = array();
    while ($product_attachment = xtc_db_fetch_array($products_attachments_query, true)) {
        $rows ++;
        $product_attachment = array_merge($product_attachment, array('ID' => xtc_row_number_format($rows)));
        $products_attachments[] = $product->buildDataArray($product_attachment);
    }
    $info_smarty->assign('PRODUCTS_ATTACHMENTS', $products_attachments);
}
