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

require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();


function _cmp ($a, $b) {
    return ( $a['wert'] <= $b['wert'] ) ? +1 : -1;
}  
  
require (DIR_WS_INCLUDES.'head.php');
//jQueryDatepicker
require (DIR_WS_INCLUDES.'javascript/jQueryDateTimePicker/datepicker.js.php');
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
      <div class="pageHeadingImage"><?php echo xtc_image(DIR_WS_ICONS.'heading/icon_statistic.png'); ?></div>
      <div class="pageHeading">Bestellungen nach Herkunft</div>              
      <div class="main pdg2">Statistik</div>
      <table class="tableCenter">      
        <tr>
          <td class="main" valign="top">
  			<?php echo xtc_draw_form('stats_referer',FILENAME_STATS_REFERER,'','get'); ?>
  			Startdatum: 
  			<?php echo xtc_draw_input_field('startdate', isset($_GET['startdate']) ? $_GET['startdate'] : '' ,'class="datepicker" autocomplete="off" style="width: 155px"'); ?>
  			&nbsp;Enddatum
  			<?php echo xtc_draw_input_field('enddate', isset($_GET['enddate']) ? $_GET['enddate'] : '' ,'class="datepicker" autocomplete="off" style="width: 155px"'); ?>
  			&nbsp;
  			Format: dd.mm.yyyy (z.B. 01.01.2019)
  			<input type="submit" class="button" value="Statistik filtern"></form>
 		 </td>
 		</tr>
 		<tr>
          <td class="boxCenterLeft">
            <table class="tableBoxCenter collapse">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" style="width:15%" >Anzahl Bestellungen</td>
                <td class="dataTableHeadingContent">Herkunft</td>
                <td class="dataTableHeadingContent txta-c" style="width:20%">Umsatz</td>
              </tr>
              <?php
              $referer_array = array();
              if($_GET['startdate'] != '') {
                $startdate_array = explode(".", $_GET['startdate']);
                $startdate = $startdate_array[2]."-".$startdate_array[1]."-".$startdate_array[0]." 00:00:00";
                if($_GET['enddate'] == '') {
                    $timeframe_where = " WHERE o.date_purchased >= '$startdate' ";
                }
                else {
                    $enddate_array = explode(".", $_GET['enddate']);
                    $enddate = $enddate_array[2]."-".$enddate_array[1]."-".$enddate_array[0]." 23:59:59";
                    $timeframe_where = " WHERE (o.date_purchased >= '$startdate' AND o.date_purchased <= '$enddate')";
                }
             }
             else {
                $timeframe_where = '';
             }
             
             $customers_query_raw = "select o.referer, op.value as ordersum from " . TABLE_ORDERS . " o JOIN " . TABLE_ORDERS_TOTAL . " op on (o.orders_id = op.orders_id AND  op.class = 'ot_total') ".$timeframe_where." order by o.orders_id DESC";
             $customers_query = xtc_db_query($customers_query_raw);
             while ($customers = xtc_db_fetch_array($customers_query)) {
                 if(!empty($customers['referer'])) {
                     if(strpos($customers['referer'], '/') !== false) {
                         $referer = substr($customers['referer'], 0, strpos($customers['referer'], '/'));
                     }
                     else {
                         $referer = $customers['referer'];
                     }
                     
                     $referer = trim($referer);
                    
                     $ref_array = array_reverse((explode(".",$referer)));
                     if(isset($ref_array[2]) && $ref_array[2] != 'www') {
                         $referer = $ref_array[2].'.'.$ref_array[1].'.'.$ref_array[0];
                     }
                     else {
                         $referer = $ref_array[1].'.'.$ref_array[0];
                     }
                     
                     if(!array_key_exists($referer, $referer_array)) {
                         $referer_array[$referer]['name'] = $referer;
                         $referer_array[$referer]['wert'] = $customers['ordersum'];
                         $referer_array[$referer]['anzahl'] = 1;
                     }
                     else {
                         $referer_array[$referer]['wert'] += $customers['ordersum'];
                         $referer_array[$referer]['anzahl']++;
                     }
                 }
                 $rows++;
                 if (strlen($rows) < 2) {
                     $rows = '0' . $rows;
                 }
             }
             usort($referer_array, "_cmp");
             foreach($referer_array as $refers) {
              ?>                  
              <tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='pointer'" onmouseout="this.className='dataTableRow'" >
                <td class="dataTableContent"><?php echo $refers['anzahl']; ?></td>
                <td class="dataTableContent"><?php echo $refers['name']; ?></td>
                <td class="dataTableContent style="text-align:right" align="right"><?php echo $currencies->format($refers['wert']); ?>&nbsp;</td>
              </tr>
            <?php
              }
            ?>
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