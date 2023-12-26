<?php

error_reporting('E_NONE');
/* -----------------------------------------------------------------------------------------
 modified eCommerce Shopsoftware
 http://www.modified-shop.org
 
 Copyright (c) 2009 - 2013 [www.modified-shop.org]
 -----------------------------------------------------------------------------------------
 based on:
 (c) 2000-2001 The Exchange Project (earlier name of osCommerce)
 (c) 2002-2003 osCommerce(cod.php,v 1.28 2003/02/14); www.oscommerce.com
 (c) 2003 nextcommerce (invoice.php,v 1.6 2003/08/24); www.nextcommerce.org
 
 Released under the GNU General Public License
 ---------------------------------------------------------------------------------------*/
defined( '_VALID_XTC' ) or die( 'Direct Access to this location is not allowed.' );

define('MODULE_GOOGLE_SHOPPING_TEXT_DESCRIPTION', 'Export - Google Shopping');
define('MODULE_GOOGLE_SHOPPING_TEXT_TITLE', 'Google Shopping');
define('MODULE_GOOGLE_SHOPPING_FILE_TITLE' , '<hr noshade>Dateiname');
define('MODULE_GOOGLE_SHOPPING_FILE_DESC' , 'Geben Sie einen Dateinamen ein, falls die Exportdatei am Server gespeichert werden soll.<br>(Verzeichnis export/)');
define('MODULE_GOOGLE_SHOPPING_STATUS_DESC','Modulstatus') ;
define('MODULE_GOOGLE_SHOPPING_STATUS_TITLE','Status');
define('MODULE_GOOGLE_SHOPPING_CURRENCY_TITLE','W&auml;hru ng');
define('MODULE_GOOGLE_SHOPPING_CURRENCY_DESC','Welche W&auml;hrung soll exportiert werden?');
define('EXPORT_YES','Nur Herunterladen');
define('EXPORT_NO','Am Server Speichern');
define('CURRENCY','<hr noshade><b>W&auml;hrung:</b>');
define('CURRENCY_DESC','W&auml;hrung in der Exportdatei');
define('EXPORT','Bitte den Sicherungsprozess AUF KEINEN FALL unterbrechen. Dieser kann einige Minuten in Anspruch nehmen.');
define('EXPORT_TYPE','<hr noshade><b>Speicherart:</b>');
define('EXPORT_STATUS_TYPE','<hr noshade><b>Kundengruppe:</b>');
define('EXPORT_STATUS','Bitte w&auml;hlen Sie die Kundengruppe, die Basis f&uuml;r den Exportierten Preis bildet. (Falls Sie keine Kundengruppenpreise haben, w&auml;hlen Sie <i>Gast</i>):</b>');
define('CAMPAIGNS', '<hr /><b>Kampagnen:</b>');
define('CAMPAIGNS_DESC', 'Mit Kampagne zur Nachverfolgung verbinden.');
define('DATE_FORMAT_EXPORT', '%d.%m.%Y'); // this is used for strftime()

// include needed functions


