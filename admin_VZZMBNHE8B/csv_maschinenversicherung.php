<?php

require('includes/application_top.php');

$action = (isset($_GET['action']) ? $_GET['action'] : '');

// CSV-Export
if($action == 'export') {
    $csv_schema = 'Vorname;Name;Strasse;Wohnort;Postleitzahl;Lieferland;E-Mail Adresse;Bestelldatum;RG-Datum;Artikelpreis;Hersteller;Artikelbezeichnung;Artikelanzahl;Bestellnummer'."\n";
    
    if($_POST['startdate'] != '') {
        $startdate_array = explode(".", $_POST['startdate']);
        $startdate = $startdate_array[2]."-".$startdate_array[1]."-".$startdate_array[0]." 00:00:00";
        
        if($_POST['enddate'] == '') {
            $timeframe_where = " WHERE date_purchased >= '$startdate' ";
        }
        else {
            $enddate_array = explode(".", $_POST['enddate']);
            $enddate = $enddate_array[2]."-".$enddate_array[1]."-".$enddate_array[0]." 23:59:59";
            $timeframe_where = " WHERE (date_purchased >= '$startdate' AND date_purchased <= '$enddate')";
        }
    }
    else {
        $startdate_day = date('Y-m-d', time());
        $startdate = $startdate_day." 00:00:00";
        $enddate = $startdate_day." 23:59:59";
        $timeframe_where = " WHERE (date_purchased >= '$startdate' AND date_purchased <= '$enddate')";
    } 
    
    $result = export_orders($timeframe_where);
    while($data=xtc_db_fetch_array($result)) {
        //Bestelldatum
        $order_date = get_order_date($data['date_purchased']);
        //Rechnungsdatum
        if($data['invoice_date'] != '' && $data['invoice_date'] != 0) {
            $invoice_date = date('d.m.Y', $data['invoice_date']);
        }
        else {
            $invoice_date = '';
        }
        
        $products_result =  export_ordered_products($data['orders_id']);
        while($products_data=xtc_db_fetch_array($products_result)) {
            $manufacturer=get_order_manufacturer($products_data['products_id']);
            $attributes = get_order_products_attribute($products_data['orders_products_id']);
            
            $csv_content .= $data['delivery_firstname'].';'.
                            $data['delivery_lastname'].';'.
                            $data['delivery_street_address'].';'.
                            $data['delivery_city'].';'.
                            $data['delivery_postcode'].';'.
                            $data['delivery_country'].';'.
                            $data['customers_email_address'].';'.
                            $order_date.';'.
                            $invoice_date.';'.
                            number_format($products_data['products_price'],2,',','.').';'.
                            $manufacturer.';'.
                            $products_data['products_quantity'].';'.
                            $products_data['products_name'].' '.$attributes.';'.
                            $data['orders_id']. ';'.
                            "\n";
            $manufacturer='';
        }
    }
    
    $export_result = $csv_schema.$csv_content;
    header("Content-type: application/x-msdownload");
    header("Content-Disposition: attachment; filename=Auftragsdurchlauf.csv");
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
        	<div class="pageHeading">Export von Bestellungen für die Maschinenversicherung
			<br></div>
          <div class="main pdg2 flt-l">
			CSV aller Shopbestellungen (inkl. aller Kundengruppen) für die Maschinenversicherung erstellen          
          </div>
        </div>
        <div class="clear div_box brd-none">
          <div class="pageHeading mrg5">Klicken Sie auf "CSV-Datei erstellen" um die Datei für die Maschinenversicherung zu erstellen</div>
          <div class="div_box mrg5"><b>Bitte beachten Sie Beim erstellen der Datei folgendes:<br>
				- Sofern nur ein Startdatum gesetzt ist, werden alle Auftr&auml;ge  ab diesem Datum exportiert<br>
				- Sofern kein Datum oder nur ein Enddatum gesetzt ist, werden alle Auftr&auml;ge des aktuellen Datums exportiert<br>
				- Es werden alle Bestellung exportiert, auch die f&uuml;r die schon eine Rechnung erstellt wurde<br></b>
                <?php echo xtc_draw_form('export',FILENAME_CSV_MASCHINENVERSICHERUNG,'action=export','post','enctype="multipart/form-data"'); ?>
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