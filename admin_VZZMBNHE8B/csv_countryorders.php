<?php

require('includes/application_top.php');

$action = (isset($_GET['action']) ? $_GET['action'] : '');

// CSV-Export
if($action == 'export') {
    $csv_schema = 'Land;Bestellungen;Gesamtsumme;Summe UST; Summe Versandkosten'."\n";
    
    if($_POST['startdate'] != '' && $_POST['enddate'] != '') {
        $startdate_array = explode(".", $_POST['startdate']);
        $startdate = $startdate_array[2]."-".$startdate_array[1]."-".$startdate_array[0]." 00:00:00";
        
        $enddate_array = explode(".", $_POST['enddate']);
        $enddate = $enddate_array[2]."-".$enddate_array[1]."-".$enddate_array[0]." 23:59:59";
        
        $timeframe_where = " o.date_purchased >= '".$startdate."' AND o.date_purchased <= '".$enddate."' "; 
    }
    else {
        $timeframe_where = "true";
    } 
    
    $query = "SELECT COUNT(*) as Anzahl, o.delivery_country,
                SUM(ot.value) as Gesamtsumme,
                SUM(ot2.value)  AS Ustsumme,
                SUM(ot3.value)  AS Versandsumme
                FROM ".TABLE_ORDERS." o
                JOIN ".TABLE_ORDERS_TOTAL." ot on ot.orders_id = o.orders_id AND ot.class='ot_total'
                JOIN ".TABLE_ORDERS_TOTAL." ot2 on ot2.orders_id = o.orders_id AND ot2.class='ot_tax'
                JOIN ".TABLE_ORDERS_TOTAL." ot3 on ot3.orders_id = o.orders_id AND ot3.class='ot_shipping'
                WHERE ".$timeframe_where."
                GROUP BY o.delivery_country";
    
    $result=xtc_db_query($query);
    while($data=xtc_db_fetch_array($result)) {
        $csv_content .=  $data['delivery_country'].';'.
            $data['Anzahl'].';'.
            number_format(round($data['Gesamtsumme'], 2) ,2,',','.').';'.
            number_format(round($data['Ustsumme'], 2) ,2,',','.').';'.
            number_format(round($data['Versandsumme'], 2) ,2,',','.').';'.
            "\n";  
    }
    
    $export_result = $csv_schema.$csv_content;
    header("Content-type: application/x-msdownload");
    header("Content-Disposition: attachment; filename=Laenderbestellungen.csv");
    header("Pragma: no-cache");
    header("Expires: 0");
    print "$export_result";  
    exit;
}

require (DIR_WS_INCLUDES.'head.php');
//jQueryDatepicker
require (DIR_WS_INCLUDES.'javascript/jQueryDateTimePicker/datepicker.js.php');
?>
<script type="text/javascript" src="includes/general.js"></script>
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
        <div class="pageHeadingImage"><?php echo xtc_image(DIR_WS_ICONS.'heading/icon_content.png'); ?></div>
        <div class="flt-l">
        	<div class="pageHeading">CSV - Umsatz nach Ländern
			<br></div>
          <div class="main pdg2 flt-l">
			Auswertung von Bestellungen nach Ländern           
          </div>
        </div>
        <div class="clear div_box brd-none">
          <div class="pageHeading mrg5">Klicken Sie auf "CSV-Datei erstellen" um eine Auswertung von Bestellungen nach Ländern zu erhalten</div>
          <div class="div_box mrg5"><b>Bitte beachten Sie beim Erstellen der Datei folgendes:<br>
				- Sofern eins von beiden Datumsfeldern leer ist, werden alle Bestellungen ausgewertet.<br>
				- Es werden alle Bestellungen exportiert, unabhängig davon ob bereits eine Rechnung erstellt wurde.<br></b>
                <?php echo xtc_draw_form('export',FILENAME_CSV_COUNTRYORDERS,'action=export','post','enctype="multipart/form-data"'); ?>
                <div class="mrg5">  			
                Startdatum: 
  				<?php echo xtc_draw_input_field('startdate', date('d.m.Y', strtotime('-1 day')) ,'class="datepicker" onfocus="this.value=\'\'" autocomplete="off" style="width: 155px" '); ?>
  				&nbsp;<br><br>Enddatum:&nbsp;&nbsp;
  				<?php echo xtc_draw_input_field('enddate', date('d.m.Y', time()) ,'class="datepicker" onfocus="this.value=\'\'" autocomplete="off" style="width: 155px"'); ?>
  				&nbsp;<br><br>
  				Format: dd.mm.yyyy (z.B. 01.01.2019)
                
            <div class="mrg5"><input type="submit" class="button" onclick="this.blur();" value="CSV-Datei erstellen"/></div>
            </form>
          </div>        
        </div>
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