class google_shopping {
    var $code, $title, $description, $enabled;
    
    
    function google_shopping() {
        $this->code       = 'google_shopping';
        $this->language   = 'de';
        $this->title = MODULE_GOOGLE_SHOPPING_TEXT_TITLE;
        $this->description = MODULE_GOOGLE_SHOPPING_TEXT_DESCRIPTION;
        $this->sort_order = MODULE_GOOGLE_SHOPPING_SORT_ORDER;
        $this->enabled = ((MODULE_GOOGLE_SHOPPING_STATUS == 'True') ? true : false);
        $this->CAT=array();
        $this->PARENT=array();
    }
    
    
    function process($file) {
        
        $hersteller = '24, 27, 34, 39, 40, 41, 49, 50, 52, 54, 57, 59, 62, 64, 65, 66, 67, 69, 71, 73, 79, 80, 82, 83, 84, 88, 90, 97, 99, 103, 104, 105, 106, 109, 110, 112, 113, 114, 115, 117, 119, 121, 122, 125, 127, 128, 129, 130, 131, 132, 133, 134, 135, 137, 138, 139, 140, 141, 142, 143, 145';
        @xtc_set_time_limit(0);
        require(DIR_FS_CATALOG.DIR_WS_CLASSES . 'xtcPrice.php');
        $xtPrice = new xtcPrice($_POST['currencies'],$_POST['status']);
        
        $schema = 'ID'."\t".'MPN'."\t".'ean'."\t".'Marke'."\t".'Titel'."\t".'Beschreibung'."\t".'bild_link'."\t".'Link'."\t".'Versandgewicht'."\t".'Versand'."\t".'Preis'."\t".'Zustand'."\t".'availability'."\t".'Produkttyp'."\n" ;
        
        
        $export_query =xtc_db_query("SELECT
                             p.products_id,
                             pd.products_name,
                             pd.products_description,
                             p.products_model,
                             p.products_ean,
                             p.products_image,
                             p.products_price,
                             p.products_status,
                             p.products_weight,
                             p.products_date_available,
                             p.products_shippingtime,
                             p.products_discount_allowed,
                             pd.products_meta_keywords,
                             p.products_tax_class_id,
                             p.products_date_added,
                             m.manufacturers_name,
                             m.manufacturers_id
                         FROM
                             " . TABLE_PRODUCTS . " p LEFT JOIN
                             " . TABLE_MANUFACTURERS . " m
                           ON p.manufacturers_id = m.manufacturers_id LEFT JOIN
                             " . TABLE_PRODUCTS_DESCRIPTION . " pd
                           ON p.products_id = pd.products_id AND
                            pd.language_id = '".$_SESSION['languages_id']."' LEFT JOIN
                             " . TABLE_SPECIALS . " s
                           ON p.products_id = s.products_id
                         WHERE
                           p.products_status = 1 AND
                           p.products_price > 0 AND
                           p.manufacturers_id IN (". $hersteller .")
                         ORDER BY
                            p.products_date_added DESC,
                            pd.products_name");
        
        
        while ($products = xtc_db_fetch_array($export_query)) {
            
            $products_price = $xtPrice->xtcGetPrice($products['products_id'],
                $format=false,
                1,
                $products['products_tax_class_id'],
                '');
                
                // get product categorie
                $categorie_query=xtc_db_query("SELECT
                                            categories_id
                                            FROM ".TABLE_PRODUCTS_TO_CATEGORIES."
                                            WHERE products_id='".$products['products_id']."'");
                while ($categorie_data=xtc_db_fetch_array($categorie_query)) {
                    $categories=$categorie_data['categories_id'];
                }
                
                if(stristr($products['products_description'], 'KEINE EINZELBESTELLUNG M&Ouml;GLICH!') !== FALSE) {
                    continue;
                }
                
                // remove trash
                //BOF - Hetfield - 2009-08-11 - remove too much str_replace functiones and using html_entity_decode
                $products_description = strip_tags($products['products_description']);
                $products_description = html_entity_decode($products_description);
                //EOF - Hetfield - 2009-08-11 - remove too much str_replace functiones and using html_entity_decode
                $products_description = str_replace(";",", ",$products_description);
                $products_description = str_replace("'",", ",$products_description);
                $products_description = str_replace("\n"," ",$products_description);
                $products_description = str_replace("\r"," ",$products_description);
                $products_description = str_replace("\t"," ",$products_description);
                $products_description = str_replace("\v"," ",$products_description);
                $products_description = str_replace(chr(13)," ",$products_description);
                $products_description = substr($products_description, 0, 4500);
                $cat = $this->buildCAT($categories);
                
                //-- Shopstat URLS ----//
                //zur Aktivierung von SEO-URLs Kommentierung entfernen
                $cat = strip_tags($this->buildCAT($categories));
                require_once(DIR_FS_INC . 'xtc_href_link_from_admin.inc.php');
                $link = xtc_href_link_from_admin('product_info.php', 'products_id=' . $products['products_id']);
                (preg_match("/\?/",$link)) ? $link .= '&' : $link .= '?';
                //-- Shopstat URLS ----//
                
                if ($products['products_image'] != ''){
                    $image = HTTP_CATALOG_SERVER . DIR_WS_CATALOG_ORIGINAL_IMAGES .$products['products_image'];
                }else{
                    $image = '';
                }
                
                $shipping_status = xtc_get_shipping_status_name($products['products_shippingtime'], 2);
                
                /*
                 / BEGINN EINF_GUNG - MICHAEL Förster -> Anzeige der Versandkosten
                 */
                
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
                    if ($products['products_weight'] <= $ZONESE_table[$i]) {
                        $shipping_cost = $ZONESE_table[$i+1];
                        break;
                    }
                }
                
                if (MODULE_SHIPPING_ZONESE_TAX_CLASS > 0) {
                    $shipping_tax = xtc_get_tax_rate(MODULE_SHIPPING_ZONESE_TAX_CLASS, 81, 0);
                }
                
                $shipping_fee = $xtPrice->xtcFormat(xtc_add_tax($shipping_cost, $shipping_tax), true, 0, true);
                $shipping_fee = str_replace('EUR', '', $shipping_fee);
                $shipping_fee = str_replace(',', '.', $shipping_fee);
                
                /*
                 // ENDE EINF_GUNG - MICHAEL Förster -> Anzeige der Versandkosten
                 */
                
                if($products['manufacturers_id'] == 80) {
                    $substr_pos = stripos($products['products_name'], '. Mit 5');
                    if($substr_pos !== false) {
                        $products['products_name'] = substr($products['products_name'], 0, $substr_pos);
                    }
                }
                
                $shop_cat = $this->buildCAT($this->getCategoriesID($products['products_id']));
                
                if(($products_price + $shipping_fee) >= 585.01) {
                    //create content
                    $schema .=
                    
                    $products['products_id'] ."\t".
                    $products['products_model'] ."\t".
                    $products['products_ean'] ."\t".
                    $products['manufacturers_name'] ."\t".
                    $products['products_name'] ."\t".
                    $products_description ."\t".
                    $image ."\t" .
                    $link."\t".
                    round($products['products_weight'],0) ."\t".
                    'DE::Standard:'.$shipping_fee ."\t".
                    number_format($products_price,2,'.',''). "\t" .
                    'neu' . "\t" .
                    //        $shipping_status . "\t" .
                    'auf lager' . "\t" .
                    substr($shop_cat, 0, strlen($shop_cat)-2) . "\n";
                }
        }
        // create File
        $fp = fopen(DIR_FS_DOCUMENT_ROOT.'export/' . $file, "w+");
        fputs($fp, $schema);
        fclose($fp);
        
        
        switch ($_POST['export']) {
            case 'yes':
                // send File to Browser
                $extension = substr($file, -3);
                $fp = fopen(DIR_FS_DOCUMENT_ROOT.'export/' . $file,"rb");
                $buffer = fread($fp, filesize(DIR_FS_DOCUMENT_ROOT.'export/' . $file));
                fclose($fp);
                header('Content-type: application/x-octet-stream');
                header('Content-disposition: attachment; filename=' . $file);
                echo $buffer;
                exit;
                
                break;
        }
        
    }
    
    function buildCAT($catID)
    {
        
        if (isset($this->CAT[$catID]))
        {
            return  $this->CAT[$catID];
        } else {
            $cat=array();
            $tmpID=$catID;
            
            while ($this->getParent($catID)!=0 || $catID!=0)
            {
                $cat_select=xtc_db_query("SELECT categories_name FROM ".TABLE_CATEGORIES_DESCRIPTION." WHERE categories_id='".$catID."' and language_id='".$_SESSION['languages_id']."'");
                $cat_data=xtc_db_fetch_array($cat_select);
                $catID=$this->getParent($catID);
                $cat[]=$cat_data['categories_name'];
                
            }
            $catStr='';
            for ($i=count($cat);$i>0;$i--)
            {
                $catStr.=$cat[$i-1].' > ';
            }
            $this->CAT[$tmpID]=$catStr;
            return $this->CAT[$tmpID];
        }
    }
    
    function getParent($catID)
    {
        if (isset($this->PARENT[$catID]))
        {
            return $this->PARENT[$catID];
        } else {
            $parent_query=xtc_db_query("SELECT parent_id FROM ".TABLE_CATEGORIES." WHERE categories_id='".$catID."'");
            $parent_data=xtc_db_fetch_array($parent_query);
            $this->PARENT[$catID]=$parent_data['parent_id'];
            return  $parent_data['parent_id'];
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
    
    function display() {
        
        $customers_statuses_array = xtc_get_customers_statuses();
        
        $campaign_array = array(array('id' => '', 'text' => TEXT_NONE));
        $campaign_query = xtc_db_query("select campaigns_name, campaigns_refID from ".TABLE_CAMPAIGNS." order by campaigns_id");
        while ($campaign = xtc_db_fetch_array($campaign_query)) {
            $campaign_array[] = array ('id' => 'refID='.$campaign['campaigns_refID'].'&', 'text' => $campaign['campaigns_name'],);
        }
        
        return array('text' =>  EXPORT_STATUS_TYPE.'<br>'.
            EXPORT_STATUS.'<br>'.
            xtc_draw_pull_down_menu('status',$customers_statuses_array, '1').'<br>'.
            CAMPAIGNS . '<br>' .
            CAMPAIGNS_DESC . '<br>' .
            xtc_draw_pull_down_menu('campaign',$campaign_array).'<br>'.
            EXPORT_TYPE.'<br>'.
            EXPORT.'<br>'.
            xtc_draw_radio_field('export', 'no',false).EXPORT_NO.'<br>'.
            xtc_draw_radio_field('export', 'yes',true).EXPORT_YES.'<br>'.
            '<br>' . xtc_button(BUTTON_EXPORT) .
            xtc_button_link(BUTTON_CANCEL, xtc_href_link(FILENAME_MODULE_EXPORT, 'set=' . $_GET['set'] . '&module=google_shopping')));
        
        
    }
    
    function check() {
        if (!isset($this->_check)) {
            $check_query = xtc_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_GOOGLE_SHOPPING_STATUS'");
            $this->_check = xtc_db_num_rows($check_query);
        }
        return $this->_check;
    }
    
    function install() {
        xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_GOOGLE_SHOPPING_FILE', 'google_shopping.txt',  '6', '1', '', now())");
        xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_GOOGLE_SHOPPING_STATUS', 'True',  '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
    }
    
    function remove() {
        xtc_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
    
    function keys() {
        return array('MODULE_GOOGLE_SHOPPING_STATUS','MODULE_GOOGLE_SHOPPING_FILE');
    }
    
}
?>