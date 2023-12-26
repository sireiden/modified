<?php

/* --------------------------------------------------------------
   Amazon Pay  V3.1.0
   function.amazonPayCheckoutSuccessMessage.php 2017-02-10

   alkim media
   http://www.alkim.de

   patworx multimedia GmbH
   http://www.patworx.de/

   Released under the GNU General Public License
   --------------------------------------------------------------
*/

function smarty_function_amazonPayCheckoutSuccessMessage($params, $smarty){
    include_once(DIR_FS_CATALOG.'AmazonLoginAndPay/.config.inc.php');
    $orders_query = xtc_db_query("SELECT orders_id, payment_class, a1.amz_tx_id AS tx_id_timeout, a2.amz_tx_id AS tx_id_success, a3.amz_tx_id AS tx_id_pending
                                    FROM 
                                        ".TABLE_ORDERS." o
                                        LEFT JOIN amz_transactions AS a1 ON (o.amazon_order_id = a1.amz_tx_order_reference AND a1.amz_tx_type = 'auth' AND a1.amz_tx_status = 'Declined')
                                        LEFT JOIN amz_transactions AS a2 ON (o.amazon_order_id = a2.amz_tx_order_reference AND a2.amz_tx_type = 'auth' AND (a2.amz_tx_status = 'Open' || a2.amz_tx_status = 'Closed'))
                                        LEFT JOIN amz_transactions AS a3 ON (o.amazon_order_id = a3.amz_tx_order_reference AND a3.amz_tx_type = 'auth' AND a3.amz_tx_status = 'Pending')
                                    WHERE 
                                        customers_id = ".(int)$_SESSION['customer_id']."
                                         
                                    ORDER BY
                                        orders_id DESC 
                                    LIMIT 1");
                                   
    if($order = xtc_db_fetch_array($orders_query)){
        if($order["payment_class"] == 'am_apa'
            &&
           !empty($order["tx_id_timeout"])
            &&
           !empty($order["tx_id_pending"])
            &&
           empty($order["tx_id_success"])
          ){
            return AMZ_CHECKOUT_SUCCESS_PENDING_AUTHORIZATION;
        }
    }
}
