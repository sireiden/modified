<?php
/* -----------------------------------------------------------------------------------------
 $Id: billiger.php 2020 2011-06-24 10:10:55Z dokuman $

 modified eCommerce Shopsoftware
 http://www.modified-shop.org

 Copyright (c) 2009 - 2013 [www.modified-shop.org]
 -----------------------------------------------------------------------------------------
 based on:
 (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
 (c) 2002-2003 osCommerce(cod.php,v 1.28 2003/02/14); www.oscommerce.com
 (c) 2003 nextcommerce (invoice.php,v 1.6 2003/08/24); www.nextcommerce.org
 (c) 2006 xt-commerce; www.xt-commerce.com
 (c) 2008 modified by m3WebWork.de - version 1.1

 Released under the GNU General Public License
 ---------------------------------------------------------------------------------------*/

error_reporting('E_ALL');
defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');
define('MODULE_IDEALO_TEXT_DESCRIPTION', 'Einfach mit wenigen Klicks alle Artikel samt Versandkosten f&uuml;r www.idealo.de exportieren.');
define('MODULE_IDEALO_TEXT_TITLE', 'Idealo Export - CSV');
define('MODULE_IDEALO_FILE_TITLE' , '<hr />Dateiname');
define('MODULE_IDEALO_FILE_DESC' , 'Geben Sie einen Dateinamen ein, falls die Exportadatei am Server gespeichert werden soll.<br>(Verzeichnis export/)');
define('MODULE_IDEALO_STATUS_DESC', 'Modulstatus');
define('MODULE_IDEALO_STATUS_TITLE', 'Status');
define('MODULE_IDEALO_CURRENCY_TITLE', 'W&auml;hrung');
define('MODULE_IDEALO_CURRENCY_DESC', 'Welche W&auml;hrung soll exportiert werden?');
define('EXPORT_YES', 'Nur Herunterladen');
define('EXPORT_NO', 'Am Server Speichern');
define('CURRENCY', '<hr /><b>W&auml;hrung:</b>');
define('CURRENCY_DESC', 'W&auml;hrung in der Exportdatei');
define('LANGUAGE', '<hr /><b>Sprache:</b>');
define('LANGUAGE_DESC', 'Sprache in der Exportdatei');
define('EXPORT', 'Bitte den Sicherungsprozess AUF KEINEN FALL unterbrechen. Dieser kann einige Minuten in Anspruch nehmen.');
define('EXPORT_TYPE', '<hr /><b>Speicherart:</b>');
define('EXPORT_STATUS_TYPE', '<hr /><b>Kundengruppe:</b>');
define('EXPORT_STATUS', 'Bitte w&auml;hlen Sie die Kundengruppe, die Basis f&uuml;r den Exportierten Preis bildet. (Falls Sie keine Kundengruppenpreise haben, w&auml;hlen Sie <i>Gast</i>):</b>');
define('CAMPAIGNS', '<hr /><b>Kampagnen:</b>');
define('CAMPAIGNS_DESC', 'Mit Kampagne zur Nachverfolgung verbinden.');
define('DATE_FORMAT_EXPORT', '%d.%m.%Y'); // this is used for strftime()

class idealo {
    var $code;
    var $title;
    var $description;
    var $enabled;
    
    function idealo()
    {
        global $order;
        
        $this->code = 'idealo';
        $this->title = MODULE_IDEALO_TEXT_TITLE;
        $this->description = MODULE_IDEALO_TEXT_DESCRIPTION;
        $this->sort_order = MODULE_IDEALO_SORT_ORDER;
        $this->enabled = ((MODULE_IDEALO_STATUS == 'True') ? true : false);
        $this->CAT = array();
        $this->PARENT = array();
    }
    
