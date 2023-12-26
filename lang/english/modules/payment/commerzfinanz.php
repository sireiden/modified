<?php
/* -----------------------------------------------------------------------------------------
 $Id: paypal.php,v 1.2 2004/04/01 14:19:26 fanta2k Exp $
 
 XT-Commerce - community made shopping
 http://www.xt-commerce.com
 
 Copyright (c) 2003 XT-Commerce
 -----------------------------------------------------------------------------------------
 based on:
 (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
 (c) 2002-2003 osCommerce(paypal.php,v 1.7 2002/04/17); www.oscommerce.com
 (c) 2003	 nextcommerce (paypal.php,v 1.4 2003/08/13); www.nextcommerce.org
 
 Released under the GNU General Public License
 ---------------------------------------------------------------------------------------*/

define('MODULE_PAYMENT_COMMERZFINANZ_TEXT_TITLE', 'Finanzierung');
define('MODULE_PAYMENT_COMMERZFINANZ_TEXT_DESCRIPTION', 'Commerz Finanz GmbH');
define('MODULE_PAYMENT_COMMERZFINANZ_TEXT_INFO', 'Jetzt kaufen - und in kleinen monatlichen Raten zahlen!');
define('MODULE_PAYMENT_COMMERZFINANZ_ALLOWED_TITLE' , 'Erlaubte Zonen');
define('MODULE_PAYMENT_COMMERZFINANZ_ALLOWED_DESC' , 'Geben Sie <b>einzeln</b> die Zonen an, welche f&uuml;r dieses Modul erlaubt sein sollen. (z.B. AT,DE (wenn leer, werden alle Zonen erlaubt))');
define('MODULE_PAYMENT_COMMERZFINANZ_STATUS_TITLE' , 'Commerz Finanz GmbH Finanz Modul aktivieren');
define('MODULE_PAYMENT_COMMERZFINANZ_STATUS_DESC' , 'M&ouml;chten Sie Zahlungen per Commerz Finanz GmbH Finanz akzeptieren?');
define('MODULE_PAYMENT_COMMERZFINANZ_NUMBER_TITLE' , 'Commerz Finanz GmbH Haendlernummer');
define('MODULE_PAYMENT_COMMERZFINANZ_NUMBER_DESC' , 'Geben Sie hier Ihre Haendlernummer der Commerz Finanz GmbH ein');
define('MODULE_PAYMENT_COMMERZFINANZ_SORT_ORDER_TITLE' , 'Anzeigereihenfolge');
define('MODULE_PAYMENT_COMMERZFINANZ_SORT_ORDER_DESC' , 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt');
define('MODULE_PAYMENT_COMMERZFINANZ_ZONE_TITLE' , 'Zahlungszone');
define('MODULE_PAYMENT_COMMERZFINANZ_ZONE_DESC' , 'Wenn eine Zone ausgew&auml;hlt ist, gilt die Zahlungsmethode nur f&uuml;r diese Zone.');
define('MODULE_PAYMENT_COMMERZFINANZ_ORDER_STATUS_ID_TITLE' , 'Bestellstatus festlegen');
define('MODULE_PAYMENT_COMMERZFINANZ_ORDER_STATUS_ID_DESC' , 'Bestellungen, welche mit diesem Modul gemacht werden, auf diesen Status setzen');
?>