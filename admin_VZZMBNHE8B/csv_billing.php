<?php

require('includes/application_top.php');

$action = (isset($_GET['action']) ? $_GET['action'] : '');

// CSV-Export
if($action == 'export') {
    if($_POST['csv_format'] == 'normal') {
        $csv_schema = 'WKZ;Rechnungsbetrag(incl. MwSt);Transportkosten;Transaktionskosten;Gutscheine;Mehrwertsteuerbetrag;Bestellnummer;Rechnungsdatum; ; ; ;Nachname;Vorname;Firmenbezeichnung;Lieferland;Rechnungsland;Steuersatz;USt-ID-Nr.;Zahlungsart;Elavon-Referenznummer;Status'."\n";
    }
    if($_POST['csv_format'] == 'datev') {
        $csv_schema = 'Währung;VorzBetrag;RechNr;Belegdatum;InterneRechNr;LieferantName;LieferantOrt;LieferantKonto;BU;Konto;Kontobezeichnung;Ware/Leistung;Fällig_am;gezahlt_am;UStSatz;USt-IdNr.Lieferant;Kunden-Nr.;KOST1;KOST2;KOSTmenge;Kurs;Skonto;Nachricht;Skto_Fällig_am;BankKonto;BankBlz;Bankname;BankIban;BankBic'."\n";
    }
    
    if($_POST['startdate'] != '' && $_POST['enddate'] != '') {
        $startdate_array = explode(".", $_POST['startdate']);
        $enddate_array = explode(".", $_POST['enddate']);
        
        if($_POST['invoice_status'] == 1) {
            $startdate = mktime (0,0,0, $startdate_array[1], $startdate_array[0], $startdate_array[2]);
            $enddate = mktime (23,59,0, $enddate_array[1], $enddate_array[0], $enddate_array[2]);
            $timeframe_where = " AND (invoice_date >= '".$startdate."' AND invoice_date <= '".$enddate."')";
            $invoice_where = " invoice_date != ''";
        }
        if($_POST['invoice_status'] == 2) {
            $startdate = date('Y-m-d 00:00:00', mktime (0,0,0, $startdate_array[1], $startdate_array[0], $startdate_array[2]));  
            $enddate = date('Y-m-d 23:59:00',  mktime (23,59,0, $enddate_array[1], $enddate_array[0], $enddate_array[2]));
            $timeframe_where = " (date_purchased >= '".$startdate."' AND date_purchased <= '".$enddate."')";
            $invoice_where = ""; 
        }
    }
    else {
        if($_POST['invoice_status'] == 1) {
            $invoice_where = " invoice_date != ''";
            $timeframe_where = "";
        }
        
        if($_POST['invoice_status'] == 2) {
            $invoice_where = " ";
            $timeframe_where = "true";
        } 
    }
    
    $result = export_orders_billing($timeframe_where, $invoice_where);
    while($data=xtc_db_fetch_array($result)) {

        //Rechnungsdatum
        if(($data['invoice_date'] != '') AND ($data['invoice_date'] != 0)) {
            $invoice_date = date('d.m.Y', $data['invoice_date']);
        }
        else {
            $invoice_date = '';
        }
       
        // Bestellsumme
        $order_value = get_order_value($data['orders_id']);
        $order_value = number_format($order_value,2,',','.');
        $order_value .= '';
        
        if($_POST['csv_format'] == 'normal') {
            // Transportkosten
            $shipping_value = get_tr_costs($data['orders_id']);
            // Zusätzlich auch Erstellung der Exportdokumente
            $export_costs = get_export_costs($data['orders_id']);
            $shipping_value += $export_costs;
            $shipping_value = number_format($shipping_value,2,',','.');
            $shipping_value .= '';
            
            // MwST
            $tax_value = get_order_tax($data['orders_id']);
            $tax_value = number_format($tax_value,2,',','.');
            $tax_value .= '';
            
            // Transaktionskosten
            $fee_value = get_order_fee($data['orders_id']);
            $fee_value = number_format($fee_value,2,',','.');
            $fee_value .= '';
            // Transaktionskosten
            $gutschein_value = get_gutscheine($data['orders_id']);
            $gutschein_value = number_format(abs($gutschein_value),2,',','.');
            $gutschein_value .= ''; 
            
            // Steuer
            $tax = get_tax_value($data['orders_id']); 
            
            // Nachname
            if($data['billing_lastname'] == '') {
                $lastname = get_order_billing_lastname($data['customers_id']);
            }
            else {
                $lastname = $data['billing_lastname'];
            }
            
            // Vorname
            if($data['billing_firstname'] == '') {
                $firstname = get_order_billing_firstname($data['customers_id']);
            }
            else {
                $firstname = $data['billing_firstname'];
            } 
            
            //Zahlungsart
            $payment_type = get_order_payment_type($data['payment_class']);
            $referenzID = ''; 
        }
        
        // CSV schreiben
        if($_POST['csv_format'] == 'normal') {
            $csv_content .=  ';'.
                $order_value.';'.
                $shipping_value.';'.
                $fee_value.';'.
                $gutschein_value.';'.
                $tax_value.';'.
                $data['orders_id'].(($data['customers_status'] == 5) ? '-'.$data['kvnummer'] : '').';'.
                $invoice_date.';'.
                ';'.
                ';'.
                ';'.
                $lastname.';'.
                $firstname.';'.
                $data['billing_company'] .';'.
                $data['delivery_country'].';'.
                $data['billing_country'].';'.
                $tax.';'.
                $data['customers_vat_id'].';'.
                $payment_type.';'.
                $referenzID.';'.
                $data['orders_status_name'].';'.
                "\n";
                
                set_csv_exported($data['orders_id']); 
        }
        
        if($_POST['csv_format'] == 'datev') {
            $csv_content .=  'EUR;'.
                '+'.$order_value.';'.
                $data['orders_id'].';'.
                $invoice_date.';'.
                ';'.
                ';'.
                ';'.
                ';'.
                ';'.
                ';'.
                ';'.
                ';'.
                ';'.
                ';'.
                '19;'.
                ';'.
                ';'.
                ';'.
                ';'.
                ';'.
                ';'.
                ';'.
                ';'.
                ';'.
                ';'.
                ';'.
                ';'.
                ';'.
                "\n";
        }
    }
    
    $export_result = $csv_schema.$csv_content;
    header("Content-type: application/x-msdownload");
    header("Content-Disposition: attachment; filename=Bestellungen.csv");
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
        	<div class="pageHeading">Export von Bestellungen für die Buchhaltung
			<br></div>
          <div class="main pdg2 flt-l">
        	CSV-Export aller Bestellungen für die Buchhaltung (reguläres oder Datev-Format)        
          </div>
        </div>
        <div class="clear div_box brd-none">
          <div class="pageHeading mrg5">Klicken Sie auf "CSV-Datei erstellen" um die Datei für die Buchhaltung zu erstellen</div>
          <div class="div_box mrg5"><b>Bitte beachten Sie Beim erstellen der Datei folgendes:<br>
				- Sofern eins von beiden Datumsfeldern leer ist, werden alle Bestellungen exportiert<br>
				- Es kann ausgewählt werden, ob alle Bestellungen exportiert werden sollen oder lediglich die, für die bereits eine reguläre Rechnung erstellt wurde<br>
				- Es kann ausgewählt werden, ob der Export im regulären oder im Datev-Format erfolgen soll<br></b><br><br>
                <?php echo xtc_draw_form('export',FILENAME_CSV_BILLING,'action=export','post','enctype="multipart/form-data"'); ?>
                <div class="mrg5">  			
                Startdatum: 
  				<?php echo xtc_draw_input_field('startdate', date('d.m.Y', strtotime('-1 day')) ,'class="datepicker" onfocus="this.value=\'\'" autocomplete="off" style="width: 155px" '); ?>
  				&nbsp;<br><br>Enddatum:&nbsp;&nbsp;
  				<?php echo xtc_draw_input_field('enddate', date('d.m.Y', time()) ,'class="datepicker" onfocus="this.value=\'\'" autocomplete="off" style="width: 155px"'); ?>
  				&nbsp;<br><br>
  				Welche Bestellungen sollen exportiert werden?<br>
  				<?php 
                $invoice_status_array = array(array('id'=>1,'text'=>'Rechnung bereits erstellt'), array('id'=>2,'text'=>'Alle Bestellungen'));
                echo draw_on_off_selection('invoice_status', $invoice_status_array, 1, 'style="width: 155px"'); ?>
                &nbsp;<br><br>
               <div class="mrg5"> In welchem Format soll der Export erstellt werden?<br>
  				<?php 
                $csv_format_array = array(array('id'=>'normal','text'=>'Reguläres Format'), array('id'=>'datev','text'=>'DATEV-Format'));
                echo draw_on_off_selection('csv_format', $csv_format_array, 'normal', 'style="width: 155px"'); ?>
                &nbsp;<br><br>                
                
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