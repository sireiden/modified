<?php
/* -----------------------------------------------------------------------------------------
   $Id: sitemaporg.php 12078 2019-08-16 11:00:49Z GTB $   

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(cod.php,v 1.28 2003/02/14); www.oscommerce.com 
   (c) 2003	 nextcommerce (invoice.php,v 1.6 2003/08/24); www.nextcommerce.org
   (c) 2005	xt-commerce (sitemaporg.php,v 1.6 2003/08/24); www.xt-commerce.com
   (c) 2006	hendrik.koch@gmx.de

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');


require_once(DIR_FS_INC . 'xtc_href_link_from_admin.inc.php');
require_once(DIR_FS_INC . 'xtc_get_parent_categories.inc.php');
require_once(DIR_FS_INC . 'xtc_get_category_path.inc.php');
require_once(DIR_FS_INC . 'xtc_get_products_mo_images.inc.php');
require_once(DIR_FS_INC . 'seo_url_href_mask.php');


class sitemaporg {
  var $code, $title, $description, $enabled;

  function __construct() {
    global $order;

    $this->code = 'sitemaporg';
    $this->title = MODULE_SITEMAPORG_TEXT_TITLE;
    $this->description = MODULE_SITEMAPORG_TEXT_DESCRIPTION;
    $this->sort_order = ((defined('MODULE_SITEMAPORG_SORT_ORDER')) ? MODULE_SITEMAPORG_SORT_ORDER : '');
    $this->enabled = ((defined('MODULE_SITEMAPORG_STATUS') && MODULE_SITEMAPORG_STATUS == 'True') ? true : false);
    $this->schema = '';
    /* BOF zus�tzliche Definitionen f�r Priority and Frequency */
    $this->priority_products = '0.5';
    $this->priority_categories = '1.0';
    $this->priority_others = '0.4';
    $this->frequency_products = 'daily';
    $this->frequency_categories = 'weekly';
    $this->frequency_others = 'monthly';
  }
  
  function xml_sitemap_top() {
    $this->schema .= '<?xml version="1.0" encoding="utf-8"?>'."\n";
    $this->schema .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">'."\n";
  }
  
  function xml_sitemap_bottom() {
    $this->schema .= '</urlset>'."\n";
  }
  
  function xml_sitemap_entry($url, $lastmod = '', $priority = '0.5', $changefreq = 'weekly', $products = '') { 
    if (trim($url) == '#') return; 
    $this->schema .= "\t<url>\n";
    $this->schema .= "\t\t<loc>" . $url . "</loc>\n";
    /* BOF - Priority and ChangeFreq */
    $this->schema .= "\t\t<priority>" . $priority . "</priority>\n";
    $this->schema .= "\t\t<changefreq>" . $changefreq . "</changefreq>\n";
    /* EOF - Priority and ChangeFreq */
    if ($this->check_date($lastmod) === true) {
      $this->schema .= "\t\t<lastmod>" . date('c', strtotime($lastmod)) . "</lastmod>\n";
    }
    if (is_array($products)) {      
      if (is_file(DIR_FS_CATALOG_POPUP_IMAGES.$products['products_image'])) {
        $this->xml_image_entry(HTTP_SERVER.DIR_WS_CATALOG_POPUP_IMAGES.urlencode($products['products_image']), $products['products_name']);
      }
      $mo_images = xtc_get_products_mo_images($products['products_id']);
      if ($mo_images != false) {
        foreach ($mo_images as $img) {
          if (is_file(DIR_FS_CATALOG_POPUP_IMAGES.$img['image_name'])) {
            $this->xml_image_entry(HTTP_SERVER.DIR_WS_CATALOG_POPUP_IMAGES.urlencode($img['image_name']), $products['products_name']);
          }
        }
      }
    }
    $this->schema .= "\t</url>\n";
  }
  
  function xml_image_entry($link, $title) {
		$this->schema .= "\t\t<image:image>\n";
		$this->schema .= "\t\t\t<image:loc>".encode_utf8(decode_htmlentities($link), $_SESSION['language_charset'], true)."</image:loc>\n";
		$this->schema .= "\t\t\t<image:title><![CDATA[".encode_utf8(decode_htmlentities($title), $_SESSION['language_charset'], true)."]]></image:title>\n";
		$this->schema .= "\t\t\t<image:caption><![CDATA[".encode_utf8(decode_htmlentities($title), $_SESSION['language_charset'], true)."]]></image:caption>\n";
		$this->schema .= "\t\t</image:image>\n";
  }
  
  function process_contents() {

    $group_check = GROUP_CHECK == 'true' ? ' AND group_ids LIKE \'%c_'.$this->group_id.'_group%\' ' : '';

    $content_query = "SELECT content_id,
                             categories_id,
                             parent_id,
                             content_title,
                             content_group,
                             date_added,
                             last_modified
                        FROM ".TABLE_CONTENT_MANAGER."
                       WHERE languages_id = '".(int)$this->languages_id."'
                             ".$group_check." 
                         AND content_status = '1' 
                         AND content_meta_robots NOT LIKE '%noindex%' 
                    ORDER BY sort_order";

    $content_query = xtc_db_query($content_query);
    while ($content_data=xtc_db_fetch_array($content_query)) {
      $link = encode_htmlspecialchars(xtc_href_link_from_admin('shop_content.php', $this->url_param.'coID='.$content_data['content_group'], 'NONSSL', false));
      $date = (($this->check_date($content_data['last_modified']) === true) ? $content_data['last_modified'] : $content_data['date_added']);
      $this->xml_sitemap_entry($link, $date, $this->frequency_others, $this->priority_others);
    }
  }

  function process_manufacturers() {
    $manufacturers_query = "SELECT DISTINCT m.manufacturers_id,
                                            m.manufacturers_name 
                                       FROM ".TABLE_MANUFACTURERS." as m
                                       JOIN ".TABLE_PRODUCTS." as p 
                                            ON m.manufacturers_id = p.manufacturers_id
                                              AND p.products_status = '1'
                                   ORDER BY m.manufacturers_name";

    $manufacturers_query = xtc_db_query($manufacturers_query);
    while ($manufacturers_data=xtc_db_fetch_array($manufacturers_query)) {
      $link = encode_htmlspecialchars(xtc_href_link_from_admin('index.php', $this->url_param.'manufacturers_id='.$manufacturers_data['manufacturers_id'], 'NONSSL', false));
      $this->xml_sitemap_entry($link, '', $this->frequency_categories, $this->priority_others);     
    }
  }
    
  function process_categories() {

    $c_group_check = GROUP_CHECK == 'true' ? ' AND c.group_permission_'.$this->group_id.' = 1 ' : '';

    $categories_query = "SELECT c.categories_image,
                                c.categories_id,
                                cd.categories_name,
                                c.date_added,
                                c.last_modified
                           FROM " . TABLE_CATEGORIES . " c 
                      LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION ." cd 
                                ON c.categories_id = cd.categories_id
                          WHERE c.categories_status = '1'                      
                            AND cd.language_id = ".(int)$this->languages_id." 
                                ".$c_group_check."
                       ORDER BY c.sort_order ASC";

    $categories_query = xtc_db_query($categories_query);
    while ($categories = xtc_db_fetch_array($categories_query)) {
      $cPath = xtc_get_category_path($categories['categories_id']);
      $link = encode_htmlspecialchars(xtc_href_link_from_admin('index.php', $this->url_param.'cPath='.$cPath, 'NONSSL', false));
      $date = (($this->check_date($categories['last_modified']) === true) ? $categories['last_modified'] : $categories['date_added']);
      $this->xml_sitemap_entry($link, $date, $this->frequency_categories, $this->priority_categories);
    }
  }
  
  function process_products() {      

    $p_group_check = GROUP_CHECK == 'true' ? ' AND p.group_permission_'.$this->group_id.' = 1 ' : '';
    
    $products_query =xtc_db_query("SELECT p.products_id,
                                          p.products_last_modified,
                                          p.products_date_added,
                                          p.products_image,
                                          pd.products_name
                                     FROM " . TABLE_PRODUCTS . " p, 
                                          " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                    WHERE p.products_status = 1
                                      AND p.products_id = pd.products_id
                                          ".$p_group_check."
                                      AND pd.language_id = ".(int)$this->languages_id."
                                 ORDER BY p.products_id");

    while ($products = xtc_db_fetch_array($products_query)) {
      $link = encode_htmlspecialchars(xtc_href_link_from_admin('product_info.php', $this->url_param.'products_id='.$products['products_id'], 'NONSSL', false));
      $date = (($this->check_date($products['products_last_modified']) === true) ? $products['products_last_modified'] : $products['products_date_added']);
      $this->xml_sitemap_entry($link, $date, $this->frequency_products, $this->priority_products, $products);  
    }
  }
  
  function process_manufacturers_pages() {
      $export_query = xtc_db_query("SELECT mp.page_title,
                                           mp.manufacturers_id,
                                            m.manufacturers_name,
                                            m.last_modified,
                                            m.date_added
                                      FROM " . TABLE_MANUFACTURERS_PAGES . " mp JOIN
                                           " . TABLE_MANUFACTURERS . " m
                                      ON mp.manufacturers_id = m.manufacturers_id
                                      WHERE m.manufacturers_status = 1
                                      ORDER BY mp.sort_order");
      while($manufacturers_pages = xtc_db_fetch_array($export_query, true)) {
          $link = encode_htmlspecialchars(xtc_href_link_from_admin(seo_url_href_mask($manufacturers_pages['manufacturers_name']) . '/' .  seo_url_href_mask($manufacturers_pages['page_title']).MAN_OVERVIEW_DIVIDER.$manufacturers_pages['manufacturers_id'].PAG_DIVIDER.$manufacturers_page['page_id'].'.html', '', 'NONSSL', false));;
          $date = (($this->check_date($manufacturers_pages['last_modified']) === true) ? $manufacturers_pages['last_modified'] : $manufacturers_pages['date_added']);
          $this->xml_sitemap_entry($link, $date, $this->frequency_others, $this->priority_others);
      }
  }
  
  function process_promotions() {
      $export_query = xtc_db_query("SELECT p.name,
                                           p.id,
                                           m.manufacturers_name,
                                           p.last_modified,
                                           p.date_added
                                      FROM " . TABLE_MAIN_PROMOTIONS . " p JOIN
                                           " . TABLE_MANUFACTURERS . " m
                                      ON p.manufacturers_id = m.manufacturers_id
                                      WHERE p.status = 1 and p.image != '' order by p.sort_order ASC");
      while($promotions = xtc_db_fetch_array($export_query, true)) {
          $link = encode_htmlspecialchars(xtc_href_link_from_admin('Aktionen/' . seo_url_href_mask($promotions['manufacturers_name']) . '/' .  seo_url_href_mask($promotions['name']).'-:-'.$promotions['id'].'.html', '', 'NONSSL', false));
          $date = (($this->check_date($promotions['last_modified']) === true) ? $promotions['last_modified'] : $promotions['date_added']);
          $this->xml_sitemap_entry($link, $date, $this->frequency_others, $this->priority_others);
      }
  }
  
  function check_date($date) {
    if ($date != '' && strtotime($date) !== false && strtotime($date) > 0) {
      return true;
    }
    return false;
  }
  
  function process($file) {
    @xtc_set_time_limit(0);
    
    $this->url_param = '';
    $this->group_id = $_POST['configuration']['MODULE_SITEMAPORG_CUSTOMERS_STATUS'];
    $this->languages_code = $_SESSION['language_code'];
    $this->languages_id = $_SESSION['languages_id'];
    
    if (defined('MODULE_MULTILANG_STATUS') && MODULE_MULTILANG_STATUS == 'true') {
      $this->languages_code = $_POST['configuration']['MODULE_SITEMAPORG_LANGUAGE'];
    
      $lang_query = xtc_db_query("SELECT languages_id
                                    FROM ".TABLE_LANGUAGES."
                                   WHERE code = '".xtc_db_input($this->languages_code)."'");
      $lang = xtc_db_fetch_array($lang_query);
      $this->languages_id = $lang['languages_id'];
      
      $this->url_param = 'language='.$this->languages_code.'&';
    }
    
    $this->xml_sitemap_top();
    $this->xml_sitemap_entry(xtc_href_link_from_admin('index.php'));
    
    $this->process_contents();
    $this->process_categories();
    $this->process_products();
    $this->process_manufacturers();
    $this->process_manufacturers_pages();
    $this->process_promotions();
    
    $this->xml_sitemap_bottom();
  
    $file = $_POST['configuration']['MODULE_SITEMAPORG_FILE'];

    if ($_POST['configuration']['MODULE_SITEMAPORG_ROOT'] == 'yes' 
        && $_POST['configuration']['MODULE_SITEMAPORG_EXPORT'] == 'no'
        ) 
    {
      $filename = DIR_FS_DOCUMENT_ROOT.$file; 
    } else {
      $filename = DIR_FS_DOCUMENT_ROOT.'export/'.$file;
    }
  
    if ($_POST['configuration']['MODULE_SITEMAPORG_EXPORT'] == 'yes') { 
      $filename = $filename.'_tmp_'.time();
    }
  
    if ($_POST['configuration']['MODULE_SITEMAPORG_GZIP'] == 'yes') {
      $filename = $filename.'.gz';
      $gz = gzopen($filename,'w');
      gzwrite($gz, $this->schema);
      gzclose($gz);
      $file = $file.'.gz';
    } else {
      $fp = fopen($filename, "w");
      fputs($fp, $this->schema);
      fclose($fp);
    }
  
    switch ($_POST['configuration']['MODULE_SITEMAPORG_EXPORT']) {
      case 'yes':
        // send File to Browser
        header('Content-type: application/x-octet-stream');
        header('Content-disposition: attachment; filename=' . $file);
        readfile($filename);
        unlink($filename);
        exit;
        break;
      case 'no':
        $sitemap = HTTP_SERVER.DIR_WS_CATALOG.(($_POST['configuration']['MODULE_SITEMAPORG_ROOT']=='no') ? 'export/':'').$file;
        break;
    }
  }

  function display() {
    return array('text' => '<br />' . xtc_button(BUTTON_EXPORT) .
                            xtc_button_link(BUTTON_CANCEL, xtc_href_link(FILENAME_MODULE_EXPORT, 'set=' . $_GET['set'] . '&module=sitemaporg')));
  }

  function check() {
    if (!isset($this->_check)) {
      $check_query = xtc_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SITEMAPORG_STATUS'");
      $this->_check = xtc_db_num_rows($check_query);
    }
    return $this->_check;
  }

  function install() {
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_SITEMAPORG_FILE', 'sitemap.xml',  '6', '1', '', now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_SITEMAPORG_STATUS', 'True',  '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('MODULE_SITEMAPORG_CUSTOMERS_STATUS', '1',  '6', '1', 'xtc_cfg_pull_down_customers_status_list(', 'xtc_get_customers_status_name', now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_SITEMAPORG_LANGUAGE', '".DEFAULT_LANGUAGE."',  '6', '1', 'xtc_cfg_pull_down_language_code(', now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_SITEMAPORG_ROOT', 'no',  '6', '1', 'xtc_cfg_select_option(array(\'yes\', \'no\'), ', now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_SITEMAPORG_GZIP', 'no',  '6', '1', 'xtc_cfg_select_option(array(\'yes\', \'no\'), ', now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_SITEMAPORG_EXPORT', 'no',  '6', '1', 'xtc_cfg_select_option(array(\'yes\', \'no\'), ', now())");
  }

  function remove() {
    xtc_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key LIKE 'MODULE_SITEMAPORG_%'");
  }

  function keys() {
    $keys = array(
      'MODULE_SITEMAPORG_STATUS',
      'MODULE_SITEMAPORG_FILE',
      'MODULE_SITEMAPORG_CUSTOMERS_STATUS',
      ((defined('MODULE_MULTILANG_STATUS') && MODULE_MULTILANG_STATUS == 'true') ? 'MODULE_SITEMAPORG_LANGUAGE' : ''),
      'MODULE_SITEMAPORG_ROOT',
      'MODULE_SITEMAPORG_GZIP',
      'MODULE_SITEMAPORG_EXPORT'
    );
    $keys = array_values(array_filter($keys));
    
    return $keys;
  }
  
}
?>