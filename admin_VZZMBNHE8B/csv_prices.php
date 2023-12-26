<?php

require('includes/application_top.php');

$action = (isset($_GET['action']) ? $_GET['action'] : '');
$output_message = '';
$missing_models = '';
$locked_models = '';

// CSV-Export
if($action == 'export') {
    $csv_schema = 'Artikelnummer;Preis'."\n";
    
    if($_POST['products_status'] == '1') {
        $where = " WHERE p.products_status = '1' ";
    }
    else {
        $where = '';
    }
    
    $query = "SELECT p.products_model, p.products_price 
                    FROM ". TABLE_PRODUCTS." p
                    ORDER BY p.products_model ASC";
    $result=xtc_db_query($query);
    while($data=xtc_db_fetch_array($result)) {
        //Preis umrechnen Brutto oder Netto
        $price = 0;
        $brutto = 0;
        $brutto = ($data['products_price']*(100+19)/100);
        $price = round($brutto, PRICE_PRECISION);

        $csv_content .= $data['products_model'].';'.
            number_format($price,2,',','').';'.
            "\n";
    }
    
    $export_result = $csv_schema.$csv_content;
    header("Content-type: application/x-msdownload");
    header("Content-Disposition: attachment; filename=Preise.csv");
    header("Pragma: no-cache");
    header("Expires: 0");
    print "$export_result";
    exit; 
}

if($action == 'import') {
    // --- CSV Datei einlesen
    $rows = 0;
    $allowed_types = "csv";
    $products=array();
    $successful = 0;
    
    if(is_uploaded_file($_FILES["file_upload"]["tmp_name"])) {
        // Gültige Endung? ($ = Am Ende des Dateinamens) (/i = Groß- Kleinschreibung nicht berücksichtigen)
        if(preg_match("/\." . $allowed_types . "$/i", $_FILES["file_upload"]["name"])) {
            $cursor = fopen($_FILES['file_upload']['tmp_name'], "r");
            while(($data = fgetcsv($cursor, 100000000, ";")) !== FALSE) {
                $cols = count($data);
                $rows++;
                if($rows>=2) {
                    for($i = 0; $i < $cols; $i++) {
                        if($i == 0) {
                            $product['products_model'] =  $data[$i];
                        }
                        elseif($i == 1) {
                            $product['products_price'] =  $data[$i];
                        }
                    }
                    $products[]=$product;
                }
            }
            fclose($cursor);
            
            for ( $i=0; $i < count($products); $i++ ) {
                $check_model_query = "SELECT products_id, products_shippingtime FROM ". TABLE_PRODUCTS." WHERE products_model = '".$products[$i]['products_model']."'";
                $check_result = xtc_db_query($check_model_query);
                if(xtc_db_num_rows($check_result) >= 1) {
                    while($product_result = xtc_db_fetch_array($check_result)) {
                        $shipping_status = $product_result['products_shippingtime'];
                    }
                    if($shipping_status == 13) {
                        $locked_models = $products[$i]['products_model']."<br>";
                    }
                    else {
                        $price = str_replace(",", ".", $products[$i]['products_price']);
                        $price = round($price - ($price/(100+19)*19), PRICE_PRECISION);
                        
                        $sql_query = "UPDATE ". TABLE_PRODUCTS." SET
                            products_price   = '".$price."'
                            WHERE products_model = '".$products[$i]['products_model']."'";
                        $result = xtc_db_query($sql_query);
                        $successful++;
                    }
                    
                }
                else {
                    $missing_models .= $products[$i]['products_model']."<br>";
                }
            }
        }
        $output_message='<br style="clear:both"><div class="success_message">Die hochgeladene CSV-Datei wurde erfolgreich importiert. Es wurden insgesamt '.$successful.' Artikel erfolgreich importiert.</div><br>';
    }
}
require (DIR_WS_INCLUDES.'head.php');
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
        	<div class="pageHeading">Preis Update<br></div>
          <div class="main pdg2 flt-l">Import von Artikelpreisen und Export aller Artikelpreise</div>
        </div>
        <div class="clear div_box brd-none pdg2">
        <div class="main important_info" style="width:600px">
        	<strong>Struktur der CSV-Datei:</strong><br>
        	Artikelnummer;Preis
        </div>
        </div>
		<?php echo $output_message; ?>
		<?php if(!empty($missing_models)) { ?>
            <div class="clear div_box brd-none pdg2">
            	<div class="main important_info" style="width:700px">
            		<strong>Folgende Artikelnummern sind im Shop nicht vorhanden und müssen erst angelegt werden:</strong><br>
            		<?php echo $missing_models; ?>
            	</div>
            </div>		
		<?php } ?>
		<?php if(!empty($locked_models)) { ?>
            <div class="clear div_box brd-none pdg2">
            	<div class="main important_info" style="width:700px">
            		<strong>Folgende Artikelnummern wurden auf Grund des Lieferstatus nicht aktualisiert:</strong><br>
            		<?php echo $locked_models; ?>
            	</div>
            </div>		
		<?php } ?>		
		
        <div class="clear div_box brd-none">
          <div class="pageHeading mrg5">Export aller Artikelpreise</div>
          <div class="div_box mrg5">Klicken Sie auf "CSV-Datei erstellen" um eine Excel-Tabelle mit der o.g. Struktur zu erstellen.<br>
                <br><b>Bitte beachten Sie beim Erstellen der Datei folgendes: :
                <br>- Standardmäßig werden nur aktive Artikel exportiert. Bitte treffen Sie die entsprechende Auswahl, sofern alle Artikel exportiert werden sollen.<br></b>
                <?php echo xtc_draw_form('export',FILENAME_CSV_PRICES,'action=export','post','enctype="multipart/form-data"'); ?>
                <div class="mrg5">Welche Artikel sollen Exportiert werden:</div>
                <?php 
                $product_status_array = array(array('id'=>1,'text'=>'Nur aktive Artikel'), array('id'=>0,'text'=>'Alle Artikel'));
                echo draw_on_off_selection('products_status', $product_status_array, 1, 'style="width: 155px"'); ?>
                <div class="mrg5"><input type="submit" class="button" onclick="this.blur();" value="CSV-Datei erstellen"/></div>
            </form>
          </div>        
        
          <div class="pageHeading mrg5">Import von Artikelinformationen</div>
          <div class="div_box mrg5">
          	Wählen Sie eine Datei aus um diese hochzuladen<br>
                <br><b>Bitte beachten Sie folgendes beim Import:
                  <br>- Notwendige Struktur gemäß Auflistung oben
                  <br>- Es wird keine Sicherung der Datei erstellt, daher ist ein Upload nicht rückgängig zu machen (der Import findet sofort statt).
                  <br></b>
            <div class="mrg5">Datei importieren:</div>
    
            <?php echo xtc_draw_form('import',FILENAME_CSV_PRICES,'action=import','post','enctype="multipart/form-data"');
            echo '<div class="mrg5">'.xtc_draw_file_field('file_upload').'</div>';
            echo '<div class="mrg5"><input type="submit" class="button" onclick="this.blur();" value="CSV-Datei importieren"/></div>';
            ?>
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