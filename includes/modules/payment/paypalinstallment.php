<?php
/* -----------------------------------------------------------------------------------------
   $Id: paypalinstallment.php 12109 2019-09-20 07:06:59Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/


// include needed classes
require_once(DIR_FS_EXTERNAL.'paypal/classes/PayPalPayment.php');


class paypalinstallment extends PayPalPayment {
	var $code, $title, $description, $extended_description, $enabled;


	function __construct() {
		global $order;
    
    PayPalPayment::__construct('paypalinstallment');

		$this->tmpOrders = false;
	}


  function update_status() {
    global $order;
    
    if ($this->enabled === true
        && (!defined('MODULE_ORDER_TOTAL_PAYPALINSTALLMENT_FEE_STATUS')
            || MODULE_ORDER_TOTAL_PAYPALINSTALLMENT_FEE_STATUS == 'false'
            )
        )
    {
      $this->enabled = false;
    }
    
    if ($this->enabled === true
        && $order->billing['country']['iso_code_2'] != 'DE'
        )
    {
      $this->enabled = false;
    }

    if ($this->enabled === true
        && ($_SESSION['customers_status']['customers_status_show_price_tax'] != '1'
            || $_SESSION['customers_status']['customers_status_add_tax_ot'] != '0'
            )
        )
    {
      $this->enabled = false;
    }
    
    if ($this->enabled === true
        && ($order->content_type == 'virtual' 
            || $order->content_type == 'virtual_weight'
            || $_SESSION['cart']->count_contents_virtual() == 0
            )
        )
    {
      $this->enabled = false;
    }
    
    if ($this->enabled === true) {
      $this->total_amount = $this->calculate_total();
      
      $this->presentment_array = $this->get_presentment($this->total_amount, $order->info['currency'], $order->billing['country']['iso_code_2']);
      if (count($this->presentment_array) < 1) {
        $this->enabled = false;
      }
    }
  }
  
  
  function selection() {
    global $order, $request_type;
    
    $presentment = '';
    if ($this->total_amount > 0) {
      $presentment .= $this->get_presentment_details($this->total_amount, $order->info['currency'], $order->billing['country']['iso_code_2'], 'payment');
      $presentment .= '<br/><br/><input type="checkbox" value="pp_conditions" name="pp_conditions" id="pp_conditions" />&nbsp;<strong><label for="pp_conditions">'.MODULE_PAYMENT_PAYPALINSTALLMENT_TEXT_CHECKBOX.'</label></strong>';
    }
    
    return array(
      'id' => $this->code, 
      'module' => $this->title, 
      'description' => $presentment,
    );
  }


  function javascript_validation() {
    $js = 'if (payment_value == "' . $this->code . '") {' . "\n" .
          '  if (!document.getElementById("checkout_payment").pp_conditions.checked) {' . "\n" .
          '    error_message = error_message + unescape("' . xtc_js_lang(MODULE_PAYMENT_PAYPALINSTALLMENT_TEXT_ERROR_CHECKBOX) . '");' . "\n" .
          '    error = 1;' . "\n" .
          '  }' . "\n" .
          '}' . "\n";
    
    return $js;
  }


  function confirmation() {
    return array ('title' => $this->description);
  }


	function pre_confirmation_check() {
		// confirmed
		if (isset($_GET['PayerID']) && $_GET['PayerID'] != '' 
		    && isset($_GET['token']) && $_GET['token'] != '' 
		    && isset($_GET['paymentId']) && $_GET['paymentId'] != '' 
		    && $_GET['paymentId'] == $_SESSION['paypal']['paymentId']		
		    ) 
		{
		  $this->validate_paypal_installment();
   		return;
		}
		
    if ((!isset($_POST['pp_conditions']) || $_POST['pp_conditions'] == false) && !isset($_GET['pp_conditions'])) {
      $error = str_replace('\n', '<br />', MODULE_PAYMENT_PAYPALINSTALLMENT_TEXT_ERROR_CHECKBOX);
      xtc_redirect(xtc_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode($error), 'SSL', true, false));
    }

    // load the selected shipping module
    require_once (DIR_WS_CLASSES . 'shipping.php');
    $shipping_modules = new shipping($_SESSION['shipping']);

 		$this->payment_redirect();
	}


  function before_send_order() {
		$this->complete_payment_paypal_installment();
  }


	function after_process() {
		unset($_SESSION['paypal']);
	}

  
	function install() {
	  parent::install();

    $stati = array(
      'PAYPAL_INST_ORDER_STATUS_ACCEPTED_NAME' => 'PAYPAL_ORDER_STATUS_ACCEPTED_ID'
    );
	  $this->status_install($stati);
	  
    include_once(DIR_FS_LANGUAGES.$_SESSION['language'].'/modules/order_total/ot_paypalinstallment_fee.php');
	  require_once(DIR_FS_CATALOG.'includes/modules/order_total/ot_paypalinstallment_fee.php');
	  $pp_fee = new ot_paypalinstallment_fee();
	  if ($pp_fee->check() != 1) {
	    $pp_fee->install();
	  }
	}


	function keys() {
		return array('MODULE_PAYMENT_PAYPALINSTALLMENT_STATUS', 
		             'MODULE_PAYMENT_PAYPALINSTALLMENT_SORT_ORDER'
		             );
	}

}
?>