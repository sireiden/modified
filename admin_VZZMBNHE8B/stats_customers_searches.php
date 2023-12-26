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
      <div class="pageHeadingImage"><?php echo xtc_image(DIR_WS_ICONS.'heading/icon_statistic.png'); ?></div>
      <div class="pageHeading">Kundensuchen</div>              
      <div class="main pdg2">Begriffe nach denen gesucht wurde</div>
      <div id="pager" class="pager" >
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
      <table class="tableCenter">      
        <tr>
          <td class="boxCenterFull">
            <table class="tableBoxCenter collapse">
            <thead>
              <tr>
                <th>Suchbegriff</th>
                <th>Anzahl</th>
              </tr>
            <thead>
            <tbody>
              <?php
              $customers_searches_query_raw = "SELECT * FROM " . TABLE_CUSTOMERS_SEARCHES . "  ORDER BY counter DESC";
              $customers_searches_query = xtc_db_query($customers_searches_query_raw);
              while ($customers_searches = xtc_db_fetch_array($customers_searches_query)) {
              ?>                  
              <tr>
                <td><?php echo $customers_searches['keyword']; ?></td>
                <td><?php echo $customers_searches['counter']; ?></td>
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