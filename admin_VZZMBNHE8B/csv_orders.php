<?php

require('includes/application_top.php');

$action = (isset($_GET['action']) ? $_GET['action'] : '');
$output_message = '';

// CSV-Export
if($action == 'export') {
    if($_POST['order_mode'] == 'shop') {
        $csv_schema = 'Best.Datum;Best-Nr.;Kommission;Hersteller;Anzahl/Menge;Artikel;VK brutto;Tr.K;NN/Paypal;Skonto;Idealo Direktkauf ID; Gesamtsumme Bestellung;Zahl-Art;Lieferland;Bestell. Liefer.;Sachbearb. ;AB;Kunden-; Hersteller- und Liefer-Hinweise;;;;;Link zur Bewertungsabgabe'."\n";
    }
    if($_POST['order_mode'] == 'kuechen') {
        $csv_schema = 'Best.Datum;Best-Nr.;Kommission;Hersteller;Anzahl/Menge;Artikel;VK brutto;Tr.K;NN/Paypal;Skonto;Idealo Direktkauf ID; Gesamtsumme BestellungZahl-Art;Lieferland;Bestell. Liefer.;Sachbearb. ;AB;Kunden-, Hersteller- und Liefer-Hinweise;Wareneingang;Versandtag;RE-Datum'."\n";
    }
    
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
        if($_POST['order_mode'] == 'shop' && $data['customers_status'] == 5) {
            continue;
        }
        
        if($_POST['order_mode'] == 'kuechen' && $data['customers_status'] != 5) {
            continue;
        }
        
        $i = 1;
        //Bestelldatum
        $order_date = get_order_date($data['date_purchased']);
        //Zahlungsart
        $payment_type = get_order_payment_type($data['payment_class']);
        
        //Kommission
        $kommission = '';
        if($data['customers_company'] != '') {
            $kommission = $data['customers_company'].' ';
        }
        if($data['customers_lastname'] != '') {
            $kommission .= $data['customers_lastname'];
        }
        else {
            $kommission .= get_order_billing_lastname($data['customers_id']);
        }
        
        //Transportkosten
        $tr_costs = get_tr_costs($data['orders_id']);
        // Erstellung Exportdokumente (mit in den Transportkosten
        $export_costs = get_export_costs($data['orders_id']);
        $tr_costs += $export_costs;
        
        $tr_costs = number_format($tr_costs,2,',','.');
        $tr_costs .= '';
        //Transaktionskosten
        $ot_costs = get_ot_costs($data['orders_id'], $data['payment_class']);
        $ot_costs = number_format($ot_costs,2,',','.');
        $ot_costs .= '';
        // Skonto
        $cash_discount = get_cash_discount($data['orders_id']);
        $cash_discount = number_format(abs($cash_discount),2,',','.');
        $cash_discount .= '';
        // Gutscheine
        $gutscheine = get_gutscheine($data['orders_id']);
        $gutscheine = number_format(abs($gutscheine),2,',','.');
        $gutscheine .= '';  
        // Idealo ID
        $idealo_order_id = get_directbuy_id($data['orders_id']);
        // Gesamtsumme Bestellung
        $order_total_value = get_order_value($data['orders_id']);
        $order_total_value = number_format(abs($order_total_value),2,',','.');
        $order_total_value .= '';  
        
        
        //Falls Zahlung mit Kreditkarte
        if($data['payment_class']== 'eos_cc_iframe') {
            $pos_1 = stripos($data['customers_name'], '(');
            $pos_2 = stripos($data['customers_name'], ')');
            $cc_information = substr($data['customers_name'],$pos_1+1, $pos_2 );
            $cc_information = substr($cc_information, 0, -1);
        }
        else {
            $cc_information = '';
        }
        
        if($_POST['order_mode'] == 'shop' && $data['contact_bewertung'] != 1 && $data['orders_id'] > 430864) {
            $kein_link = ';;;;;;Opt-Out KEIN LINK';
            $kein_link1 = ';;Opt-Out KEIN LINK';
        }
        else {
            $kein_link = '';
            $kein_link1 = '';
        }
        
        $products_result =  export_ordered_products($data['orders_id']);
        while($products_data=xtc_db_fetch_array($products_result)) {
            $manufacturer=get_order_manufacturer($products_data['products_id']);
            $attributes = get_order_products_attribute($products_data['orders_products_id']);
            
            if($_POST['order_mode'] == 'shop') {
                // Erste Bestelposition (Zusatzdaten mit schreiben)
                if($i == 1) {
                    $csv_content .=  $order_date.';'.
                        $data['orders_id'].(($data['customers_status'] == 5) ? '-'.$data['kvnummer'] : '').';'.
                        $kommission.';'.
                        $manufacturer.';'.
                        $products_data['products_quantity'].';'.
                        $products_data['products_name'].' '.$attributes.';'.
                        number_format($products_data['final_price'],2,',','.').';'.
                        $tr_costs.';'.
                        $ot_costs.';'.
                        $cash_discount.';'.
                        $idealo_order_id.';'.
                        $order_total_value.';'.   
                        $payment_type.';'.
                        $data['delivery_country'].';'.
                        ';;;'.
                        ';;;'.
                        ';'.
                        $kein_link1.
                        "\n";
                }
                else {
                    $csv_content .= ';'.
                        ';'.
                        ';'.
                        $manufacturer.';'.
                        $products_data['products_quantity'].';'.
                        $products_data['products_name'].' '.$attributes.';'.
                        number_format($products_data['final_price'],2,',','.').';'.
                        '0,00;'.
                        '0,00;'.
                        ';'.
                        $idealo_order_id.';'.
                        $order_total_value.';'.   
                        $payment_type.';'.
                        $data['delivery_country'].';'.
                        ';'.
                        ';'.
                        ';'.
                        $kein_link.
                        "\n";
                }
            }
            
            if($_POST['order_mode'] == 'kuechen') {
                // Erste Bestelposition (Zusatzdaten mit schreiben)
                if($i == 1) {
                    $csv_content .=  $order_date.';'.
                        $data['orders_id'].(($data['customers_status'] == 5) ? '-'.$data['kvnummer'] : '').';'.
                        $kommission.';'.
                        $manufacturer.';'.
                        $products_data['products_quantity'].';'.
                        $products_data['products_name'].' '.$attributes.';'.
                        number_format($products_data['final_price'],2,',','.').';'.
                        $tr_costs.';'.
                        $ot_costs.';'.
                        $cash_discount.';'.
                        $idealo_order_id.';'.
                        $order_total_value.';'.  
                        $payment_type.';'.
                        $data['delivery_country'].';'.
                        ';;;'.
                        ';;;;'.
                        "\n";
                }
                else {
                    $csv_content .= ';'.
                        ';'.
                        ';'.
                        $manufacturer.';'.
                        $products_data['products_quantity'].';'.
                        $products_data['products_name'].' '.$attributes.';'.
                        number_format($products_data['final_price'],2,',','.').';'.
                        '0,00;'.
                        '0,00;'.
                        ';'.
                        $idealo_order_id.';'.
                        $order_total_value.';'.  
                        $payment_type.';'.
                        $data['delivery_country'].';'.
                        ';;;'.
                        ';;;;'.
                        "\n";
                }
            }
            
            $i++;
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
        	<div class="pageHeading">Export von Bestellungen für die Auftragsverwaltung
			<br></div>
          <div class="main pdg2 flt-l">
        	<?php 
        	if($_GET['mode'] == 'kuechen') {
        	    echo  'CSV aller Bestellungen der Kundengruppe ascasa Küchenstudio für die Auftragsverwaltung erstellen';
        	}
        	else {
        	    echo 'CSV aller Shopbestellungen (ohne Kundengruppe ascasa Küchenstudio) für die Auftragsverwaltung erstellen';
        	}
        	?>          
          </div>
        </div>
        <div class="clear div_box brd-none">
          <div class="pageHeading mrg5">Klicken Sie auf "CSV-Datei erstellen" um die Datei für die Auftragsverwaltung zu erstellen</div>
          <div class="div_box mrg5"><b>Bitte beachten Sie Beim erstellen der Datei folgendes:<br>
				- Sofern nur ein Startdatum gesetzt ist, werden alle Auftr&auml;ge  ab diesem Datum exportiert<br>
				- Sofern kein Datum oder nur ein Enddatum gesetzt ist, werden alle Auftr&auml;ge des aktuellen Datums exportiert<br>
				- Es werden alle Bestellung exportiert, auch die f&uuml;r die schon eine Rechnung erstellt wurde<br></b>
                <?php echo xtc_draw_form('export',FILENAME_CSV_ORDERS,'action=export','post','enctype="multipart/form-data"'); ?>
                <?php echo xtc_draw_hidden_field('order_mode', (isset($_GET['mode']) && $_GET['mode'] == 'kuechen') ? 'kuechen' : 'shop')?>
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