<?php
/* -----------------------------------------------------------------------------------------
   $Id: function.googleanalytics.php 12263 2019-10-08 07:32:08Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2011 WEB-Shop Software (function.googleAnalytics.php 1871) http://www.webs.de/

   Add the Google Analytics tracking code (and the possibility to track the order details as well)

   Usage: Put one of the following tags into the templates\yourtemplate\index.html at the bottom
   {googleanalytics account=UA-XXXXXXX-X}
   where "UA-XXXXXXX-X" is your Google Analytics ID

   Third party contributions:
   Snippets from http://webanalyse-news.de/xtcommerce-tracking-mit-google-analytics-tutorial/
   and https://developers.google.com/analytics/devguides/collection/gajs/

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

require_once (DIR_FS_INC.'get_order_total.inc.php');

function smarty_function_googleanalytics($params, $smarty) {
  global $PHP_SELF, $request_type, $last_order;
  
  if (!isset($params['account']) || TRACKING_GOOGLEANALYTICS_GTAG == 'true') {
    return false;
  }
  $account = strtoupper($params['account']);
  
  $google_linkid = null;
  $google_display = null;
  
  $beginCode = '
      <script type="text/javascript">
        // Set to the same value as the web property used on the site
        var gaProperty = \''.$account.'\';

        // Disable tracking if the opt-out cookie exists.
        var disableStr = \'ga-disable-\' + gaProperty;
        if (document.cookie.indexOf(disableStr + \'=true\') > -1) {
          window[disableStr] = true;
        }

        // Opt-out function
        function gaOptout() {
          document.cookie = disableStr + \'=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/\';
          window[disableStr] = true;
        }
 
        var _gaq = _gaq || [];
        var gaLoaded = false;
        
        function TrackingGoogle () {'."\n";
  
  if (TRACKING_GOOGLEANALYTICS_UNIVERSAL == 'false') {
    $beginCode .= '
          _gaq.push([\'_setAccount\', \''.$account.'\']);
          _gaq.push([\'_gat._anonymizeIp\']);
          _gaq.push([\'_trackPageview\']);'."\n";

    // cache ga.js
    $cache_gs = DIR_FS_CATALOG.'cache/ga.js';
    if (!is_file($cache_gs) || (time() - filemtime($cache_gs) > 3600)) {
      require_once(DIR_FS_INC.'get_external_content.inc.php');
      $source_gs = get_external_content('https://www.google-analytics.com/ga.js', 2, false);
      if (file_put_contents($cache_gs, $source_gs, LOCK_EX) !== false) {
        $gs = xtc_href_link('cache/ga.js', '', $request_type, false);
      }
    } elseif (is_file($cache_gs)) {
      $gs = xtc_href_link('cache/ga.js', '', $request_type, false);
    }

    $endCode ='
          if (gaLoaded === false) {
            (function() {
              var ga = document . createElement(\'script\');
              ga.type = \'text/javascript\';
              ga.async = true;
              ga.src = '.((isset($gs)) ? '\''.$gs.'\'' : '(\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\'').';
              var s = document.getElementsByTagName(\'script\')[0];
              s . parentNode . insertBefore(ga, s);
            })();
            gaLoaded = true;
          }
        }
      </script>
    ';
  } else {
    // cache analytics.js
    $cache_gs = DIR_FS_CATALOG.'cache/analytics.js';
    if (!is_file($cache_gs) || (time() - filemtime($cache_gs) > 3600)) {
      require_once(DIR_FS_INC.'get_external_content.inc.php');
      $source_gs = get_external_content('https://www.google-analytics.com/analytics.js', 2, false);
      if (file_put_contents($cache_gs, $source_gs, LOCK_EX) !== false) {
        $gs = xtc_href_link('cache/analytics.js', '', $request_type, false);
      }
    } elseif (is_file($cache_gs)) {
      $gs = xtc_href_link('cache/analytics.js', '', $request_type, false);
    }

    $beginCode .= "
          if (gaLoaded === false) {
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script',".((isset($gs)) ? "'".$gs."'" : "'//www.google-analytics.com/analytics.js'").",'ga');
            gaLoaded = true;
          }
        
          ga('create', '".$account."', '".TRACKING_GOOGLEANALYTICS_DOMAIN."');
          ga('set', 'anonymizeIp', true);\n";
  
    if (TRACKING_GOOGLE_LINKID == 'true'){
      $google_linkid = "          ga('require', 'linkid', 'linkid.js');\n";
    }
  
    if (TRACKING_GOOGLE_DISPLAY == 'true'){
      $google_display = "          ga('require', 'displayfeatures');\n";
    }
  
    $endCode = "          ga('send', 'pageview');
        }
      </script>";
  }
  
  $orderCode = null;
  if (strpos($PHP_SELF, FILENAME_CHECKOUT_SUCCESS) !== false
      && TRACKING_GOOGLE_ECOMMERCE == 'true'
      && !in_array('GA-'.$last_order, $_SESSION['tracking']['order'])
      )
  {
    $_SESSION['tracking']['order'][] = 'GA-'.$last_order;
    
    if (TRACKING_GOOGLEANALYTICS_UNIVERSAL == 'false') {
      $orderCode = getOrderDetailsAnalytics();
    } else {
      $orderCode = getOrderDetailsAnalyticsUniversal();
    }
  }

  return $beginCode . $google_linkid . $google_display . $orderCode . $endCode;
}


/**
 * Get the details of the order
 *
 * @global <type> $last_order
 * @return string Code for the eCommerce tracking
 */
