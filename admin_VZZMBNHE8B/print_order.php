<?php
/* -----------------------------------------------------------------------------------------
   $Id: print_order.php 11999 2019-07-24 10:15:08Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2003 nextcommerce (print_order.php,v 1.1 2003/08/19); www.nextcommerce.org
   (c) 2006 XT-Commerce (print_order.php 1166 2005-08-21)

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  require('includes/application_top.php');

  $smarty = new Smarty;

  //get store name and store name_address
  $smarty->assign('store_name', STORE_NAME);
  $smarty->assign('store_name_address', STORE_NAME_ADDRESS); 

  // BOF - Prüfung, ob die Rechnung bereits gedruckt wurde und RG-Datum in der DB speichern
  $invoice_result = xtc_db_query("SELECT invoice_date FROM ".TABLE_ORDERS."	WHERE orders_id='".(int)$_GET['oID']."'");
  $invoice = xtc_db_fetch_array($invoice_result);
  $invoice_printed = ($invoice['invoice_date'] != '' && $invoice['invoice_date'] != 0) ? true : false;
  
  // Rechnungsdatum in die Datenbank speichern
  if($invoice_printed === false && $_GET['version'] != 'proforma_de' && $_GET['version'] != 'proforma_en') {
      $invoice_date = (int)time();
      $invoice_date_query=xtc_db_query("UPDATE ".TABLE_ORDERS." SET invoice_date = '".$invoice_date."' WHERE orders_id='".(int)$_GET['oID']."'");
  }
  // Falls die Rechnung erneut gedruckt werden soll
  if(isset($_GET['reprint']) && $_GET['reprint']=='yes') {
      $invoice_date = $invoice['invoice_date'];
      $invoice_printed = false;
  }
  
  if($_GET['version'] == 'proforma_de' || $_GET['version'] != 'proforma_en') {
      $invoice_date = (int)time();
  }
  
  // BOF - Prüfung, ob die Rechnung bereits gedruckt wurde und RG-Datum in der DB speichern
  
  // get order data
  include(DIR_WS_CLASSES . 'order.php');
  $order = new order((int)$_GET['oID']);

  $smarty->assign('address_label_customer',xtc_address_format($order->customer['format_id'], $order->customer, 1, '', '<br />'));
  $smarty->assign('address_label_shipping',xtc_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br />'));
  $smarty->assign('address_label_payment',xtc_address_format($order->billing['format_id'], $order->billing, 1, '', '<br />'));
  $smarty->assign('csID',$order->customer['csID']);
  $smarty->assign('vatID',$order->customer['vat_id']);

  // get products data
  include_once(DIR_FS_CATALOG.DIR_WS_CLASSES .'xtcPrice.php');
  $xtPrice = new xtcPrice($order->info['currency'], $order->info['status']);

  $order_total = $order->getTotalData($order->info['order_id']);
  $order_data = $order->getOrderData($order->info['order_id']);
  
  if($order->billing['country_iso_2'] == 'AT') {
      $smarty->assign('FOREIGN_TAX_NR', 'Steuernummer Österreich: 68 436/2023');
      $smarty->assign('FOREIGN_FA', 'Finanzamt Graz-Stadt');
  }
  else {
      $smarty->assign('FOREIGN_TAX_NR', '');
      $smarty->assign('FOREIGN_FA', '');
  } 

  $smarty->assign('order_data', $order_data);
  foreach($order_total['data'] as $key => &$order_parameter)  {
      if($order_parameter['CLASS'] == 'ot_subtotal') {
          $order_parameter['TEXT'] = format_price($order_parameter['VALUE'], 1, $order->info['currency'], $order->products[0]['allow_tax'], $order->products[0]['tax']);
          $order_parameter['TITLE'] = 'Zwischensumme netto:';
          if($_GET['version'] == 'proforma_en') {
              $order_parameter['TITLE'] = 'Subtotal net:';
          } 
      }
      if($order_parameter['CLASS'] == 'ot_discount') {
          $order_parameter['TEXT'] = format_price($order_parameter['VALUE'], 1, $order->info['currency'], $order->products[0]['allow_tax'], $order->products[0]['tax']);
          $order_parameter['TITLE'] = str_replace(':', ' ', $order_parameter['TITLE']);
          $order_parameter['TITLE'] .= ' netto:';
      }
      if($order_parameter['CLASS'] == 'ot_shipping') {
          $order_parameter['TEXT'] = format_price($order_parameter['VALUE'], 1, $order->info['currency'], $order->products[0]['allow_tax'], $order->products[0]['tax']);
          $order_parameter['TITLE'] = 'Versicherter Versand netto:';
          if($_GET['version'] == 'proforma_en') {
              $order_parameter['TITLE'] = 'Insured shipping net:';
          } 
      }
      if($order_parameter['CLASS'] == 'ot_cod_fee' || $order_parameter['CLASS'] == 'ot_cash_discount')  {
          if($order_parameter['VALUE'] == 0) {
              unset($order_parameter);
          }
          else {
              $order_parameter['TEXT'] = format_price($order_parameter['VALUE'], 1, $order->info['currency'], $order->products[0]['allow_tax'], $order->products[0]['tax']);
              $order_parameter['TITLE'] = str_replace(':', ' ', $order_parameter['TITLE']);
              $order_parameter['TITLE'] .= ' netto:';
          }
      }
      
      if($order_parameter['CLASS'] == 'ot_tax') {
          $middle_sum = $key;
          $key++;
          $tax_total = $order_parameter['VALUE'];
          $order_parameter['TITLE'] = 'zuzüglich '.round($order->products[0]['tax']).'% Umsatzsteuer:';
          if($_GET['version'] == 'proforma_en') {
              $order_parameter['TITLE'] = 'Plus '.round($order->products[0]['tax']).'% sales tex:';
          }
      }
      
      if($order_parameter['CLASS'] == 'ot_total') {
          $key++;
          $sum_total = $order_parameter['VALUE'];
          $order_parameter['TITLE'] = 'Rechnungsbetrag:';
          if($_GET['version'] == 'proforma_en') {
              $order_parameter['TITLE'] = 'Invoice amount:';
          } 
      }
      
      if($order_parameter['CLASS'] == 'ot_subtotal_no_tax') {
          continue;
      }
      
      $new_order_total[$key] = $order_parameter;
  }
  
  if($_GET['version'] == 'proforma_en') {
      $new_order_total_label = 'Total net:';
  }
  else {
      $new_order_total_label = 'Gesamtsumme netto:';
  }  
  $new_order_total[$middle_sum] = array('TEXT' => format_price(($sum_total - $tax_total), 1, $order->info['currency'], 0, 0), 'TITLE'=> $new_order_total_label, 'STYLE' => 'text-decoration: overline;');
  ksort($new_order_total);
  $smarty->assign('order_total', $new_order_total);
  $smarty->assign('TAX_RATE', round($order->products[0]['tax']));

  // assign language to template for caching
  $languages_query = xtc_db_query("select code, language_charset from " . TABLE_LANGUAGES . " WHERE directory ='". $order->info['language'] ."'");
  $langcode = xtc_db_fetch_array($languages_query);
  $smarty->assign('langcode', $langcode['code']);
  $smarty->assign('charset', $langcode['language_charset']);
  $smarty->assign('language', $order->info['language']);

  $smarty->assign('logo_path', DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/img/');
  $smarty->assign('tpl_path', DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/');

  $smarty->assign('oID',$order->info['order_id']);
  if ($order->info['payment_method'] != '' && $order->info['payment_method'] != 'no_payment') {
    require_once (DIR_FS_CATALOG.DIR_WS_CLASSES . 'payment.php');
    $payment_modules = new payment($order->info['payment_method']);
    $payment_method = $payment_modules::payment_title($order->info['payment_method'],$order->info['order_id']);

    // mod: BILLPAY payment module
    if(stripos($order->info['payment_method'], 'billpay') !== false) {
      require_once(DIR_FS_EXTERNAL . 'billpay/utils/billpay_display_bankdata.php');
      $payment_method .= display_billpay_bankdata();
    }

    if(strpos($order->info['payment_method'], 'paypalplus') !== false) {
      require_once(DIR_FS_EXTERNAL.'paypal/classes/PayPalInfo.php');
      $paypal = new PayPalInfo($order->info['payment_method']);   
      
      $pp_payment_information = $paypal->get_payment_instructions($order->info['order_id']);
      if(stripos($pp_payment_information[0]['title'], '&uuml;berweisen Sie den Betrag') !== false && stripos($pp_payment_information[0]['title'], 'folgendes Konto') !== false) {
          $smarty->assign('KAR', true);
      }
      
      $smarty->assign('PAYMENT_INFO', $paypal->get_payment_instructions($pp_payment_information));
    }
    
    if(strpos($order->info['payment_method'], 'hpivsec') !== false) {
        $short_id_query = xtc_db_query("SELECT shortID FROM heidelpay_transaction_data WHERE orderId = '".$order->info['order_id']."' AND paymentmethod LIKE 'hpiv%' LIMIT 1");
        $short_id = xtc_db_fetch_array($short_id_query);
        if(!empty($short_id['shortID'])) {
            $order_comment_query = xtc_db_query("SELECT * FROM orders_status_history WHERE orders_id = '".$order->info['order_id']."' AND comments LIKE '%".$short_id['shortID']."%' ");
            $order_comment = xtc_db_fetch_array($order_comment_query);
            if(!empty($order_comment['comments'])) {
                $comment = str_replace('Ihre Transaktion war erfolgreich!', '', $order_comment['comments']);
                $comment = substr($comment, stripos('\r\n', $comment) + 1);
                $smarty->assign('PAYMENT_INFO_HPIVSEC', nl2br($comment));
            }
        }
    }
    
    
    $smarty->assign('PAYMENT_METHOD', $payment_method);
  }
  $smarty->assign('COMMENTS', nl2br($order->info['comments']));
  $smarty->assign('DATE', gmdate('d.m.Y', $invoice_date));
  $smarty->assign('INVOICE_NUMBER', isset($order->info['ibn_billnr']) && $order->info['ibn_billnr'] != '' ? $order->info['ibn_billnr'] :  $order->info['order_id']);
  $smarty->assign('INVOICE_DATE', isset($order->info['ibn_billdate']) && $order->info['ibn_billdate'] != '0000-00-00' ? xtc_date_short($order->info['ibn_billdate']) :  xtc_date_short($order->info['date_purchased']));
  $smarty->assign('DELIVERY_DATE', isset($order->info['ibn_billdate']) && $order->info['ibn_billdate'] != '0000-00-00' ? xtc_date_short($order->info['ibn_billdate']) :  xtc_date_short($order->info['date_purchased']));
  $smarty->assign('SHIPPING_CLASS', $order->info['shipping_class']);
  
  require_once(DIR_FS_CATALOG.'includes/classes/main.php');
  $main = new main();
  
  if($_GET['version'] == 'spb_garant') {
      $smarty->assign('SPB_GARANT', true);
  }

 //$invoice_data = $main->getContentData(INVOICE_INFOS);
 //$smarty->assign('ADDRESS_SMALL', $invoice_data['content_heading']);
 //$smarty->assign('ADDRESS_LARGE', $invoice_data['content_text']);

  // dont allow cache
  $smarty->caching = false;
  $smarty->template_dir=DIR_FS_CATALOG.'templates';
  $smarty->compile_dir=DIR_FS_CATALOG.'templates_c';
  $smarty->config_dir=DIR_FS_CATALOG.'lang';
  
  if($_GET['version'] == 'proforma_de') {
      $smarty->display(CURRENT_TEMPLATE . '/admin/print_order_proforma_de.html');
  }
  elseif($_GET['version'] == 'proforma_en') {
      $smarty->display(CURRENT_TEMPLATE . '/admin/print_order_proforma_en.html');
  }
  elseif($invoice_printed===false) {
      $smarty->display(CURRENT_TEMPLATE . '/admin/print_order.html');
  }
  else {
      $smarty->display(CURRENT_TEMPLATE . '/admin/order_already_printed.html');
  }
  
?>