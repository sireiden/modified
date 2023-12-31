<?php
/* -----------------------------------------------------------------------------------------
   $Id: checkout_shipping_address.php 11874 2019-07-10 07:04:09Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(checkout_shipping_address.php,v 1.14 2003/05/27); www.oscommerce.com
   (c) 2003 nextcommerce (checkout_shipping_address.php,v 1.14 2003/08/17); www.nextcommerce.org
   (c) 2006 xtcommerce (checkout_shipping_address.php 867 2005-04-21)

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

include ('includes/application_top.php');

// create smarty elements
$smarty = new Smarty;

// include needed functions
require_once (DIR_FS_INC.'xtc_count_customer_address_book_entries.inc.php');
require_once (DIR_FS_INC.'xtc_address_label.inc.php');
require_once (DIR_FS_INC.'xtc_get_address_format_id.inc.php');
require_once (DIR_FS_INC.'xtc_address_format.inc.php');
require_once (DIR_FS_INC.'xtc_get_country_name.inc.php');
require_once (DIR_FS_INC.'xtc_get_zone_code.inc.php');
require_once (DIR_FS_INC.'secure_form.inc.php');

$params = '';
$link_checkout_shipping = FILENAME_CHECKOUT_SHIPPING;
if (isset($_SESSION['paypal']['PayerID'])) {
  $params = xtc_get_all_get_params();
  $link_checkout_shipping = FILENAME_CHECKOUT_CONFIRMATION;
}

// if the customer is not logged on, redirect them to the login page
if (!isset ($_SESSION['customer_id'])) {
  xtc_redirect(xtc_href_link(FILENAME_LOGIN, '', 'SSL'));
}

// if there is nothing in the customers cart, redirect them to the shopping cart page
if ($_SESSION['cart']->count_contents() < 1) {
  xtc_redirect(xtc_href_link(FILENAME_SHOPPING_CART));
}

// if the order contains only virtual products, forward the customer to the billing page as a shipping address is not needed
if (isset($order) && $order->content_type == 'virtual' && !isset($_SESSION['paypal']['PayerID'])) {
  $_SESSION['shipping'] = false;
  $_SESSION['sendto'] = false;
  xtc_redirect(xtc_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
}

$error = false;
$process = false;
if (isset ($_POST['action']) && ($_POST['action'] == 'submit')) {
  // process a new shipping address
  if (xtc_not_null($_POST['firstname']) 
      && xtc_not_null($_POST['lastname']) 
      && xtc_not_null($_POST['street_address'])
      ) 
  {
    $checkout_page = 'shipping';
    include(DIR_WS_MODULES.'checkout_address_store.php');    
  // process the selected shipping destination
  } elseif (isset ($_POST['address'])) {
    $reset_shipping = false;
    if (isset ($_SESSION['sendto'])) {
      if ($_SESSION['sendto'] != $_POST['address']) {
        if (isset ($_SESSION['shipping'])) {
          $reset_shipping = true;
        }
      }
    }

    $_SESSION['sendto'] = (int)$_POST['address'];

    $check_address_query = xtc_db_query("SELECT count(*) AS total 
                                           FROM ".TABLE_ADDRESS_BOOK." 
                                          WHERE customers_id = '".(int)$_SESSION['customer_id']."' 
                                            AND address_book_id = '".(int)$_SESSION['sendto']."'");
    $check_address = xtc_db_fetch_array($check_address_query);

    if ($check_address['total'] == '1') {
      if ($reset_shipping == true && !isset($_SESSION['paypal']['PayerID'])) {
        unset ($_SESSION['shipping']);
      }
      xtc_redirect(xtc_href_link($link_checkout_shipping, $params, 'SSL'));
    } else {
      unset ($_SESSION['sendto']);
    }
  } else {
    $_SESSION['sendto'] = $_SESSION['customer_default_address_id'];
    xtc_redirect(xtc_href_link($link_checkout_shipping, $params, 'SSL'));
  }
}

// if no shipping destination address was selected, use their own address as default
if (!isset ($_SESSION['sendto'])) {
  $_SESSION['sendto'] = $_SESSION['customer_default_address_id'];
}

// include boxes
require (DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/boxes.php');

$breadcrumb->add(NAVBAR_TITLE_1_CHECKOUT_SHIPPING_ADDRESS, xtc_href_link($link_checkout_shipping, $params, 'SSL'));
$breadcrumb->add(NAVBAR_TITLE_2_CHECKOUT_SHIPPING_ADDRESS, xtc_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, $params, 'SSL'));

$addresses_count = xtc_count_customer_address_book_entries();

require (DIR_WS_INCLUDES.'header.php');
$smarty->assign('FORM_ACTION', xtc_draw_form('checkout_address', xtc_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, $params, 'SSL'), 'post', 'onsubmit="return check_form_optional(checkout_address);"').secure_form());

if ($messageStack->size('checkout_address') > 0) {
  $smarty->assign('error', $messageStack->output('checkout_address'));
}

if ($process == false) {
  $smarty->assign('ADDRESS_LABEL', xtc_address_label($_SESSION['customer_id'], $_SESSION['sendto'], true, ' ', '<br />'));
  include(DIR_WS_MODULES.'checkout_address_layout.php');
}

if ($addresses_count < MAX_ADDRESS_BOOK_ENTRIES) {
  require (DIR_WS_MODULES.'checkout_new_address.php');
}
$smarty->assign('BUTTON_CONTINUE', xtc_draw_hidden_field('action', 'submit').xtc_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE));

if ($process == true) {
  $smarty->assign('BUTTON_BACK', '<a href="'.xtc_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, $params, 'SSL').'">'.xtc_image_button('button_back.gif', IMAGE_BUTTON_BACK).'</a>');
}
$smarty->assign('FORM_END', '</form>');
if (isset($_SESSION['NO_SHIPPING']) && $_SESSION['NO_SHIPPING'] === true) {
  $smarty->assign('NO_SHIPPING', $_SESSION['NO_SHIPPING']);
}
$smarty->assign('language', $_SESSION['language']);
$main_content = $smarty->fetch(CURRENT_TEMPLATE.'/module/checkout_shipping_address.html');
$smarty->assign('main_content', $main_content);
$smarty->caching = 0;
if (!defined('RM')) {
  $smarty->load_filter('output', 'note');
}
$smarty->display(CURRENT_TEMPLATE.'/index.html');
include ('includes/application_bottom.php');
?>