function getOrderDetailsAnalytics() {
  global $last_order; // from checkout_success.php
  
  $total = get_order_total($last_order);
  
  $shipping_query = xtc_db_query("SELECT value
                                    FROM " . TABLE_ORDERS_TOTAL . "
                                   WHERE orders_id = '" . (int)$last_order . "' 
                                     AND class='ot_shipping'");
  $shipping = xtc_db_fetch_array($shipping_query);

  $tax_query = xtc_db_query("SELECT value
                               FROM " . TABLE_ORDERS_TOTAL . "
                              WHERE orders_id = '" . (int)$last_order . "' 
                                AND class='ot_tax'");
  $tax = xtc_db_fetch_array($tax_query);

  $location_query = xtc_db_query("SELECT customers_city,
                                         customers_state,
                                         customers_country
                                    FROM " . TABLE_ORDERS . "
                                   WHERE orders_id = '" . (int)$last_order . "'");
  $location = xtc_db_fetch_array($location_query);

  /**
   * _gaq.push(['_addTrans',
   *    '1234',           // order ID - required
   *    'Acme Clothing',  // affiliation or store name
   *    '11.99',          // total - required
   *    '1.29',           // tax
   *    '5',              // shipping
   *    'San Jose',       // city
   *    'California',     // state or province
   *    'USA'             // country
   *  ]);
   *
   */
  $addTrans = sprintf("          _gaq.push(['_addTrans','%s','%s','%s','%s','%s','%s','%s','%s']);\n",
    $last_order,
    addslashes(STORE_NAME),
    $total,
    $tax["value"],
    $shipping['value'],
    addslashes($location['customers_city']),
    addslashes($location['customers_state']),
    addslashes($location['customers_country'])
  );

  $item_query = xtc_db_query("SELECT cd.categories_name,
                                     op.products_id,
                                     op.orders_products_id,
                                     op.products_model,
                                     op.products_name,
                                     op.products_price,
                                     op.products_quantity
                                FROM " . TABLE_ORDERS_PRODUCTS . " op
                                JOIN " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c
                                     ON op.products_id = p2c.products_id
                                JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd
                                     ON p2c.categories_id = cd.categories_id
                                        AND cd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                               WHERE op.orders_id='" . (int)$last_order . "'
                            GROUP BY op.products_id");

  $addItem = array();
  while ($item = xtc_db_fetch_array($item_query)) {
    /**
     * _gaq.push(['_addItem',
     *    '1234', // order ID - required
     *    'DD44', // SKU/code - required
     *    'T-Shirt', // product name
     *    'Green Medium', // category or variation
     *    '11.99', // unit price - required
     *    '1'         // quantity - required
     *  ]);
     *
     */
    $addItem[] = sprintf("          _gaq.push(['_addItem','%s','%s','%s','%s','%s','%s']);\n",
      $last_order,
      $item['products_id'],
      addslashes($item['products_name']),
      addslashes($item['categories_name']),
      $item['products_price'],
      $item['products_quantity']
    );
  }
  $trackTrans = "          _gaq.push(['_trackTrans']);\n";

  return $addTrans . implode('', $addItem) . $trackTrans;
}


/**
 * Get the details of the order
 *
 * @global <type> $last_order
 * @return string Code for the eCommerce tracking
 */
function getOrderDetailsAnalyticsUniversal() {
  global $last_order; // from checkout_success.php

  $total = get_order_total($last_order);
  
  $shipping_query = xtc_db_query("SELECT value
                                    FROM " . TABLE_ORDERS_TOTAL . "
                                   WHERE orders_id = '" . (int)$last_order . "' 
                                     AND class='ot_shipping'");
  $shipping = xtc_db_fetch_array($shipping_query);

  $tax_query = xtc_db_query("SELECT value
                               FROM " . TABLE_ORDERS_TOTAL . "
                              WHERE orders_id = '" . (int)$last_order . "' 
                                AND class='ot_tax'");
  $tax = xtc_db_fetch_array($tax_query);

  $currency_query = xtc_db_query("SELECT currency
                                    FROM " . TABLE_ORDERS . "
                                   WHERE orders_id = '" . (int)$last_order . "'");
  $currency = xtc_db_fetch_array($currency_query);

  $trackCommerce = "          ga('require', 'ecommerce', 'ecommerce.js');\n";

  /* 
  ga('ecommerce:addTransaction', {
     'id': '1234',                     // Transaction ID. Required.
     'affiliation': 'Acme Clothing',   // Affiliation or store name.
     'revenue': '11.99',               // Grand Total.
     'shipping': '5',                  // Shipping.
     'tax': '1.29'                     // Tax.
  }); 
  */
  $addTrans = sprintf("          ga('ecommerce:addTransaction', {'id': '%s', 'affiliation': '%s', 'revenue': '%s', 'shipping': '%s', 'tax': '%s', 'currency': '%s'});\n",
    $last_order,
    addslashes(STORE_NAME),
    $total,
    $shipping['value'],
    $tax["value"],
    $currency['currency']
  );

  $item_query = xtc_db_query("SELECT cd.categories_name,
                                     op.products_id,
                                     op.orders_products_id,
                                     op.products_model,
                                     op.products_name,
                                     op.products_price,
                                     op.products_quantity
                                FROM " . TABLE_ORDERS_PRODUCTS . " op
                                JOIN " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c
                                     ON op.products_id = p2c.products_id
                                JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd
                                     ON p2c.categories_id = cd.categories_id
                                        AND cd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                               WHERE op.orders_id='" . (int)$last_order . "'
                            GROUP BY op.products_id");

  $addItem = array();
  while ($item = xtc_db_fetch_array($item_query)) {
   /*
   ga('ecommerce:addItem', {
      'id': '1234',
      'name': 'Fluffy Pink Bunnies',
      'sku': 'DD23444',
      'category': 'Party Toys',
      'price': '11.99',
      'quantity': '1',
      'currency': 'GBP' // local currency code.
    });
    */
    $addItem[] = sprintf("          ga('ecommerce:addItem', {'id': '%s', 'name': '%s', 'sku': '%s', 'category': '%s', 'price': '%s', 'quantity': '%s', 'currency': '%s'});\n",
      $last_order,
      addslashes($item['products_name']),
      $item['products_id'],
      addslashes($item['categories_name']),
      $item['products_price'],
      $item['products_quantity'],
      $currency['currency']
    );
  }
  $trackTrans = "          ga('ecommerce:send');\n";

  return $trackCommerce . $addTrans . implode('', $addItem) . $trackTrans;
}
?>