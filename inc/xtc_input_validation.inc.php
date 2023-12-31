<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_input_validation.inc.php 12277 2019-10-14 15:50:58Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(german.php,v 1.119 2003/05/19); www.oscommerce.com
   (c) 2003 nextcommerce (german.php,v 1.25 2003/08/25); www.nextcommerce.org
   (c) 2006 XT-Commerce
   
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/


  function xtc_input_validation($var, $type, $replace_char = '') {

    switch($type) {
      case 'cPath':
        $replace_param = '/[^0-9_]/';
        break;
      case 'int':
        $replace_param = '/[^0-9]/';
        break;
      case 'char':
        $replace_param = '/[^a-zA-Z]/';
        break;
      case 'lang':
        $replace_param = '/[^a-zA-Z_]/';
        break;
      case 'products_id':
        $replace_param = '/[^0-9\{\}]/';
        break;
      case 'amount':
        $var = str_replace(",", ".", $var);
        $replace_param = '/[^0-9\.]/';
        break;
    }

    $val = preg_replace($replace_param, $replace_char, $var);

    return $val;
  }
?>