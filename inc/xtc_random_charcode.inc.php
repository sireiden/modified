<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_random_charcode.inc.php 12438 2019-12-02 15:52:46Z GTB $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2004 XT-Commerce
   -----------------------------------------------------------------------------------------
   by Guido Winger for XT:Commerce

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  // include needed functions
  require_once(DIR_FS_INC . 'xtc_rand.inc.php');

  // build to generate a random charcode
  function xtc_random_charcode($length) {
    $arraysize = 28; 
    $chars = array('A','B','C','D','E','F','G','H','K','M','N','P','Q','R','S','T','U','V','W','X','Y','Z','2','3','4','5','6','8','9');

    $code = '';
    for ($i = 1; $i <= $length; $i++) {
      $j = floor(xtc_rand(0,$arraysize));
      $code .= $chars[$j];
    }
    
    return  $code;
  }
?>