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

if(isset($_POST['products_list']) && !empty($_POST['products_list'])) {
    $products_list = explode(',', $_POST['products_list']);
    if(count($products_list) > 1) {
        $delete_quick_edit = xtc_db_query("DELETE FROM " . TABLE_PRODUCTS_QUICK_EDIT); 
        foreach($products_list as $products_id) {
            $products_id = str_replace('.', '', $products_id);
            $products_id = str_replace(' ', '', $products_id);
            $products_id = intval($products_id);
            
            $insert_query_raw = "INSERT INTO " . TABLE_PRODUCTS_QUICK_EDIT . " SET products_id = '" . trim(xtc_db_prepare_input($products_id)) . "'";
            $insert_query = xtc_db_query($insert_query_raw);
        }
        $success_message = 'Die Artikelliste wurde erfolgreich eingelesen';
    }
    else {
        $_GET['error_message'] = 'Es wurden keine Artikel angegeben';
    }
    
}


//display per page
require (DIR_WS_INCLUDES.'head.php');
?>
<link href="includes/modules/tablesorter/theme.default.css" rel="stylesheet">
<script type="text/javascript" src="includes/modules/tablesorter/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="includes/modules/tablesorter/jquery.tablesorter.widgets.min.js"></script>
<script type="text/javascript" src="includes/modules/tablesorter/jquery.tablesorter.pager.min.js"></script>
<script type="text/javascript">
$(function() {
	// define pager options
	var pagerOptions = {
		container: $(".pager"),
		output: '{startRow} - {endRow} / {filteredRows} ({totalRows})',
		fixedHeight: false,
		removeRows: true,
		size: 500,
		// go to page selector - select dropdown that sets the current page
		cssGoto:	 '.gotoPage'
	};
	$(".tableBoxCenter")
     .tablesorter({
         widgets: ['zebra', 'filter'], 
     })
	.tablesorterPager(pagerOptions);
	console.log('test');
});
</script>
	

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
      <div class="pageHeading">Schnellbearbeitung von Artikeln</div>              
      <div class="main pdg2">Artikelbeschreibungen können hier schnell und komfortabel bearbeitet werden</div>
      <?php if(isset($_GET['error_message'])) { ?>
      <br style="clear:both"><div class="error_message"><?= $_GET['error_message'] ?></div><br>
      <?php } ?>
       <?php if(isset($success_message)) { ?>
      <br style="clear:both"><div class="success_message"><?= $success_message ?></div><br>
      <?php } ?>     
      <table class="tableCenter">
        <tr>
          <td class="main" valign="top">
  			<form style="display:inline" action="<?= xtc_href_link(FILENAME_PRODUCTS_QUICK_LIST, '', 'NONSSL') ?>" method="post">
  			Einlesen einer neuen Artikelliste für die Schnellbearbeiten (Shop-Artikelnummer müssen kommagetrennt eingegeben werden)<br>
  			<textarea id="products_list" name="products_list" wrap="soft" style="width:100%" rows="4"><?= $product['products_description'] ?></textarea>
  			<input type="submit" class="button" value="Artikel einlesen"></form>
 		 </td>
 		</tr>            
        <tr>
        	<td>
        	      <div id="pager" class="pager" style="margin-top:20px; >
                	<form name="form2">
                		<img src="includes/modules/tablesorter/first.png" class="first"/>
                		<img src="includes/modules/tablesorter/prev.png" class="prev"/>
                		<input type="text" class="pagedisplay" style="width:150px"/>
                		<img src="includes/modules/tablesorter/next.png" class="next"/>
                		<img src="includes/modules/tablesorter/last.png" class="last"/>
                		<select class="pagesize">
                			<option value="50">50</option>
                			<option value="100">100</option>
                			<option selected="selected"  value="250">250</option>
                			<option value="500">500</option>
                			<option value="750">750</option>
                			<option value="1000">1000</option>
                			<option value="2000">2000</option>
                		</select>
                	</form>
				</div>
			</td>
		</tr>
		<tr>
          <td class="boxCenterFull">
            <table class="tableBoxCenter collapse">
            <thead>
              <tr>
                <th>Artikelname</th>
                <th>Zuletzt bearbeitet</th>
              </tr>
            <thead>
            <tbody>
              <?php
              $products_quick_list_raw = "SELECT pd.products_name, pd.products_description, p.products_id, p.products_last_modified FROM " . TABLE_PRODUCTS . " p JOIN " . TABLE_PRODUCTS_DESCRIPTION. " pd ON pd.products_id = p.products_id WHERE p.products_id in (SELECT products_id FROM " . TABLE_PRODUCTS_QUICK_EDIT. ") ORDER BY p.products_id DESC";
              $products_quick_list = xtc_db_query($products_quick_list_raw);
              while ($product = xtc_db_fetch_array($products_quick_list)) {
              ?>                  
              <tr>
                <td><a href="<?= xtc_href_link(FILENAME_PRODUCTS_QUICK_EDIT, 'products_id='.$product['products_id'], 'NONSSL') ?>. '" target="_blank"><?php echo $product['products_name']; ?></a>
                <td><?= (!empty($product['products_last_modified'])) ? $product['products_last_modified'] : ''  ?></td>
              </tr>
            <?php
              }
            ?>
            </tbody>
            </table>
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