<?php
/**************************************************************************
***************************************************************************
***	 Zusatzmodul für Erstellung Exportdokumente							***
***	 Copyright (c) Brainsquad, Florian Glötzl						    ***
***                                                                     ***
***************************************************************************
**************************************************************************/

class ot_export_fee {
    var $title, $output;
    
    function __construct() {
        global $xtPrice;
        $this->code = 'ot_export_fee';
        $this->title = MODULE_ORDER_TOTAL_EXPORT_FEE_TITLE;
        $this->description = MODULE_ORDER_TOTAL_EXPORT_FEE_DESCRIPTION;
        $this->enabled = ((MODULE_ORDER_TOTAL_EXPORT_FEE_STATUS == 'true') ? true : false);
        $this->sort_order = MODULE_ORDER_TOTAL_EXPORT_FEE_SORT_ORDER;

        $this->output = array();
    }
    
    function process() {
        global $order, $xtPrice, $export_cost, $export_country, $shipping;
        
        if (MODULE_ORDER_TOTAL_EXPORT_FEE_STATUS == 'true') {
            $allowed = constant('MODULE_ORDER_TOTAL_EXPORT_FEE_ALLOWED');
            if(!empty($allowed)) {
                $allowed_zones = explode(',', $allowed);
            }
            else {
                $allowed_zones = array();
            }
            
            if ((isset($_SESSION['delivery_zone']) && in_array($_SESSION['delivery_zone'], $allowed_zones) == true) || count($allowed_zones) == 0) {
                $export_costs_array = preg_split("/[:,]/", MODULE_ORDER_TOTAL_EXPORT_FEE_COST);
                $this->xtc_order_total();
                for ($i = 0; $i < count($export_costs_array); $i++) {
                    if($this->amount <= $export_costs_array[$i]) {
                        $export_cost = $export_costs_array[$i + 1];
                        break;
                    }
                    else {
                        $i++;
                    }
                }
                
                if($export_cost > 0) {
                    $export_tax = xtc_get_tax_rate(MODULE_ORDER_TOTAL_EXPORT_FEE_TAX_CLASS, $order->delivery['country']['id'], $order->delivery['zone_id']);
                    $export_tax_description = xtc_get_tax_description(MODULE_ORDER_TOTAL_EXPORT_FEE_TAX_CLASS, $order->delivery['country']['id'], $order->delivery['zone_id']);
                    if ($_SESSION['customers_status']['customers_status_show_price_tax'] == 1)
                    {
                        $order->info['tax'] += xtc_add_tax($export_cost, $export_tax)-$export_cost;
                        //			$order->info['tax_groups'][TAX_ADD_TAX . "$export_tax_description"] += xtc_add_tax($export_cost, $export_tax)-$export_cost;
                        $order->info['total'] += $export_cost + (xtc_add_tax($export_cost, $export_tax)-$export_cost);
                        $export_cost_value= xtc_add_tax($export_cost, $export_tax);
                        $export_cost= $xtPrice->xtcFormat($export_cost_value,true);
                    }
                    if ($_SESSION['customers_status']['customers_status_show_price_tax'] == 0 && $_SESSION['customers_status']['customers_status_add_tax_ot'] == 1)
                    {
                        $order->info['tax'] += xtc_add_tax($export_cost, $export_tax)-$export_cost;
                        $order->info['tax_groups'][TAX_NO_TAX . "$export_tax_description"] += xtc_add_tax($export_cost, $export_tax)-$export_cost;
                        $export_cost_value=$export_cost;
                        $export_cost= $xtPrice->xtcFormat($export_cost,true);
                        $order->info['subtotal'] += $export_cost_value;
                        $order->info['total'] += $export_cost_value;
                    }
                    if (!$export_cost_value)
                    {
                        $export_cost_value=$export_cost;
                        $export_cost= $xtPrice->xtcFormat($export_cost,true);
                        $order->info['total'] += $export_cost_value;
                    }
                    
                    $this->output[] = array('title' => $this->title . ':',
                        'text' => $export_cost,
                        'value' => $export_cost_value);
                }
            }
        }
    }
    
    function check() {
        if (!isset($this->_check)) {
            $check_query = xtc_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_EXPORT_FEE_STATUS'");
            $this->_check = xtc_db_num_rows($check_query);
        }
        return $this->_check;
    }
    
    function keys() {
        return array('MODULE_ORDER_TOTAL_EXPORT_FEE_STATUS', 'MODULE_ORDER_TOTAL_EXPORT_FEE_SORT_ORDER', 'MODULE_ORDER_TOTAL_EXPORT_FEE_COST', 'MODULE_ORDER_TOTAL_EXPORT_FEE_ALLOWED', 'MODULE_ORDER_TOTAL_EXPORT_FEE_TAX_CLASS');
    }
    
    function install() {
        xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_ORDER_TOTAL_EXPORT_FEE_STATUS', 'true', '6', '0', 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
        xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_ORDER_TOTAL_EXPORT_FEE_SORT_ORDER', '36', '6', '0', now())");
        xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_ORDER_TOTAL_EXPORT_FEE_COST', '1000:55,1000.01:70', '6', '0', now())");
        xtc_db_query("insert into " . TABLE_CONFIGURATION." ( configuration_key, configuration_value,  configuration_group_id, sort_order, date_added) values ('MODULE_ORDER_TOTAL_EXPORT_FEE_ALLOWED', '', '6', '0', now())");
        xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) values ('MODULE_ORDER_TOTAL_EXPORT_FEE_TAX_CLASS', '0', '6', '0', 'xtc_get_tax_class_title', 'xtc_cfg_pull_down_tax_classes(', now())");
    }
    
    function remove() {
        xtc_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
    
    function xtc_order_total()
    {
        global $order;
        $order_total = $order->info['total'];
        // Check if gift voucher is in cart and adjust total
        $products = $_SESSION['cart']->get_products();
        for ($i=0; $i<sizeof($products); $i++) {
            $t_prid = xtc_get_prid($products[$i]['id']);
            $gv_query = xtc_db_query("select products_price, products_tax_class_id, products_model from " . TABLE_PRODUCTS . " where products_id = '" . (int)($t_prid) . "'");
            $gv_result = xtc_db_fetch_array($gv_query);
            $qty = $_SESSION['cart']->get_quantity($products[$i]['id']);
            $products_tax = xtc_get_tax_rate($gv_result['products_tax_class_id']);
            if (preg_match('/^GIFT/', addslashes($gv_result['products_model']))) {
                if ($this->include_tax =='false') {
                    $gv_amount = $gv_result['products_price'] * $qty;
                } else {
                    $gv_amount = ($gv_result['products_price'] + xtc_calculate_tax($gv_result['products_price'],$products_tax)) * $qty;
                }
                $order_total -= $gv_amount;
            } else {
                $this->amounts[(string)$products_tax] += $gv_result['products_price'] * (int)$qty;
                $this->amounts['total'] += $gv_result['products_price'] * $qty;
            }
        }
        if ($this->include_shipping == 'false') $order_total -= $order->info['shipping_cost'];
        if ($this->include_tax == 'false') $order_total -= $order->info['tax'];
        $this->amount = $order_total;
    }
    
}
?>