    function process($file)
    {
        
        $hersteller = '24, 27, 34, 39, 40, 41, 49, 50, 52, 54, 57, 59, 62, 64, 65, 66, 67, 69, 71, 73, 78, 79, 80, 82, 83, 84, 88, 90, 97, 99, 103, 104, 105, 106, 109, 110, 112, 113, 114, 115, 117, 119, 121, 122, 125, 127, 128, 129, 130, 131, 132, 133, 134, 135, 136, 137, 138, 139, 140, 141, 142, 143, 145';
        @xtc_set_time_limit(0);
        
        $file = $_POST['configuration']['MODULE_IDEALO_FILE'];
        
        require(DIR_FS_CATALOG . DIR_WS_CLASSES . 'xtcPrice.php');
        $xtPrice = new xtcPrice($_POST['currencies'], $_POST['status']);
        
        $export_query =xtc_db_query("SELECT
                     p.products_id,
                     pd.products_name,
                     pd.products_description,
                     pd.products_short_description,
                     p.products_model,
                     p.manufacturers_id,
            		 p.products_manufacturers_model,
                     p.products_ean,
                     p.products_image,
                     p.products_price,
                     p.products_status,
                     p.products_date_available,
                     p.products_shippingtime,
                     p.products_discount_allowed,
                     pd.products_meta_keywords,
                     p.products_tax_class_id,
                     p.products_date_added,
                     p.products_weight,
                     pf.products_shippinginfo,
                     pf.products_cash_discount,
                     m.manufacturers_name
                 FROM
                     " . TABLE_PRODUCTS . " p LEFT JOIN
                     " . TABLE_MANUFACTURERS . " m
                   ON p.manufacturers_id = m.manufacturers_id LEFT JOIN
                     " . TABLE_PRODUCTS_DESCRIPTION . " pd
                   ON p.products_id = pd.products_id AND
                    pd.language_id = '".(int)$_SESSION['languages_id']."' LEFT JOIN
                     " . TABLE_SPECIALS . " s
                   ON p.products_id = s.products_id LEFT JOIN
                     " . TABLE_PRODUCTS_FIELDS . " pf
                   ON p.products_id = pf.products_id
                 WHERE
                   p.products_status = 1
                   AND p.manufacturers_id IN (". $hersteller .")
                 ORDER BY
                    p.products_date_added DESC,
                    pd.products_name
                 LIMIT 1000");
        //BOF - DokuMan - 2011-06-24 - fix sql query (thx to franky_n)
        
        // csv schema / headline
        $schema = 'Artikelnummer im Shop;EAN/Barcodenummer;Original Herstellerartikelnummer;Herstellername;Produktname;Produktgruppe im Shop;Preis (Brutto);Lieferzeit;ProduktURL;BildURL_1;Vorkasse;Nachnahme;Kreditkarte;Paypal;Sofortüberweisung;Giropay;Barzahlung;Rechnung;Lastschrift;Amazon Payments;Versandkosten Kommentar;Produktbeschreibung';
        
        
        $schema .= "\n";
        // parse data
        while ($products = xtc_db_fetch_array($export_query)) {
            $Artikelnummer = $products['products_model'];
            $EAN = $products['products_ean'];
            $Herstellerartikelnummer = $products['products_manufacturers_model'];
            $Herstellername = $products['manufacturers_name'];
            $Produktname = $this->cleanVars($products['products_name']);
            $Produktgruppe = $this->buildCAT($this->getCategoriesID($products['products_id']));
            $Preis = $xtPrice->xtcGetPrice($products['products_id'], $format = false, 1, $products['products_tax_class_id'], '');
            $Lieferzeit = $this->getShippingtimeName($products['products_shippingtime']);
            $ProduktURL = xtc_catalog_href_link('product_info.php', xtc_product_link($products['products_id'], $products['products_name']).'&refTrack=idealo'.(!empty($_POST['campaign']) ? '&'.$_POST['campaign'] : ''));
            $BildURL_1 = ($products['products_image'] != '') ? HTTP_CATALOG_SERVER . DIR_WS_CATALOG_POPUP_IMAGES . $products['products_image'] : '';
            $shipping_cost = $this->getShippingCost($products['products_weight']);
            /* Skonto wird nicht mehr berücksichtigt, da guenstiger keine negativen Werte akzeptieret
             $Vorkasse =  number_format($this->getCashDiscount($products['products_cash_discount'], $products['products_price']) + $shipping_cost, 2, ',', '0') ;
             */
            $Vorkasse =  number_format($shipping_cost, 2, ',', '0') ;
            $Nachnahme = number_format($this->getNachnahmeFee($products['products_cod_fee'], $products['products_weight']) + $shipping_cost, 2, ',', '0') ;
            $Kreditkarte = number_format($shipping_cost, 2, ',', '0') ;
            $PayPal = number_format($shipping_cost, 2, ',', '0') ;
            $Sofortueberweisung = number_format($shipping_cost, 2, ',', '0') ;
            $Giropay = number_format($shipping_cost, 2, ',', '0') ;
            $Barzahlung = number_format($shipping_cost, 2, ',', '0') ;
            $Rechnung = number_format($shipping_cost, 2, ',', '0') ;
            $Lastschrift = number_format($shipping_cost, 2, ',', '0') ;
            $Amazon = number_format($shipping_cost, 2, ',', '0') ;
            $Versandkosten_Kommentar = substr($this->cleanVars($products['products_shippinginfo']), 0, 100);
            $ProduktBeschreibung = substr($this->cleanVars($products['products_description']), 0, 1000);
            $ProduktBeschreibung = utf8_decode($ProduktBeschreibung);
            
            if(stristr($products['products_description'], 'KEINE EINZELBESTELLUNG M&Ouml;GLICH!') !== FALSE) {
                continue;
            }
            
            $manu_array = array(125, 130, 84, 123, 115, 67, 83, 57, 80, 112, 97, 117, 82, 66, 121);
           
            
            // add line
            if(($Preis + $shipping_cost) >= 85.01) {
                $schema .= $Artikelnummer . ";" .
                    $EAN . ";" .
                    $Herstellerartikelnummer. ";" .
                    $Herstellername. ";" .
                    $Produktname. ";" .
                    substr($Produktgruppe, 0, strlen($Produktgruppe)-2) . ";" . // kategorie
                    number_format($Preis, 2, ',', '') . ";" . // preis
                    $Lieferzeit. "; " . //lieferzeit
                    $ProduktURL . ";" . // link;
                    $BildURL_1 . ";" . // Bilderlink;
                    $Vorkasse . ";" .
                    $Nachnahme . ";" .
                    $Kreditkarte . ";" .
                    $PayPal . ";" .
                    $Sofortueberweisung . ";" .
                    $Giropay . ";" .
                    $Barzahlung . ";" .
                    $Rechnung . ";" .
                    $Lastschrift. ";" .
                    $Amazon. ";" .
                    $Versandkosten_Kommentar . ";" .
                    $ProduktBeschreibung . ";" .
                    "\n";
            }
        }
        
        $filename = DIR_FS_DOCUMENT_ROOT . 'export/' . $file;
        if($_POST['export'] == 'yes') { $filename = $filename.'.tmp_'.time(); }
        // create File
        $fp = fopen( $filename, "w+");
        fputs($fp, $schema);
        fclose($fp);
        // send File to Browser
        
        switch ($_POST['export']) {
            case 'yes':
                header('Content-type: application/x-octet-stream');
                header('Content-disposition: attachment; filename=' . $file);
                readfile ( $filename );
                unlink( $filename );
                exit;
                break;
        }
    }
    // helper
    
    function buildCAT($catID)
    {
        if (isset($this->CAT[$catID])) {
            return $this->CAT[$catID];
        } else {
            $cat = array();
            $tmpID = $catID;
            
            while ($this->getParent($catID) != 0 || $catID != 0) {
                $cat_select = xtc_db_query("SELECT categories_name FROM " . TABLE_CATEGORIES_DESCRIPTION . " WHERE categories_id='" . $catID . "' and language_id='2'");
                $cat_data = xtc_db_fetch_array($cat_select);
                $catID = $this->getParent($catID);
                $cat[] = $cat_data['categories_name'];
            }
            $catStr = '';
            for ($i = count($cat);$i > 0;$i--) {
                $catStr .= $cat[$i-1] . ' > ';
            }
            $this->CAT[$tmpID] = $catStr;
            return $this->CAT[$tmpID];
        }
    }
    // helper
    function getParent($catID)
    {
        if (isset($this->PARENT[$catID])) {
            return $this->PARENT[$catID];
        } else {
            $parent_query = xtc_db_query("SELECT parent_id FROM " . TABLE_CATEGORIES . " WHERE categories_id='" . $catID . "'");
            $parent_data = xtc_db_fetch_array($parent_query);
            $this->PARENT[$catID] = $parent_data['parent_id'];
            return $parent_data['parent_id'];
        }
    }
    // helper
    function getCategoriesID($pID)
    {
        $categorie_query = xtc_db_query("SELECT
                                          categories_id
                                        FROM
                      " . TABLE_PRODUCTS_TO_CATEGORIES . "
                                        WHERE
                      products_id='" . $pID . "'");
        while ($categorie_data = xtc_db_fetch_array($categorie_query)) {
            $categories = $categorie_data['categories_id'];
        }
        return $categories;
    }
    // helper
    function getShippingtimeName($sID)
    {
        $query = xtc_db_query("SELECT shipping_status_name FROM " . TABLE_SHIPPING_STATUS . " WHERE shipping_status_id='" . $sID . "' AND language_id='2'");
        $data = xtc_db_fetch_array($query);
        $this->SHIPPINGTIMENAME = $data['shipping_status_name'];
        return $data['shipping_status_name'];
    }
    // helper
    function getShippingCost($pWeight)
    {
        global $xtPrice;
        for ($i=1; $i<=10; $i++) {
            $countries_table = constant('MODULE_SHIPPING_ZONESE_COUNTRIES_' . $i);
            $country_zones = explode(",", $countries_table);
            if (in_array('DE', $country_zones)) {
                $dest_zone = $i;
                break;
            }
        }
        
        $ZONESE_cost = constant('MODULE_SHIPPING_ZONESE_COST_' . $dest_zone);
        $ZONESE_table = preg_split("/[:,]/" , $ZONESE_cost);
        $size = sizeof($ZONESE_table);
        for ($i=0; $i<$size; $i+=2) {
            if ($pWeight <= $ZONESE_table[$i]) {
                $shipping_cost = $ZONESE_table[$i+1];
                break;
            }
        }
        
        if (MODULE_SHIPPING_ZONESE_TAX_CLASS > 0) {
            $shipping_tax = xtc_get_tax_rate(MODULE_SHIPPING_ZONESE_TAX_CLASS, 81, 0);
        }
        
        $shipping_fee = xtc_add_tax($shipping_cost, $shipping_tax);
        return $shipping_fee;
    }
    
    
    function getCashDiscount($products_cash_discount, $products_price)
    {
        $cash_discount_value = 0;
        if (MODULE_ORDER_TOTAL_CASH_DISCOUNT_STATUS == 'true')
        {
            $cash_discount = ($products_price / 100 * $products_cash_discount);
            $discount_tax = xtc_get_tax_rate(MODULE_ORDER_TOTAL_CASH_DISCOUNT_TAX_CLASS, 81, 0);
            $cash_discount_value= xtc_add_tax($cash_discount, $discount_tax);
            $cash_discount_value = $cash_discount_value * -1 ;
        }
        return $cash_discount_value;
    }
    
    function getNachnahmeFee($products_nachnahme_fee, $products_weight)
    {
        $nachnahme_value = 0;
        if (MODULE_ORDER_TOTAL_COD_FEE_STATUS == 'true')
        {
            $cod_zones = split("[:,]", MODULE_ORDER_TOTAL_COD_FEE_ZONESE);
            for ($i = 0; $i < count($cod_zones); $i++) {
                if ($cod_zones[$i] == 'DE') {
                    if(strpos($cod_zones[$i + 1],'|') !== false) {
                        $cod_zone = preg_split("/[<|]/", $cod_zones[$i + 1]);
                        for ($b = 0; $b < count($cod_zone); $b+=2) {
                            if ($products_weight <= $cod_zone[$b]) {
                                $nachnahme_fee = $cod_zone[$b + 1];
                                break;
                            }
                        }
                    }
                    else {
                        $nachnahme_fee = $cod_zones[$i + 1];
                        break;
                    }
                }
            }
            
            if($products_nachnahme_fee == 1) {
                $nachnahme_tax = xtc_get_tax_rate(MODULE_ORDER_TOTAL_COD_FEE_TAX_CLASS, 81, 0);
                $nachnahme_value= xtc_add_tax($nachnahme_fee, $nachnahme_tax);
            }
        }
        return $nachnahme_value;
    }
    
    // helper
    function cleanVars($string)
    {
        $string = strip_tags($string);
        $string = html_entity_decode($string);
        $string = str_replace("<br>", " ", $string);
        $string = str_replace("<br />", " ", $string);
        $string = str_replace("<br/>", " ", $string);
        $string = str_replace(";", ", ", $string);
        $string = str_replace("'", ", ", $string);
        $string = str_replace("\n", " ", $string);
        $string = str_replace("\r", " ", $string);
        $string = str_replace("\t", " ", $string);
        $string = str_replace("\v", " ", $string);
        $string = str_replace("&quot,", " \"", $string);
        $string = str_replace("&qout,", " \"", $string);
        $string = str_replace(chr(13), " ", $string);
        $string = substr($string, 0, 5000);
        
        return $string;
    }
    // display
    function display()
    {
        /* Auswahl Kundengruppe vorbeiten */
        $customers_statuses_array = xtc_get_customers_statuses();
        
        /* Auswahl Währung vorbereiten */
        $curr = '';
        $currencies = xtc_db_query("SELECT code FROM " . TABLE_CURRENCIES . " ORDER BY currencies_id DESC");
        while ($currencies_data = xtc_db_fetch_array($currencies)) {
            $curr .= xtc_draw_radio_field('currencies', $currencies_data['code'], true) . $currencies_data['code'] . '<br>';
        }
        
        /* Auswahl Sprachen vorbereiten (ich)*/
        $lang = '';
        $languages = xtc_db_query("SELECT languages_id, name FROM " . TABLE_LANGUAGES . " ORDER BY sort_order ASC");
        while ($languages_data = xtc_db_fetch_array($languages)) {
            $lang .= xtc_draw_radio_field('languages_id', $languages_data['languages_id'], true) . $languages_data['name'] . '<br>';
        }
        /* Auswahl Kampagnen vorbereiten */
        $campaign_array = array(array('id' => '', 'text' => TEXT_NONE));
        $campaign_query = xtc_db_query("select campaigns_name, campaigns_refID from " . TABLE_CAMPAIGNS . " order by campaigns_id");
        while ($campaign = xtc_db_fetch_array($campaign_query)) {
            $campaign_array[] = array ('id' => 'refID=' . $campaign['campaigns_refID'] . '&', 'text' => $campaign['campaigns_name'],);
        }
        
        /* Ausgabe */
        return array('text' =>
            EXPORT_STATUS_TYPE . '<br>' .
            EXPORT_STATUS . '<br>' .
            xtc_draw_pull_down_menu('status', $customers_statuses_array, '1') . '<br>' .
            CURRENCY . '<br>' .
            CURRENCY_DESC . '<br>' . $curr .
            CAMPAIGNS . '<br>' .
            CAMPAIGNS_DESC . '<br>' .
            xtc_draw_pull_down_menu('campaign', $campaign_array) . '<br>' .
            EXPORT_TYPE . '<br>' .
            EXPORT . '<br>' .
            xtc_draw_radio_field('export', 'no', false) . EXPORT_NO . '<br>' .
            xtc_draw_radio_field('export', 'yes', true) . EXPORT_YES . '<br>' . '<br>' . xtc_button(BUTTON_EXPORT) .
            xtc_button_link(BUTTON_CANCEL, xtc_href_link(FILENAME_MODULE_EXPORT, 'set=' . $_GET['set'] . '&module=idealo')) . '');
    }
    // check
    function check()
    {
        if (!isset($this->_check)) {
            $check_query = xtc_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_IDEALO_STATUS'");
            $this->_check = xtc_db_num_rows($check_query);
        }
        return $this->_check;
    }
    // install
    function install()
    {
        xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_IDEALO_FILE', 'idealo.csv',  '6', '1', '', now())");
        xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_IDEALO_STATUS', 'True',  '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
        
    }
    // remove
    function remove()
    {
        xtc_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
    // keys
    function keys()
    {
        return array('MODULE_IDEALO_STATUS', 'MODULE_IDEALO_FILE');
    }
    
}
?>