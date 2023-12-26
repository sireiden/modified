<?php
/* -----------------------------------------------------------------------------------------
   $Id: paypalinstallment.php 11166 2018-05-30 12:57:09Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/


$lang_array = array(
  'MODULE_PAYMENT_PAYPALINSTALLMENT_TEXT_TITLE' => 'Ratenzahlung Powered by PayPal',
  'MODULE_PAYMENT_PAYPALINSTALLMENT_TEXT_INFO' => '<img src="https://www.paypal.com/de_DE/DE/i/logo/lockbox_150x47.gif" />',
  'MODULE_PAYMENT_PAYPALINSTALLMENT_TEXT_DESCRIPTION' => 'Ratenzahlung Powered by PayPal<br/>Mehr Infos zu Ratenzahlung powered by PayPal finden Sie <a target="_blank" href="https://www.paypal.com/de/webapps/mpp/installments">hier</a>.',
  'MODULE_PAYMENT_PAYPALINSTALLMENT_ALLOWED_TITLE' => 'Erlaubte Zonen',
  'MODULE_PAYMENT_PAYPALINSTALLMENT_ALLOWED_DESC' => 'Geben Sie <b>einzeln</b> die Zonen an, welche f&uuml;r dieses Modul erlaubt sein sollen. (z.B. AT,DE (wenn leer, werden alle Zonen erlaubt))',
  'MODULE_PAYMENT_PAYPALINSTALLMENT_STATUS_TITLE' => 'Ratenzahlung Powered by PayPal aktivieren',
  'MODULE_PAYMENT_PAYPALINSTALLMENT_STATUS_DESC' => 'M&ouml;chten Sie Zahlungen per Ratenzahlung Powered by PayPal akzeptieren?',
  'MODULE_PAYMENT_PAYPALINSTALLMENT_SORT_ORDER_TITLE' => 'Anzeigereihenfolge',
  'MODULE_PAYMENT_PAYPALINSTALLMENT_SORT_ORDER_DESC' => 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt',
  'MODULE_PAYMENT_PAYPALINSTALLMENT_ZONE_TITLE' => 'Zahlungszone',
  'MODULE_PAYMENT_PAYPALINSTALLMENT_ZONE_DESC' => 'Wenn eine Zone ausgew&auml;hlt ist, gilt die Zahlungsmethode nur f&uuml;r diese Zone.',
  'MODULE_PAYMENT_PAYPALINSTALLMENT_LP' => '<br /><br /><a target="_blank" href="http://www.paypal.com/de/webapps/mpp/referral/paypal-business-account2?partner_id=EHALBVD4M2RQS"><strong>Jetzt PayPal Konto hier erstellen.</strong></a>',

  'MODULE_PAYMENT_PAYPALINSTALLMENT_TEXT_EXTENDED_DESCRIPTION' => '<strong><font color="red">ACHTUNG:</font></strong> Bitte nehmen Sie noch die Einstellungen unter "Partner Module" -> "PayPal" -> <a href="'.xtc_href_link('paypal_config.php').'"><strong>"PayPal Konfiguration"</strong></a> vor!',

  'MODULE_PAYMENT_PAYPALINSTALLMENT_TEXT_ERROR_HEADING' => 'Hinweis',
  'MODULE_PAYMENT_PAYPALINSTALLMENT_TEXT_ERROR_MESSAGE' => 'PayPal Zahlung wurde abgebrochen',

  'MODULE_PAYMENT_PAYPALINSTALLMENT_TEXT_CHECKBOX' => 'Ich akzeptiere, dass meine Daten an PayPal weitergegeben werden.',
  'MODULE_PAYMENT_PAYPALINSTALLMENT_TEXT_ERROR_CHECKBOX' => 'Bitte akzeptieren Sie, dass Ihre Daten an PayPal weitergegeben werden.',
);


foreach ($lang_array as $key => $val) {
  defined($key) or define($key, $val);
}
?>