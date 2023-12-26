<?php
/************************************************************************************
 *** this file contains code based on:											  ***
 *** (c) 2000 - 2001 The Exchange Project										  ***
 *** (c) 2001 - 2003 osCommerce, Open Source E-Commerce Solutions				  ***
 *** (c) 2003	 nextcommerce (account_history_info.php,v 1.17 2003/08/17); 	  ***
 *** (c) 2003 - 2006 XT-Commerce													  ***
 *** Released under the GNU General Public License								  ***
 ************************************************************************************/


class ot_cash_discount {
    var $title, $output;
    
    function __construct() {
        global $xtPrice;
        $this->code = 'ot_cash_discount';
        $this->title = MODULE_ORDER_TOTAL_CASH_DISCOUNT_TITLE;
        $this->description = MODULE_ORDER_TOTAL_CASH_DISCOUNT_DESCRIPTION;
        $this->enabled = ((MODULE_ORDER_TOTAL_CASH_DISCOUNT_STATUS == 'true') ? true : false);
        $this->sort_order = MODULE_ORDER_TOTAL_CASH_DISCOUNT_SORT_ORDER;
        
        
        $this->output = array();
    }
    
    function process()
    {
        global $order, $xtPrice, $discount_cost, $discount_country, $shipping;
        
        if (MODULE_ORDER_TOTAL_CASH_DISCOUNT_STATUS == 'true')
        {
            //Will become true, if cod can be processed.
            $cash_discount = 0;
            $discount_country = true;
            //check if payment method is eustandardtransfer
            if ($_SESSION['payment'] == 'eustandardtransfer')
            {
                foreach($order->products as $product)
                {
                    $cash_discount = $cash_discount + ($product['final_price'] / 100 * $product['cash_discount']);
                }
                
                $cash_discount = $cash_discount * -1 ;
                $discount_tax = xtc_get_tax_rate(MODULE_ORDER_TOTAL_CASH_DISCOUNT_TAX_CLASS, $order->delivery['country']['id'], $order->delivery['zone_id']);
                // $cash_discount = $cash_discount /  (100 + $discount_tax) * 100;
                $discount_tax_description = xtc_get_tax_description(MODULE_ORDER_TOTAL_CASH_DISCOUNT_TAX_CLASS, $order->delivery['country']['id'], $order->delivery['zone_id']);
                if ($_SESSION['customers_status']['customers_status_show_price_tax'] == 1) {
                    $cash_discount = $cash_discount /  (100 + $discount_tax) * 100;
                    $order->info['tax'] += xtc_add_tax( $cash_discount, $discount_tax)- $cash_discount;
                    $order->info['tax_groups'][TAX_ADD_TAX . "$discount_tax_description"] += xtc_add_tax($cash_discount, $discount_tax)-$cash_discount;
                    $order->info['total'] += $cash_discount + (xtc_add_tax($cash_discount, $discount_tax)-$cash_discount);
                    $cash_discount_value= xtc_add_tax($cash_discount, $discount_tax);
                    $cash_discount= $xtPrice->xtcFormat($cash_discount_value,true);
                }
                if ($_SESSION['customers_status']['customers_status_show_price_tax'] == 0 && $_SESSION['customers_status']['customers_status_add_tax_ot'] == 1) {
                    $cash_discount = $cash_discount /  (100 + $discount_tax) * 100;
                    $order->info['tax'] += xtc_add_tax($cash_discount, $discount_tax)-$cash_discount;
                    $order->info['tax_groups'][TAX_NO_TAX . "$discount_tax_description"] += xtc_add_tax($cash_discount, $discount_tax)-$cash_discount;
                    $cash_discount_value=$cash_discount;
                    $cash_discount= $xtPrice->xtcFormat($cash_discount,true);
                    $order->info['subtotal'] += $cash_discount_value;
                    $order->info['total'] += $cash_discount_value;
                }
                if (!$cash_discount_value) {
                    $cash_discount_value=$cash_discount;
                    $cash_discount= $xtPrice->xtcFormat($cash_discount,true);
                    $order->info['total'] += $cash_discount_value;
                }
                
                if(abs($cash_discount_value) > 0) {
                    $this->output[] = array('title' => $this->title . ':',
                        'text' => $cash_discount,
                        'value' => $cash_discount_value);
                }
            }
        }
    }
    
    
    function check() {
        if (!isset($this->_check)) {
            $check_query = xtc_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_CASH_DISCOUNT_STATUS'");
            $this->_check = xtc_db_num_rows($check_query);
        }
        return $this->_check;
    }
    
    function keys() {
        return array('MODULE_ORDER_TOTAL_CASH_DISCOUNT_STATUS', 'MODULE_ORDER_TOTAL_CASH_DISCOUNT_SORT_ORDER', 'MODULE_ORDER_TOTAL_CASH_DISCOUNT_TAX_CLASS');
    }
    
    function install() {
        xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_ORDER_TOTAL_CASH_DISCOUNT_STATUS', 'true', '6', '0', 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
        
        xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_ORDER_TOTAL_CASH_DISCOUNT_SORT_ORDER', '36', '6', '0', now())");
        
        xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) values ('MODULE_ORDER_TOTAL_CASH_DISCOUNT_TAX_CLASS', '0', '6', '0', 'xtc_get_tax_class_title', 'xtc_cfg_pull_down_tax_classes(', now())");
    }
    
    function remove() {
        xtc_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
}
?>