<?php

require('includes/application_top.php');

// include needed functions
require_once (DIR_FS_INC.'xtc_get_category_tree.inc.php');
require_once (DIR_FS_INC.'xtc_get_parent_categories.inc.php');
require_once (DIR_FS_INC.'xtc_wysiwyg.inc.php');
require_once (DIR_WS_CLASSES.'megamenu.php');

$confirm_save_entry = ' onclick="ButtonClicked(this);"';
$confirm_submit = defined('CONFIRM_SAVE_ENTRY') && CONFIRM_SAVE_ENTRY == 'true' ? ' onsubmit="return confirmSubmit(\'\',\''. SAVE_ENTRY .'\',this)"' : '';

$mfunc = new megamenu();


if(isset($_POST['action'])) {
    if($_POST['action'] == 'insert_entry') {
        $megamenu_id = $mfunc->insert_megamenu_main($_POST, 'insert');
        xtc_redirect(xtc_href_link(FILENAME_MEGAMENU_EDIT, 'megamenu_id='.$megamenu_id));
        
    }
    if($_POST['action'] == 'update_entry') {
        $megamenu_id = $mfunc->insert_megamenu_main($_POST, 'update');
        xtc_redirect(xtc_href_link(FILENAME_MEGAMENU_EDIT, 'megamenu_id='.$megamenu_id));
    }
    
    
}




if(isset($_GET['megamenu_id'])) {
    $new_entry = false;
    $megamenu_query = xtc_db_query("select * from " . TABLE_MEGAMENU . " where megamenu_id = '" . (int)$_GET['megamenu_id'] . "' ");
    $megamenu = xtc_db_fetch_array($megamenu_query);
    
    $mInfo = new objectInfo($megamenu);
}
else if(isset($_GET['new']) && $_GET['new'] == 1) {
    $new_entry = true;
    $mInfo = new objectInfo(array());
}



// Basic definitions
$megamenu_status_array = array(array('id' => '1','text'=> 'Aktiviert'),
    array('id' => '0','text'=>'Deaktiviert'),
);
$megamenu_type_array = array(array('id' => 'category','text'=> 'Kategorie'),
    array('id' => 'text','text'=>'Text mit Link'),
);


function xtc_get_megamenu_mo_images($megamenu_id = '') {
    $mo_query = "SELECT *
   	               from " . TABLE_MEGAMENU_IMAGES . "
   	              WHERE megamenu_id = '" . (int)$megamenu_id ."'
   	           ORDER BY image_nr";
    $megamenu_mo_images_query = xtDBquery($mo_query);
    
    $results = array();
    while ($row = xtc_db_fetch_array($megamenu_mo_images_query,true)) {
        $results[($row['image_nr']-1)] = $row;
    }
    if (sizeof($results)>0) {
        return $results;
    } else {
        return false;
    }
}


