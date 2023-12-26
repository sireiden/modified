<?php
/* -----------------------------------------------------------------------------------------
   $Id: display_vvcodes.php 11470 2019-01-25 11:34:23Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2006 XT-Commerce

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  require ('includes/application_top.php');

  require_once(DIR_WS_CLASSES.'modified_captcha.php');
  
  $mod_captcha = $_mod_captcha_class::getInstance();
  $mod_captcha->output();
?>