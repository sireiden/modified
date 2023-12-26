<?php

/* --------------------------------------------------------------
   Amazon Payments -Login And Pay- Modul  V3.00
   function.buyWithAmazonButton.php 2015-12-09

   alkim media
   http://www.alkim.de

   patworx multimedia GmbH
   http://www.patworx.de/

   Released under the GNU General Public License
   --------------------------------------------------------------
*/
function smarty_function_buyWithAmazonButton(){
    $unallowed_modules = explode(',', $_SESSION['customers_status']['customers_status_payment_unallowed']);
    global $actual_products_id;
    include_once(DIR_FS_CATALOG.'AmazonLoginAndPay/.config.inc.php');
    if(
            MODULE_PAYMENT_AM_APA_STATUS == 'True'
                &&
            AMZ_CHECKOUT_SINGLE_PRODUCTS == 'True'
                &&
            !(AMZ_DEBUG_MODE=='True' && $_SESSION["customers_status"]["customers_status_id"] != 0)
                &&
            !in_array('am_apa', $unallowed_modules)
                &&
            !AlkimAmazonHandler::hasCartExcludedProducts()
                &&
            !AlkimAmazonHandler::isProductExcluded($actual_products_id)
       ){

            return '
                    <div style="margin-top:20px;">
                        <a href="#" class="buyProductWithAmazonButton" style="display:none;">
                            <img src="" style="display:none;"/>
                        </a>
                    </div>
                    <div id="buyProductWithAmazonOriginalButtonWr" style="display:none;">
                        <div id="payWithAmazonDiv" class="amazon_checkout_button buyProductWithAmazon"></div>
                    </div>';
    }
}