//display per page
require (DIR_WS_INCLUDES.'head.php');
?>
<script type="text/javascript">
$(document).ready(function() {
	$(".megamenu_type").on("change", function() {
		if($(this).is(':checked')) {
    		if($(this).val() == 'category') {
    			$(this).closest('table').find('.megamenu_category').show();
    			$(this).closest('table').find('.megamenu_text').hide();		
    		}
    		else {
    			$(this).closest('table').find('.megamenu_category').hide();
    			$(this).closest('table').find('.megamenu_text').show();	
    		}
		}

	});
	$(".megamenu_type").change();
});
</script>
<?php 
echo PHP_EOL . (!function_exists('editorJSLink') ? '<script type="text/javascript" src="includes/modules/fckeditor/fckeditor.js"></script>' : '') . PHP_EOL;
echo xtc_wysiwyg('megamenu_text', 'de', '');
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
      <div class="pageHeadingImage"><?php echo xtc_image(DIR_WS_ICONS.'heading/icon_categories.png'); ?></div>
      <div class="pageHeading">Megamenü</div>              
      <div class="main pdg2"><?php ($new_entry === true) ? 'Neue Megamenü-Hauptkategorie anlegen' : 'Kategorie bearbeiten'?></div>
      <?php if(isset($_GET['error_message'])) { ?>
      <br style="clear:both"><div class="error_message"><?= $_GET['error_message'] ?></div><br>
      <?php } ?>
       <?php if(isset($success_message)) { ?>
      <br style="clear:both"><div class="success_message"><?= $success_message ?></div><br>
       <br style="clear:both">
      <?php } ?>
      <?php 
      echo xtc_draw_form('update_megamenu', FILENAME_MEGAMENU_EDIT, '', 'post', 'enctype="multipart/form-data"' . $confirm_submit);
      echo xtc_draw_hidden_field('action', ($new_entry === true ? 'insert_entry' : 'update_entry'));
      echo xtc_draw_hidden_field('megamenu_id', ($new_entry === true ? 0 : $mInfo->megamenu_id));
      ?>
        <div class="main div_header">Basisdaten</div>   
        <div class="clear div_box mrg5">
            <div>
              <table class="tableInput border0">
                <tr>
                  <td><span class="main">Name:</td>
                  <td><span class="main"><?php echo xtc_draw_input_field('megamenu_name', $mInfo->megamenu_name ,'style="width: 465px"'); ?></span></td>
                </tr>               
                <tr>
                  <td class="main" style="width:260px">Status aktiv:</td>
                  <td class="main"><?php echo draw_on_off_selection(($new_entry === true ? 'megamenu_status' : 'megamenu_status['.$mInfo->megamenu_id.']'), $megamenu_status_array, ($mInfo->megamenu_status == '0' ? false : true), 'style="width: 155px"'); ?></td>
                </tr>
                <tr>
                  <td class="main">Sortierreihenfolge:</td>
                  <td class="main"><?php echo xtc_draw_input_field('sort_order', $mInfo->sort_order, 'style="width: 155px"'); ?></td>
                </tr>
              </table>
              <table class="tableInput border0">
                <tr>
                  <td class="main" style="width:260px">&nbsp;</td>
                  <td class="main">&nbsp;</td>
                </tr>
                <tr>
                  <td><span class="main">Art des Eintrags:</td>
                  <td><span class="main"><?php echo draw_on_off_selection(($new_entry === true ? 'megamenu_type' : 'megamenu_type['.$mInfo->megamenu_id.']'), $megamenu_type_array, ($mInfo->megamenu_type == 'text' ? 'text' : 'category'), 'style="width: 155px" class="megamenu_type"'); ?></span></td>
                </tr>
                <tr class="megamenu_category">
                  <td><span class="main">Kategorie:</td>
                  <td><span class="main"><?php echo xtc_draw_pull_down_menu('categories_id', xtc_get_category_tree(), $mInfo->categories_id) ?></span></td>
                </tr>
                <tr class="megamenu_text">
                  <td><span class="main">Title:</td>
                  <td><span class="main"><?php echo xtc_draw_input_field('megamenu_title', $mInfo->megamenu_title ,'style="width: 465px"'); ?></span></td>
                </tr>
                <tr class="megamenu_text">
                  <td><span class="main">Link:</td>
                  <td><span class="main"><?php echo xtc_draw_input_field('megamenu_link', $mInfo->megamenu_link ,'style="width: 465px"'); ?></span></td>
                </tr>                                
              </table>
            </div>
            <div style="clear:both;"></div> 
            <div class="main" style="margin:20px 5px;float:right;">
              <?php echo xtc_draw_hidden_field('date_added', (($mInfo->date_added) ? $mInfo->date_added : date('Y-m-d'))) . xtc_draw_hidden_field('parent_id', $mInfo->parent_id); ?>
              <input type="submit" class="button" name="cat_save" value="<?php echo BUTTON_SAVE; ?>" style="cursor:pointer" <?php echo $confirm_save_entry;?>>&nbsp;&nbsp;
              <?php
              ?>
              <a class="button" onclick="this.blur()" href="<?php echo xtc_href_link(FILENAME_MEGAMENU); ?>"><?php echo BUTTON_CANCEL ; ?></a>
            </div>
            <div style="clear:both;"></div>  
        </div>
            <?php // Bildbereich ?>
            <div class="main div_header">Bilder</div>
            <div class="div_box">
			  <?php 
			  $mo_images = xtc_get_megamenu_mo_images($mInfo->megamenu_id);
			  for ($i = 0; $i < 10; $i ++) { ?>            
                  <table class="tableConfig borderall">
                    <tr>
                      <td class="dataTableConfig col-left">Bild für Slider:</td>
             		  <td class="dataTableConfig col-middle"><?php echo (isset($mo_images[$i]['image_name']) ? $mo_images[$i]['image_name'] : ''); ?></td>
              		  <td class="dataTableConfig col-right"<?php echo $rowspan;?>><?php echo (isset($mo_images[$i]['image_name']) ? xtc_info_image('megamenu/'.$mo_images[$i]['image_name'], 'Image '. ($i +1), '','','class="thumbnail-productsimage"') : xtc_draw_separator('pixel_trans.gif', PRODUCT_IMAGE_THUMBNAIL_WIDTH, 10)); ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableConfig col-left">Bild für Slider:</td>
              		  <td class="dataTableConfig col-middle"><?php echo xtc_draw_file_field('mo_pics_'.$i, false, 'class="imgupload"'); ?></td>      
                    </tr>    
                    <tr>
                      <td class="dataTableConfig col-left">Löschen:</td>
                      <td class="dataTableConfig col-middle"><?php echo xtc_draw_checkbox_field('del_mo_pic[]', (isset($mo_images[$i]['image_name']) ? $mo_images[$i]['image_name'] : '')); ?></td>      
                    </tr>
                  </table>
                  <?php echo xtc_draw_hidden_field('products_previous_image_'. ($i +1), (isset($mo_images[$i]['image_name']) ? $mo_images[$i]['image_name'] : '')); ?>
              <?php } ?>
           </div>
           <?php // Textbereich ?>
           <div class="main div_header">Text unter Slider</div>
           <div class="clear div_box mrg5">
             <table class="tableInput border0">
              <tr>
                <td class="main" colspan="2"><?php echo xtc_draw_textarea_field('megamenu_text', 'soft', '100', '25', ((isset($mInfo->megamenu_text)) ? stripslashes($mInfo->megamenu_text) : ''), 'style="width:99%"'); ?></td>
              </tr>
            </table>          
           </div>
           <div class="clear div_box mrg5">
               <div style="padding:5px;">
                  <div style="text-align:right; margin-top:10px;">
                    <?php
                    if ($new_entry === true) {
                        echo xtc_draw_hidden_field('date_added', (($mInfo->date_added) ? $mInfo->date_added : date('Y-m-d'))) . xtc_draw_hidden_field('parent_id', $mInfo->parent_id);
                    } else {
                        echo xtc_draw_hidden_field('last_modified', (($mInfo->last_modified) ? $mInfo->last_modified->products_last_modified : date('Y-m-d')));
                    }
                    echo '<input type="submit" class="button" value="'.BUTTON_SAVE.'" '.$confirm_save_entry.'/>';
                    echo '&nbsp;&nbsp;<a class="button" href="' . ((isset($_GET['origin']) && $_GET['origin'] != '') ? xtc_href_link(basename($_GET['origin']), 'pID=' . (int)$_GET['pID'].$catfunc->page_parameter) : xtc_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . $catfunc->page_parameter . ((isset($_GET['pID']) && $_GET['pID']!='') ? '&pID=' . (int)$_GET['pID'] : ''))) . '">' . BUTTON_CANCEL . '</a>';
                    ?>
                  </div>   
               </div>  
			</div>         
        </form>
        <?php if($new_entry !== true) { ?>
            <div class="main div_header">Unterkategorien</div>   
            <div class="clear div_box mrg5">
                 <div>
                  <table class="tableInput border0">
                    <tr>
                      <td class="main" style="width:260px">Status aktiv:</td>
                      <td class="main"><?php echo draw_on_off_selection('status[]', $megamenu_status_array, ($cInfo->categories_status == '0' ? false : true), 'style="width: 155px"'); ?></td>
                      <td class="main">Sortierreihenfolge:</td>
                      <td class="main"><?php echo xtc_draw_input_field('sort_order', $cInfo->sort_order, 'style="width: 155px"'); ?></td>
                    </tr>
                    <tr>
                      <td><span class="main">Art des Eintrags:</td>
                      <td colspan="3"><span class="main"><?php echo draw_on_off_selection('type[2]', $megamenu_type_array, ($cInfo->categories_status == 'text' ? 'text' : 'category'), 'style="width: 155px" class="main_type"'); ?></span></td>
                    </tr>
                    <tr class="main_category">
                      <td><span class="main">Kategorie:</td>
                      <td colspan="3"><span class="main"><?php echo xtc_draw_pull_down_menu('cPath', xtc_get_category_tree()) ?></span></td>
                    </tr>
                    <tr class="main_text">
                      <td><span class="main">Text:</td>
                      <td colspan="3"><span class="main"><?php echo xtc_draw_input_field('products_shippinginfo', $products_fields['products_shippinginfo'] ,'style="width: 465px"'); ?></span></td>
                    </tr>
                    <tr class="main_text">
                      <td><span class="main">Link:</td>
                      <td colspan="3"><span class="main"><?php echo xtc_draw_input_field('products_shippinginfo', $products_fields['products_shippinginfo'] ,'style="width: 465px"'); ?></span></td>
                    </tr>                                
                  </table>
                </div>       
            </div>
             
       <?php } ?>       

                  
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