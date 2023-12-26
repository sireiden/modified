<?php
/* -------------------------------------------------------------------------------------
 * 	ID:						$Id: commerz_finanz.php 2 2011-06-29 12:08:34Z siekiera $
 * 	Letzter Stand:			$Revision: 3 $
 * 	zuletzt ge+ñndert von:	$Author: siekiera $
 * 	Datum:					$Date: 2011-06-06 14:08:34 +0200 (Mon, 06 Jun 2011) $
 *
 * 	SEO:mercari by Siekiera Media
 * 	http://www.seo-mercari.de
 *
 * 	Copyright (c) since 2011 SEO:mercari
 * --------------------------------------------------------------------------------------
 * 	based on:
 * 	(c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
 * 	(c) 2002-2003 osCommerce - www.oscommerce.com
 * 	(c) 2003     nextcommerce - www.nextcommerce.org
 * 	(c) 2005     xt:Commerce - www.xt-commerce.com
 *
 * 	Released under the GNU General Public License
 * ----------------------------------------------------------------------------------- */

class commerzfinanz {
    var $code, $title, $description, $enabled;
    
    function __construct() {
        global $order;
        
        $this->code = 'commerzfinanz';
        $this->title = MODULE_PAYMENT_COMMERZFINANZ_TEXT_TITLE;
        $this->description = MODULE_PAYMENT_COMMERZFINANZ_TEXT_DESCRIPTION;
        $this->sort_order = MODULE_PAYMENT_COMMERZFINANZ_SORT_ORDER;
        $this->enabled = ((MODULE_PAYMENT_COMMERZFINANZ_STATUS == 'True') ? true : false);
        $this->info = MODULE_PAYMENT_COMMERZFINANZ_TEXT_INFO;
        $this->tmpOrders = false;
        
        if (strstr ($_SERVER['PHP_SELF'], 'checkout')) {
            if($_SESSION['cart']->show_total() < COMMERZFINANZ_MINIMUM_PRICE_TITLE || $_SESSION['cart']->show_total() > COMMERZFINANZ_MAXIMUM_PRICE_TITLE )
                $this->enabled = false;
        }
        
        if ((int)MODULE_PAYMENT_COMMERZFINANZ_ORDER_STATUS_ID > 0) {
            $this->order_status = MODULE_PAYMENT_COMMERZFINANZ_ORDER_STATUS_ID;
        }
        if (is_object($order)) $this->update_status();
        
    }
    
