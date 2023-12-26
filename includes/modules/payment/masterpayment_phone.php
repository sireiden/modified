<?php
/****************************************************** 
 * Masterpayment Modul for modified eCommerce Shopsoftware 
 * Version 3.5.1
 * Copyright (c) 2010-2012 by K-30 | Florian Ressel 
 *
 * support@k-30.de | www.k-30.de
 * ----------------------------------------------------
 *
 * $Id: masterpayment_phone.php 11575 2019-03-21 09:12:10Z GTB $
 *	
 *	The Modul based on:
 *  XT-Commerce - community made shopping
 *  http://www.xt-commerce.com
 *
 *  Copyright (c) 2003 XT-Commerce
 *
 *	Released under the GNU General License
 *
 ******************************************************/

require_once(DIR_FS_CATALOG . 'includes/external/masterpayment/MasterpaymentActions.class.php');

  class masterpayment_phone extends MasterpaymentActions {
    var $code, $title, $description, $enabled;

    function __construct() {
      global $order;

      $this->code = 'masterpayment_phone';
      $this->title = 'Masterpayment ('.MODULE_PAYMENT_MASTERPAYMENT_PHONE_TEXT_TITLE.')';
      $this->title_checkout = MODULE_PAYMENT_MASTERPAYMENT_PHONE_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_MASTERPAYMENT_PHONE_TEXT_DESCRIPTION;
      $this->info = MODULE_PAYMENT_MASTERPAYMENT_PHONE_TEXT_INFO;
      $this->sort_order = ((defined('MODULE_PAYMENT_MASTERPAYMENT_PHONE_SORT_ORDER')) ? MODULE_PAYMENT_MASTERPAYMENT_PHONE_SORT_ORDER : '');
      $this->enabled = ((defined('MODULE_PAYMENT_MASTERPAYMENT_PHONE_STATUS') && MODULE_PAYMENT_MASTERPAYMENT_PHONE_STATUS == 'True') ? true : false);

      $this->tmpOrders = true;
      if ($this->check() > 0) {
        $this->order_status = MODULE_PAYMENT_MASTERPAYMENT_CONFIG_ORDER_STATUS_ID_TEMP;
        $this->tmpStatus = MODULE_PAYMENT_MASTERPAYMENT_CONFIG_ORDER_STATUS_ID_TEMP;
      }
      
      if (is_object($order)) $this->update_status();

      $this->form_action_url = $this->getActionURL();	  
    }

    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_MASTERPAYMENT_PHONE_ZONE > 0) ) {
        $check_flag = false;
        $check_query = xtc_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_MASTERPAYMENT_PHONE_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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
	  	if(MODULE_PAYMENT_MASTERPAYMENT_PHONE_ICON == 'True') {	  
	  		$content = array();
			$content = array_merge($content, array (
				array (
					'title' => ' ',
					'field' => xtc_image(DIR_WS_ICONS. 'masterpayment_phone.gif')
				)
			));			
		}	  

      	return array(
	  					'id' => $this->code,
                   		'module' => $this->title_checkout,
						'description' => $this->info,
						'fields' => $content
					);
    }

    function pre_confirmation_check() {
    	if (empty($_SESSION['cart']->cartID)) {
        	$_SESSION['cartID'] = $_SESSION['cart']->cartID = $_SESSION['cart']->generate_cart_id();
     	}		
	}

    function confirmation() {		
	   $this->deleteTempOrder();	
		
       return false;
    }

    function process_button() {     	
		return false;
    }
	
	function payment_action() {
		global $insert_id;
		
		$_SESSION['cart_Masterpayment_ID'] = $_SESSION['cartID'] . '-' . $insert_id;
		
    	xtc_redirect($this->form_action_url);
    	exit();   	
    }

    function before_process() {
		$this->checkoutBeforeProcess();		
    }

    function after_process() {		
		$this->checkoutAfterProcess();		
    }

    function get_error() {
       $error = array('title' => MODULE_PAYMENT_MASTERPAYMENT_PHONE_TEXT_ERROR_HEADING,
                     'error' => MODULE_PAYMENT_MASTERPAYMENT_PHONE_TEXT_ERROR_MESSAGE);

       return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = xtc_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_MASTERPAYMENT_PHONE_STATUS'");        
        $this->_check = xtc_db_num_rows($check_query);
      }
      return $this->_check;
    }	

    function install() {
      xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_PAYMENT_MASTERPAYMENT_PHONE_STATUS', 'False', '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now());");     
      xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_MASTERPAYMENT_PHONE_SORT_ORDER', '17', '6', '0', now())");
	  xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_MASTERPAYMENT_PHONE_ALLOWED', '', '6', '0', now())");
	  xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_PAYMENT_MASTERPAYMENT_PHONE_ICON', 'False', '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now());");
    }

    function remove() {
      xtc_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
	
    function keys() {
      return array('MODULE_PAYMENT_MASTERPAYMENT_PHONE_STATUS', 'MODULE_PAYMENT_MASTERPAYMENT_PHONE_ALLOWED', 'MODULE_PAYMENT_MASTERPAYMENT_PHONE_ICON', 'MODULE_PAYMENT_MASTERPAYMENT_PHONE_SORT_ORDER');
    }
    
  }
?>