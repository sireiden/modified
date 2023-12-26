<?php
/* --------------------------------------------------------------
 $Id: stats_products_viewed.php 10119 2016-07-20 10:50:40Z GTB $
 
 XT-Commerce - community made shopping
 http://www.xt-commerce.com
 
 Copyright (c) 2003 XT-Commerce
 --------------------------------------------------------------
 based on:
 (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
 (c) 2002-2003 osCommerce(stats_products_viewed.php,v 1.27 2003/01/29); www.oscommerce.com
 (c) 2003	 nextcommerce (stats_products_viewed.php,v 1.9 2003/08/18); www.nextcommerce.org
 
 Released under the GNU General Public License
 --------------------------------------------------------------*/

require('includes/application_top.php');

// include needed functions
require_once (DIR_FS_INC.'xtc_get_category_path.inc.php');
require_once (DIR_FS_INC.'xtc_get_parent_categories.inc.php');
require_once (DIR_FS_INC.'xtc_wysiwyg.inc.php');

if(isset($_POST['action']) && $_POST['action']=='Speichern' && $_POST['products_id'] != '')  {
    $product_id = $_POST['products_id'];
    $products_update_query_raw = "UPDATE " .TABLE_PRODUCTS_DESCRIPTION. " SET products_description = '". trim(stripslashes(xtc_db_prepare_input($_POST['products_description']))) ."' WHERE products_id ='" .xtc_db_prepare_input($_POST['products_id'])."'";
    echo xtc_db_affected_rows;
    $products_update_raw = xtc_db_query($products_update_query_raw);
    echo xtc_db_affected_rows();
    if(xtc_db_affected_rows() != 0) {
        $products_last_modified_query_raw = "UPDATE " . TABLE_PRODUCTS  .  " SET products_last_modified = now()  WHERE products_id ='" .xtc_db_prepare_input($_POST['products_id'])."'"; 
        $products_last_modified_query = xtc_db_query($products_last_modified_query_raw);
        $message = 'Die Änderung wurde erfolgreich gespeichert';
    }
    
    $products_query = xtc_db_query("SELECT * FROM " . TABLE_PRODUCTS_DESCRIPTION . " WHERE language_id = 2 AND products_id = '" .xtc_db_prepare_input($_POST['products_id'])."'");
    $product = xtc_db_fetch_array($products_query);
}
elseif($_GET['products_id'] != '') {
    $products_query = xtc_db_query("SELECT * FROM " . TABLE_PRODUCTS_DESCRIPTION . " WHERE language_id = 2 AND products_id = '" .xtc_db_prepare_input($_GET['products_id'])."'");
    $product = xtc_db_fetch_array($products_query);
}
else {
    $link = xtc_href_link(FILENAME_PRODUCTS_QUICK_LIST, 'error_message=Ungültiger Seitenaufruf', 'NONSSL');
    header("Location: ".$link);
    exit;
}


//display per page
require (DIR_WS_INCLUDES.'head.php');
?>
<link href="includes/modules/tablesorter/theme.default.css" rel="stylesheet">
<script type="text/javascript" src="includes/general.js"></script>
<?php
// Include WYSIWYG if is activated
if (USE_WYSIWYG == 'true') {
	// generate editor 
	echo PHP_EOL . (!function_exists('editorJSLink') ? '<script type="text/javascript" src="includes/modules/fckeditor/fckeditor.js"></script>' : '') . PHP_EOL;
	echo xtc_wysiwyg('products_quick_edit', 'de', '');
}
?>
</head>
<body>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table class="tableBody">
  <tr>
    <?php //left_navigation
    if (USE_ADMIN_TOP_MENU == 'false') {
      echo '<td class="columnLeft2">'.PHP_EOL;
      echo '<!-- left_navigation //-->'.PHP_EOL;       
      require_once(DIR_WS_INCLUDES . 'column_left.php');
      echo '<!-- left_navigation eof //-->'.PHP_EOL; 
      echo '</td>'.PHP_EOL;      
    }
    ?>
    <!-- body_text //-->
    
    <td class="boxCenter">
      <div class="pageHeadingImage"><?php echo xtc_image(DIR_WS_ICONS.'heading/icon_categories.png'); ?></div>
      <div class="pageHeading"><?= $product['products_name'] ?></div>              
      <div class="main pdg2">Schnellbearbeitung des Artikels</div>
            <?php if(!empty($message)) { ?>
      <br style="clear:both"><div class="success_message"><?= $message ?></div><br>
      <?php } ?>
	</div>
      <table class="tableCenter">      
        <tr>
          <td class="boxCenterFull">
            <form style="display:inline" action="<?= xtc_href_link(FILENAME_PRODUCTS_QUICK_EDIT, '', 'NONSSL') ?>" method="post">
            <input type="hidden" name="products_id" value="<?=$product['products_id'] ?>" /> 
            <span style="margin-right:105px;float:right">
              <a style="background-color:#000000;color:#ffffff;width:90%;padding: 6px 15px 6px 15px;" target="_blank" href="<?= xtc_catalog_href_link('product_info.php', 'products_id=' . $product['products_id']) ?>">Live-Ansicht</a>
              <a style="margin-left:24px;background-color:#000000;color:#ffffff;width:90%;padding: 6px 15px 6px 15px;" target="_blank" href="<?= xtc_href_link(FILENAME_CATEGORIES, "pID=".$product['products_id']."&action=new_product", 'NONSSL') ?>">Detailbearbeitung</a>
			  <a style="margin-left:24px;background-color:#000000;color:#ffffff;width:90%;padding: 6px 15px 6px 15px;" target="_blank" href="<?= xtc_href_link(FILENAME_PRODUCTS_QUICK_LIST, '', 'NONSSL') ?>">Zurück zur Übersicht</a>              
            </span>
            <p>&nbsp;</p>
            <p><textarea id="products_description" name="products_description" wrap="soft" cols="103" rows="30"><?= $product['products_description'] ?></textarea></p>
            <p><input type="submit" name="action" class="form_submit" style="background-color:#000000;color:#ffffff;width:100%;padding: 6px 15px 6px 15px;" value="Speichern" />
            </form>
          </td>
        </tr>
      </table>           
    </td>
    <!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>