<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_activate_banners.inc.php 12439 2019-12-02 17:40:51Z GTB $

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(banner.php,v 1.10 2003/02/11); www.oscommerce.com
   (c) 2003     nextcommerce (xtc_activate_banners.inc.php,v 1.3 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  // Auto activate banners
  function xtc_activate_banners() {
    $banners_query = xtc_db_query("SELECT banners_id, 
                                          date_scheduled 
                                     FROM " . TABLE_BANNERS . " 
                                    WHERE date_scheduled != ''");
    if (xtc_db_num_rows($banners_query)) {
      while ($banners = xtc_db_fetch_array($banners_query)) {
        if (date('Y-m-d H:i:s') >= $banners['date_scheduled']) {
          xtc_set_banner_status($banners['banners_id'], '1');
        }
      }
    }
  }
?>