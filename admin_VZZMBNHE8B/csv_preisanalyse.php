<?php

require('includes/application_top.php');

$action = (isset($_GET['action']) ? $_GET['action'] : '');
$output_message = '';

// CSV-Export
if($action == 'export') {
    $csv_schema = 'Artikelnummer;Artikelgewicht;Lieferstatus;Versandkommentare;Skonto bei Vorkasse'."\n";
    
    if($_POST['products_status'] == '1') {
        $where = " WHERE p.products_status = '1' ";
    }
    else {
        $where = '';
    }
    
    $query = "SELECT p.products_model, p.products_weight, p.products_shippingtime, pf.products_shippinginfo, pf.products_cash_discount 
                    FROM ". TABLE_PRODUCTS." p
                    JOIN ". TABLE_PRODUCTS_FIELDS." pf ON (p.products_id = pf.products_id)  
                    ".$where." ORDER BY p.products_id DESC";
    $result=xtc_db_query($query);
    while($data=xtc_db_fetch_array($result)) {
        $csv_content .=  $data['products_model'].';'.
            $data['products_weight'].';'.
            $data['products_shippingtime'].';'.
            $data['products_shippinginfo'].';'.
            $data['products_cash_discount'].';'.
            "\n";
    }
    
    $export_result = $csv_schema.$csv_content;
    header("Content-type: application/x-msdownload");
    header("Content-Disposition: attachment; filename=CSV-Preisanalyse.csv");
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
    $error = '';
    if(is_uploaded_file($_FILES["file_upload"]["tmp_name"])) {
        // G�ltige Endung? ($ = Am Ende des Dateinamens) (/i = Gro�- Kleinschreibung nicht ber�cksichtigen)
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
                            $product['products_weight'] =  $data[$i];
                        }
                        elseif($i == 2) {
                            $product['products_shippingtime'] =  $data[$i];
                        }
                        elseif($i == 3) {
                            $product['products_shippinginfo'] =  $data[$i];
                        }
                        elseif($i == 4) {
                            $product['products_cash_discount'] =  $data[$i];
                        }

                    }
                    $products[]=$product;
                }
            }
            fclose($cursor);
            
            for ( $i=0; $i < count($products); $i++ ) {
                if($products[$i]['products_model'] != '') {
                    if(!empty($products[$i]['products_shippingtime'])) {
                        $shipping_time = "products_shippingtime   = '".$products[$i]['products_shippingtime']."',";
                    }
                    else {
                        $shipping_time = '';
                    }
                    
                    $sql_query = "UPDATE ". TABLE_PRODUCTS." SET
                                    ".$shipping_time."                        
                                    products_weight   = '".$products[$i]['products_weight']."'
                                  WHERE products_model = '".$products[$i]['products_model']."'";
                    
                    $result = xtc_db_query($sql_query);
                    
                    $sql_query = "UPDATE ". TABLE_PRODUCTS_FIELDS." SET 
                                    products_shippinginfo = '". $products[$i]['products_shippinginfo']."',
                                    products_cash_discount = '". $products[$i]['products_cash_discount']."'
         				          WHERE products_id IN (SELECT products_id FROM ". TABLE_PRODUCTS." WHERE products_model = '".$products[$i]['products_model']."')";
                     $result = xtc_db_query($sql_query);
                }
                else {
                    $error .= '<br>Es wurden nicht f�r alle Artikel eine Artikelnummer �bergeben';
                }
            }
        }
        $output_message='<br style="clear:both"><div class="success_message">Die hochgeladene CSV-Datei wurde erfolgreich importiert.'.$error.'</div><br>';
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
        	<div class="pageHeading">CSV Preisanalyse<br></div>
          <div class="main pdg2 flt-l">Export von Artikeln f�r die Preisanalyse und m�glicher Export</div>
        </div>
        <div class="clear div_box brd-none pdg2">
        <div class="main important_info" style="width:600px">
        	<strong>Struktur der CSV-Datei:</strong><br>
        	Artikelnummer;Artikelgewicht;Lieferstatus;Versandkommentare;Skonto bei Vorkasse
        </div>
        </div>
		<?php echo $output_message; ?>
        <div class="clear div_box brd-none">
          <div class="pageHeading mrg5">Export von Artikelinformationen f�r die Preisanalyse</div>
          <div class="div_box mrg5">Klicken Sie auf "CSV-Datei erstellen" um eine Excel-Tabelle mit der o.g. Struktur zu erstellen.<br>
                <br><b>Bitte beachten Sie beim Erstellen der Datei folgendes: :
                <br>- Standardm��ig werden nur aktive Artikel exportiert. Bitte treffen Sie die entsprechende Auswahl, sofern alle Artikel exportiert werden sollen.<br></b>
                <?php echo xtc_draw_form('export',FILENAME_CSV_PREISANALYSE,'action=export','post','enctype="multipart/form-data"'); ?>
                <div class="mrg5">Welche Artikel sollen Exportiert werden:</div>
                <?php 
                $product_status_array = array(array('id'=>1,'text'=>'Nur aktive Artikel'), array('id'=>0,'text'=>'Alle Artikel'));
                echo draw_on_off_selection('products_status', $product_status_array, 1, 'style="width: 155px"'); ?>
                <div class="mrg5"><input type="submit" class="button" onclick="this.blur();" value="CSV-Datei erstellen"/></div>
            </form>
          </div>        
        
          <div class="pageHeading mrg5">Import von Artikelinformationen</div>
          <div class="div_box mrg5">
          	W�hlen Sie eine Datei aus um diese hochzuladen<br>
                <br><b>Bitte beachten Sie folgendes beim Import:
                  <br>- Notwendige Struktur gem�� Auflistung oben
                  <br>- Es wird keine Sicherung der Datei erstellt, daher ist ein Upload nicht r�ckg�ngig zu machen.
                  <br></b>
            <div class="mrg5">Datei importieren:</div>
    
            <?php echo xtc_draw_form('import',FILENAME_CSV_PREISANALYSE,'action=import','post','enctype="multipart/form-data"');
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