    function update_status() {
        global $order;
        
        if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_COMMERZFINANZ_ZONE > 0) ) {
            $check_flag = false;
            $check_query = xtc_db_query("select zone_id from ".TABLE_ZONES_TO_GEO_ZONES." where geo_zone_id = '".MODULE_PAYMENT_COMMERZFINANZ_ZONE."' and zone_country_id = '".$order->billing['country']['id']."' order by zone_id");
            while ($check = xtc_db_fetch_array($check_query)) {
                if ($check['zone_id'] < 1) {
                    $check_flag = true;
                    break;
                } elseif ($check['zone_id'] == $order->billing['zone_id']) {
                    $check_flag = true;
                    break;
                }
            }
            
            if ($check_flag == false) {
                $this->enabled = false;
            }
        }
    }
    
    function javascript_validation() {
        return false;
    }
    
    function selection() {
        global $order;
        unset($_SESSION['financing_started']);
        if(isset($_GET['commerz_declied']) && $_GET['commerz_declied'] == 1) {
            $_SESSION['no_commerz_finanz'] = 1;
        }
        
        // Pr³fung, ob einer der erlaubten Hersteller gewõhlt ist
        $allowed = false;
        $commerz_manufacturer = explode(',', COMMERZFINANZ_MANUFACTURERS);
        
        foreach($order->products as $product) {
            //	if ((empty($commerz_manufacturer) || in_array($product['manufacturers_id'], $commerz_manufacturer)) && $product['cash_discount'] > 0)  {
            if($product['cash_discount'] > 0) {
                $allowed = true;
                break;
            }
        }
        
        if($allowed !== true) {
            return false;
        }
          
        if($_SESSION['no_commerz_finanz'] == 1) {
            return false;
        }
        else {
            return array ('id' => $this->code, 'module' => $this->title, 'description' => $this->info, 'image' => HTTP_SERVER.DIR_WS_CATALOG.DIR_WS_IMAGES.'paymenttypes/finanzierung.png');
        }
        
        
    }
    
    function pre_confirmation_check() {
        return false;
    }
    
    function confirmation() {
        return false;
    }
    
    function process_button() {
        return false;
        
    }
    
    function before_process() {
        global $insert_id,$xtPrice,$order,$order_totals;
        /*
         if (!class_exists('order_total')) {
         require(DIR_WS_CLASSES.'order_total.php');
         $order_total_modules = new order_total();
         $order_totals = $order_total_modules->process();
         }
         
         for($i = 0, $n = sizeof($order_totals); $i < $n; $i ++) {
         switch($order_totals[$i]['code']) {
         case 'ot_total':
         $paymentAmount=$order_totals[$i]['value'];
         break;
         }
         }
         
         $_SESSION['hpLastData']['commerz_amount'] = $paymentAmount;
         */
        /*  	if($_SESSION['financing_started'] == 1) {
         return false;
         }
         else {
         global $order;
         $_SESSION['financing_started'] = 1;
         require_once(DIR_FS_INC.'inc.commerz_finanz.php');
         build_commerz_url();
         xtc_redirect(xtc_href_link('checkout_commerzfinanz.php', '', 'SSL'));
         } */
        return false;
    }
    
    function after_process() {
        global $insert_id;
        if($_SESSION['financing_started'] == 1) {
            return false;
        }
        else {
            global $order;
            $_SESSION['financing_started'] = 1;
            require_once(DIR_FS_INC.'inc.commerz_finanz.php');
            build_commerz_url();
            xtc_redirect(xtc_href_link('checkout_commerzfinanz.php', '', 'SSL'));
        }
    }
    
    function output_error() {
        return false;
    }
    
    function check() {
        if (!isset($this->_check)) {
            $check_query = xtc_db_query("select configuration_value from ".TABLE_CONFIGURATION." where configuration_key = 'MODULE_PAYMENT_COMMERZFINANZ_STATUS'");
            $this->_check = xtc_db_num_rows($check_query);
        }
        return $this->_check;
    }
    
    function install() {
        xtc_db_query("insert into ".TABLE_CONFIGURATION." ( configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_PAYMENT_COMMERZFINANZ_STATUS', 'True', '6', '3', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
        xtc_db_query("insert into ".TABLE_CONFIGURATION." ( configuration_key, configuration_value,  configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_COMMERZFINANZ_ALLOWED', '', '6', '0', now())");
        xtc_db_query("insert into ".TABLE_CONFIGURATION." ( configuration_key, configuration_value,  configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_COMMERZFINANZ_SORT_ORDER', '0', '6', '0', now())");
        xtc_db_query("insert into ".TABLE_CONFIGURATION." ( configuration_key, configuration_value,  configuration_group_id, sort_order, use_function, set_function, date_added) values ('MODULE_PAYMENT_COMMERZFINANZ_ZONE', '0', '6', '2', 'xtc_get_zone_class_title', 'xtc_cfg_pull_down_zone_classes(', now())");
        xtc_db_query("insert into ".TABLE_CONFIGURATION." ( configuration_key, configuration_value,  configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_COMMERZFINANZ_NUMBER', '',  '6', '4', now())");
        xtc_db_query("insert into ".TABLE_CONFIGURATION." ( configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, use_function, date_added) values ('MODULE_PAYMENT_COMMERZFINANZ_ORDER_STATUS_ID', '0',  '6', '0', 'xtc_cfg_pull_down_order_statuses(', 'xtc_get_order_status_name', now())");
    }
    
    function remove() {
        xtc_db_query("delete from ".TABLE_CONFIGURATION." where configuration_key in ('".implode("', '", $this->keys())."')");
    }
    
    function keys() {
        return array('MODULE_PAYMENT_COMMERZFINANZ_STATUS','MODULE_PAYMENT_COMMERZFINANZ_ALLOWED','MODULE_PAYMENT_COMMERZFINANZ_ZONE', 'MODULE_PAYMENT_COMMERZFINANZ_ORDER_STATUS_ID', 'MODULE_PAYMENT_COMMERZFINANZ_SORT_ORDER','MODULE_PAYMENT_COMMERZFINANZ_NUMBER');
    }
}
?>