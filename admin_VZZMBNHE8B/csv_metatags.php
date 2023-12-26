<?php

require('includes/application_top.php');

$action = (isset($_GET['action']) ? $_GET['action'] : '');
$output_message = '';

// CSV-Export
if($action == 'export') {
    $csv_schema = 'Shop-Artikelnummer;Zusatzbegriffe für Suche;Meta Title;Meta Description; Meta Keywords'."\n";
    
    $query = "SELECT products_id, products_keywords, products_meta_title, products_meta_keywords, products_meta_description FROM ". TABLE_PRODUCTS_DESCRIPTION." WHERE language_id = 2 ORDER BY products_id ASC";
    $result=xtc_db_query($query);
    while($data=xtc_db_fetch_array($result)) {
        $csv_content .=  $data['products_id'].';'.
            $data['products_keywords'].';'.
            $data['products_meta_title'].';'.
            $data['products_meta_description'].';'.
            $data['products_meta_keywords'].';'.
            "\n";
    }
    
    $export_result = $csv_schema.$csv_content;
    header("Content-type: application/x-msdownload");
    header("Content-Disposition: attachment; filename=Metatags.csv");
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
                            $product['products_id'] =  $data[$i];
                        }
                        elseif($i == 1) {
                            $product['products_keywords'] =  $data[$i];
                        }
                        elseif($i == 2) {
                            $product['products_meta_title'] =  $data[$i];
                        }
                        elseif($i == 3) {
                            $product['products_meta_description'] =  $data[$i];
                        }
                        elseif($i == 4) {
                            $product['products_meta_keywords'] =  $data[$i];
                        }
                    }
                    $products[]=$product;
                }
            }
            fclose($cursor);
            
            for ( $i=0; $i < count($products); $i++ ) {
                if($products[$i]['products_id'] != '') {
                    $sql_query = "UPDATE ". TABLE_PRODUCTS_DESCRIPTION." SET products_keywords = '". $products[$i]['products_keywords']."',
         											   products_meta_title = '". $products[$i]['products_meta_title']."',
         											   products_meta_description = '". $products[$i]['products_meta_description']."',
         											   products_meta_keywords = '". $products[$i]['products_meta_keywords']."'
         				WHERE products_id = '".$products[$i]['products_id']."'";
                    $result = xtc_db_query($sql_query);
                }
            }
        }
        $output_message='<div class="success_message">Die hochgeladene CSV-Datei wurde erfolgreich importiert.</div>';
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
        	<div class="pageHeading">CSV Metatags<br></div>
          <div class="main pdg2 flt-l">Import und Export von Metatags</div>
        </div>
		<?php echo $output_message; ?>
        <div class="clear div_box brd-none">
          <div class="pageHeading mrg5">Export von Metatags</div>
          <div class="div_box mrg5">Klicken Sie auf "CSV-Datei erstellen" um eine Excel-Tabelle allen Metatags sowie den Zusatzbegriffen für die Suche zu erstellen
            <?php echo xtc_draw_form('export',FILENAME_CSV_METATAGS,'action=export','post','enctype="multipart/form-data"'); ?>
            <div class="mrg5"><input type="submit" class="button" onclick="this.blur();" value="CSV-Datei erstellen"/></div>
            </form>
          </div>        
        
          <div class="pageHeading mrg5">Import von Metatags</div>
          <div class="div_box mrg5">
          	Wählen Sie eine Datei aus um diese hochzuladen<br>
                <br><b>Bitte beachten Sie folgendes beim Import:
                  <br>- Notwendige Struktur: Shop-Artikelnummer;Zusatzbegriffe für Suche;Meta Title;Meta Description, Meta Keywords
                  <br>- Es wird keine Sicherung der Datei erstellt, daher ist ein Upload nicht rückgängig zu machen.
                  <br></b>
            <div class="mrg5">Datei importieren:</div>
    
            <?php echo xtc_draw_form('import',FILENAME_CSV_METATAGS,'action=import','post','enctype="multipart/form-data"');
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