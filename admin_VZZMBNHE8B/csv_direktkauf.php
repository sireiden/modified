<?php

require('includes/application_top.php');

$action = (isset($_GET['action']) ? $_GET['action'] : '');
$output_message = '';

// CSV-Export
if($action == 'export') {
    $csv_schema = 'Model;Direktkauf;Preisspanne;Hersteller;Artikelname;Preis (brutto); Skonto;Fulfillment'."\n";
    
    if($_POST['products_status'] == '1') {
        $where = " WHERE p.products_status = '1' ";
    }
    else {
        $where = '';
    }
    
    $query = "SELECT p.products_model, p.products_price, pf.products_directbuy, pf.products_directbuy_range, pf.products_directbuy_fulfillment, pf.products_cash_discount, pd.products_name, m.manufacturers_name 
                    FROM ". TABLE_PRODUCTS." p
                    JOIN ". TABLE_PRODUCTS_FIELDS." pf ON (p.products_id = pf.products_id)  
                    JOIN ". TABLE_PRODUCTS_DESCRIPTION." pd ON (p.products_id = pd.products_id)  
                    LEFT JOIN ". TABLE_MANUFACTURERS." m ON (m.manufacturers_id = p.manufacturers_id)      
                    ".$where." ORDER BY m.manufacturers_name ASC";
    
    $result=xtc_db_query($query);
    while($data=xtc_db_fetch_array($result)) {
        if($data['products_directbuy'] == 1) {
            $directbuy = 'Ja';
        }
        elseif($data['products_directbuy'] == -1) {
            $directbuy = 'Nein';
        }
        else {
            $directbuy = 'Nicht festgelegt';
        }
        
        //Preis umrechnen Brutto oder Netto
        $price = 0;
        $brutto = 0;
        $brutto = ($data['products_price']*(100+19)/100);
        $price = round($brutto, PRICE_PRECISION);
        
        $csv_content .=  $data['products_model'].';'.
            $directbuy.';'.
            number_format($data['products_directbuy_range'],2,',','').';'.
            $data['manufacturers_name'].';'.
            $data['products_name'].';'.
            number_format($price,2,',','').';'.
            $data['products_cash_discount'].';'.
            $data['products_directbuy_fulfillment'].';'.
            "\n";
    }
    
    $export_result = $csv_schema.$csv_content;
    header("Content-type: application/x-msdownload");
    header("Content-Disposition: attachment; filename=Direktkauf.csv");
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
                            $product['products_directbuy'] =  $data[$i];
                        }
                        elseif($i == 2) {
                            $product['products_directbuy_range'] =  $data[$i];
                        }
                        elseif($i == 3) {
                            $product['products_directbuy_fulfillment'] =  $data[$i];
                        } 
                    }
                    $products[]=$product;
                }
            }
            fclose($cursor);
            
            for ( $i=0; $i < count($products); $i++ ) {
                if($products[$i]['products_model'] != '') {
                    if($products[$i]['products_directbuy'] == 'Ja') $directbuy = 1;
                    elseif($products[$i]['products_directbuy'] == 'Nein') $directbuy = -1;
                    else $directbuy = 0;
                    
                    $directbuy_range = str_replace(',', '.', $products[$i]['products_directbuy_range']);
                    $directbuy_fulfillment = $products[$i]['products_directbuy_fulfillment'];
                    
                    $sql_query = "UPDATE ". TABLE_PRODUCTS_FIELDS." SET 
                                    products_directbuy = '". $directbuy."', products_directbuy_range = '".$directbuy_range."', products_directbuy_fulfillment = '".$directbuy_fulfillment."'
         				          WHERE products_id IN (SELECT products_id FROM ". TABLE_PRODUCTS." WHERE products_model = '".$products[$i]['products_model']."')";
                    $result = xtc_db_query($sql_query);
                }
                else {
                    $error .= '<br>Es wurden nicht für alle Artikel eine Artikelnummer übergeben';
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
        	<div class="pageHeading">CSV Direktkauf<br></div>
          <div class="main pdg2 flt-l">Import und Export von Idealo Direktkauf-Informationen</div>
        </div>
		<?php echo $output_message; ?>
        <div class="clear div_box brd-none">
          <div class="pageHeading mrg5">Export von Direktkauf-Informationen	</div>
          <div class="div_box mrg5">Klicken Sie auf "CSV-Datei erstellen" um eine Excel-Tabelle aller Artikel mit Informationen zum Direktkauf zu erstellen.<br>
                <br><b>Bitte beachten Sie beim Erstellen der Datei folgendes: :
                <br>- Standardmäßig werden nur aktive Artikel exportiert. Bitte treffen Sie die entsprechende Auswahl, sofern alle Artikel exportiert werden sollen.<br></b>
                <?php echo xtc_draw_form('export',FILENAME_CSV_DIREKTKAUF,'action=export','post','enctype="multipart/form-data"'); ?>
                <div class="mrg5">Welche Artikel sollen Exportiert werden:</div>
                <?php 
                $product_status_array = array(array('id'=>1,'text'=>'Nur aktive Artikel'), array('id'=>0,'text'=>'Alle Artikel'));
                echo draw_on_off_selection('products_status', $product_status_array, 1, 'style="width: 155px"'); ?>
                <div class="mrg5"><input type="submit" class="button" onclick="this.blur();" value="CSV-Datei erstellen"/></div>
            </form>
          </div>        
        
          <div class="pageHeading mrg5">Import von Direktkauf-Informationen</div>
          <div class="div_box mrg5">
          	Wählen Sie eine Datei aus um diese hochzuladen<br>
                <br><b>Bitte beachten Sie folgendes beim Import:
                  <br>- Notwendige Struktur: Model;Direktkauf;Preisspanne;Fulfillment
                  <br>- Es wird keine Sicherung der Datei erstellt, daher ist ein Upload nicht rückgängig zu machen.
                  <br></b>
            <div class="mrg5">Datei importieren:</div>
    
            <?php echo xtc_draw_form('import',FILENAME_CSV_DIREKTKAUF,'action=import','post','enctype="multipart/form-data"');
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