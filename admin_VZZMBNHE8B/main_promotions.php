<?php
/* --------------------------------------------------------------
 * Verwaltung von Aktionen
 * Aktionen sind individuelle Seiten und bilden den Slider auf der Startseite
 * 
 */
require('includes/application_top.php');

switch ($_GET['action']) {
    case 'insert':
    case 'save':
        $id = xtc_db_prepare_input($_GET['pID']);
        $sql_data_array = array(	
            'name' => xtc_db_prepare_input($_POST['name']),
            'status' => xtc_db_prepare_input($_POST['status']),
            'sort_order' => xtc_db_prepare_input($_POST['sort_order']),
            'description' => xtc_db_prepare_input(remove_word_code($_POST['main_promotions_description'])),
            'manufacturers_id' => xtc_db_prepare_input($_POST['manufacturers_id']),
            'meta_title' =>  xtc_db_prepare_input($_POST['meta_title']),
            'meta_keywords' =>  xtc_db_prepare_input($_POST['meta_keywords']),
            'meta_description' =>  xtc_db_prepare_input($_POST['meta_description']));
        
        if ($_GET['action'] == 'insert') {
            $insert_sql_data = array('date_added' => 'now()');
            $sql_data_array = xtc_array_merge($sql_data_array, $insert_sql_data);
            xtc_db_perform(TABLE_MAIN_PROMOTIONS, $sql_data_array);
            $id = xtc_db_insert_id();
        } elseif ($_GET['action'] == 'save') {
            $update_sql_data = array('last_modified' => 'now()');
            $sql_data_array = xtc_array_merge($sql_data_array, $update_sql_data);
            xtc_db_perform(TABLE_MAIN_PROMOTIONS, $sql_data_array, 'update', "id = '" . xtc_db_input($id) . "'");
        }
        
        //delete promotions_image
        if ($_POST['del_pic'] == 'on') {
            $image_query = xtc_db_query("SELECT image
                                              FROM " . TABLE_MAIN_PROMOTIONS . "
                                             WHERE  id = '" . xtc_db_input($id) . "'");
            $image = xtc_db_fetch_array($image_query);
            $image_location = DIR_FS_CATALOG_IMAGES . $image['image'];
            if (file_exists($image_location)) {
                @unlink($image_location);
                $sql_data_array['image'] = '';
                xtc_db_perform(TABLE_MAIN_PROMOTIONS, $sql_data_array, 'update',  "id = '" . xtc_db_input($id) . "'");
            }
        }
        
        $dir_aktionen=DIR_FS_CATALOG_IMAGES."/main_promotions";
        if ($image = xtc_try_upload('image', $dir_aktionen,'777', '', true)) {
            xtc_db_query("update ".TABLE_MAIN_PROMOTIONS." set
                                 image ='main_promotions/".$image->filename . "'
                                 where id = '" . xtc_db_input($id) . "'");
        }
        
        if (USE_CACHE == 'true') {
            xtc_reset_cache_block('main_promotions');
        }
        
        xtc_redirect(xtc_href_link(FILENAME_MAIN_PROMOTIONS, 'pID=' . $id));
        break;
        
    case 'setflag':
        xtc_db_query("update ".TABLE_MAIN_PROMOTIONS." set
                                 status = '" . xtc_db_input($_GET['flag']) . "'
                                 where id = '" . xtc_db_input($_GET['pID']) . "'");
        xtc_redirect(xtc_href_link(FILENAME_MAIN_PROMOTIONS, 'pID=' . $_GET['pID']));
        break;
        
    case 'deleteconfirm':
        $id = xtc_db_prepare_input($_GET['pID']);
        
        if ($_POST['delete_image'] == 'on') {
            $promotion_query = xtc_db_query("select image from ".TABLE_MAIN_PROMOTIONS." where id = '" . xtc_db_input($id) . "'");
            $promotion = xtc_db_fetch_array($promotion_query);
            $image_location = DIR_FS_CATALOG_IMAGES . $promotion['image'];
            if (file_exists($image_location)) @unlink($image_location);
        }
        
        xtc_db_query("delete from ".TABLE_MAIN_PROMOTIONS." where id = '" . xtc_db_input($id) . "'");
        
        if (USE_CACHE == 'true') {
            xtc_reset_cache_block('main_promotions');
        }
        
        xtc_redirect(xtc_href_link(FILENAME_MAIN_PROMOTIONS));
        break;
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
    		<td class="boxCenter" width="100%" valign="top">
    			<table border="0" width="100%" cellspacing="0" cellpadding="2">
      				<tr>
	        			<td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          					<tr>
	            				<td class="pageHeading">Aktionen</td>
            					<td class="pageHeading" align="right"><?php echo xtc_draw_separator('pixel_trans.gif', 57, 40); ?></td>
          					</tr>
        			</tr>	
				</table>
			</td>
      	</tr>
      	<tr>
        	<td>
        		<table border="0" width="100%" cellspacing="0" cellpadding="0">
          			<tr>
		  				<?php if($_GET['action'] != 'edit' && $_GET['action'] != 'new') { ?>          
            				<td valign="top">
            					<table border="0" width="100%" cellspacing="0" cellpadding="2">
              						<tr class="dataTableHeadingRow">
                						<td class="dataTableHeadingContent">Aktionen</td>
                						<td class="dataTableHeadingContent">Position</td>
                						<td class="dataTableHeadingContent">Aktiv</td>
                						<td class="dataTableHeadingContent" align="right">Aktion&nbsp;</td>
              						</tr>
									<?php
				                    $promotions_query_raw = "select * from ".TABLE_MAIN_PROMOTIONS." ORDER BY sort_order ASC";
				                    $promotions_query= xtc_db_query($promotions_query_raw);
				                    while ($promotion = xtc_db_fetch_array($promotions_query)) {
				                        if (((!$_GET['pID']) || (@$_GET['pID'] == $promotion['id'])) && (!$pInfo) && (substr($_GET['action'], 0, 3) != 'new')) {
				                            $pInfo_array = $promotion;
				                            $pInfo = new objectInfo($pInfo_array);
				                        }
				    				    if ((is_object($pInfo)) && ($promotion['id'] == $pInfo->id) ) {
				                            echo '<tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'pointer\'" onclick="document.location.href=\'' . xtc_href_link(FILENAME_MAIN_PROMOTIONS, 'pID=' . $promotion['id'] . '&action=edit_new') . '\'">' . "\n";
				                        } 
				                        else {
				                            echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'pointer\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . xtc_href_link(FILENAME_MAIN_PROMOTIONS, 'pID=' . $promotion['id']) . '\'">' . "\n";
				                        }
				                        ?>
										<td class="dataTableContent"><?php echo $promotion['name']; ?></td>
										<td class="dataTableContent"><?php echo $promotion['sort_order']; ?></td>
										<td class="dataTableContent">
    										<?php
                				            //show status icons (green & red circle) with links
                                            if ($promotion['status'] == 1) {
                                                echo xtc_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '<a href="' . xtc_href_link(FILENAME_MAIN_PROMOTIONS, 'pID=' . $promotion['id'] . '&action=setflag&flag=0') .'">&nbsp;&nbsp;' . xtc_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
                                            } 
                                            else {
                                                echo '<a href="' . xtc_href_link(FILENAME_MAIN_PROMOTIONS, 'pID=' . $promotion['id'] . '&action=setflag&flag=1') .'">' . xtc_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . xtc_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
                                            }
                                            ?>
										</td>
				        				<td class="dataTableContent" align="right"><?php if ( (is_object($pInfo)) && ($promotion['id'] == $pInfo->id) ) { echo xtc_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ICON_ARROW_RIGHT); } else { echo '<a href="' . xtc_href_link(FILENAME_MAIN_PROMOTIONS, 'pID=' . $promotion['id']) . '">' . xtc_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
				                        <!-- EOF - Tomcraft - 2009-06-10 - added some missing alternative text on admin icons -->
				   					</tr>
									<?php } ?>
									<?php if ($_GET['action'] != 'new') { ?>
				              			<tr>
					                		<td align="right" colspan="4" class="smallText"><?php echo xtc_button_link(BUTTON_INSERT, xtc_href_link(FILENAME_MAIN_PROMOTIONS, 'pID=' . $pInfo->id . '&action=new')); ?></td>
				              			</tr>
									<?php }	?>
            					</table>
            				</td>
							<?php
				            $heading = array();
				            $contents = array();
				            switch ($_GET['action']) {
				            case 'delete':
				                $heading[] = array('text' => '<b>Aktion löschen</b>');
                                $contents = array('form' => xtc_draw_form('main_promotions', FILENAME_MAIN_PROMOTIONS, 'pID=' . $pInfo->id . '&action=deleteconfirm'));
                                $contents[] = array('text' => 'Sind Sie sicher, dass Sie diese Aktion l&ouml;schen m&ouml;chten?');
                                $contents[] = array('text' => '<br /><b>' . $pInfo->name . '</b>');
				                $contents[] = array('text' => '<br />' . xtc_draw_checkbox_field('delete_image', '', true) . ' Bild löschen');
                                $contents[] = array('align' => 'center', 'text' => '<br />' . xtc_button(BUTTON_DELETE) . '&nbsp;' . xtc_button_link(BUTTON_CANCEL, xtc_href_link(FILENAME_MAIN_PROMOTIONS, 'pID=' . $pInfo->id)));
				                break;
                            default:
                                if (is_object($pInfo)) {
                                    $heading[] = array('text' => '<b>' . $pInfo->name . '</b>');
							        $contents[] = array('align' => 'center', 'text' => xtc_button_link(BUTTON_EDIT, xtc_href_link(FILENAME_MAIN_PROMOTIONS, 'pID=' . $pInfo->id . '&action=edit')) . '&nbsp;' . xtc_button_link(BUTTON_DELETE, xtc_href_link(FILENAME_MAIN_PROMOTIONS, 'pID=' . $pInfo->id . '&action=delete')));
                                    $contents[] = array('text' => '<br />hinzugefügt am ' . xtc_date_short($pInfo->date_added));
                                    if (xtc_not_null($pInfo->last_modified)) $contents[] = array('text' => ' letzte Änderung am ' . xtc_date_short($pInfo->last_modified));
                                    $contents[] = array('text' => '<br />' . xtc_info_image($pInfo->image, $aInfo->imäge, '250'));
                                }
                                break;
                            }
                            if ( (xtc_not_null($heading)) && (xtc_not_null($contents)) ) {
                                echo '            <td width="25%" valign="top">' . "\n";
                                $box = new box;
                                echo $box->infoBox($heading, $contents);
                                echo '            </td>' . "\n";
                            }
                        } 
                        else { 
                            #//Include WYSIWYG if is activated
                            require_once (DIR_FS_INC.'xtc_wysiwyg.inc.php');
                            ?>
							<script type="text/javascript" src="includes/modules/fckeditor/fckeditor.js"></script>
                            <?php
                            // Include WYSIWYG if is activated
                            if (USE_WYSIWYG == 'true') {
                            	// generate editor 
                            	echo PHP_EOL . (!function_exists('editorJSLink') ? '<script type="text/javascript" src="includes/modules/fckeditor/fckeditor.js"></script>' : '') . PHP_EOL;
                            	if ($_GET['action'] == 'edit' || $_GET['action'] == 'new') {
                                    echo xtc_wysiwyg('main_promotions_description', 'de', '2');
                            	}
                            }
                            ?>
                            							</script>				
							<td width="90%" valign="top"><br>
    							<?php 
                                if($_GET['action'] == 'edit') {
                                    $promotion_query_raw = "select * from ".TABLE_MAIN_PROMOTIONS." WHERE id = '".xtc_db_input($_GET['pID'])."'";
                                    $promotion_query = xtc_db_query($promotion_query_raw);
                                    $promotion = xtc_db_fetch_array($promotion_query);
                                }
                                else {
                                    $promotion=array('status' => 1, 'name' => '', 'image' => '', 'description' => '', 'sort_order' => '0', 'meta_title' => '', 'meta_description' => '', 'meta_keywords' => '', 'manufacturers_id' => '');
                                }
    							$pInfo_array = $promotion;
    				            $pInfo = new objectInfo($pInfo_array);
    
                                $manufacturers_array = array (array ('id' => '', 'text' => 'Nicht zugeordnet'));
                                $manufacturers_query = xtc_db_query("SELECT manufacturers_id, manufacturers_name FROM ".TABLE_MANUFACTURERS." ORDER BY manufacturers_name");
                                while ($manufacturers = xtc_db_fetch_array($manufacturers_query)) {
                                    $manufacturers_array[] = array ('id' => $manufacturers['manufacturers_id'], 'text' => $manufacturers['manufacturers_name']);
                                }				
                                ?>
    								<table class="contentTable" border="0" width="100%" cellspacing="0" cellpadding="2">
    				  					<tbody>
    				  						<tr class="infoBoxHeading">
    				    						<td class="infoBoxHeading"><b><?=  ($_GET['action'] == 'edit') ? 'Aktion bearbeiten' : 'Aktion anlegen' ?></b></td>
    				  						</tr>
    									</tbody>
    								</table>
    								<?php
    				                if($_GET['action'] == 'edit') {
    					               echo xtc_draw_form('main_promotions', FILENAME_MAIN_PROMOTIONS, 'pID=' . $pInfo->id . '&action=save', 'post', 'enctype="multipart/form-data"');
    				                }
    				                else {
    					               echo xtc_draw_form('main_promotions', FILENAME_MAIN_PROMOTIONS, 'action=insert', 'post', 'enctype="multipart/form-data"');
    				                }
    				                ?>
    								<table class="tableInput border0">
              							<tr>
                							<td><span class="main">Aktion aktiv</span></td>
                							<td><span class="main"><?php echo draw_on_off_selection('status', 'checkbox', (isset($pInfo->status) && $pInfo->status==1 ? true : false)) ?></span></td>
              							</tr>  
                        				<tr>
                            				<td><span class="main">Position</span></td>
                            				<td><span class="main"><?php echo xtc_draw_input_field('sort_order', $pInfo->sort_order, 'style="width: 100%"'); ?></span></td>
                        				</tr>
                        				<tr>
                                            <td><span class="main">Hersteller</span></td>
                                            <td><span class="main"><?php echo xtc_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $pInfo->manufacturers_id, 'style="width: 100%"'); ?></span></td>
                                        </tr> 
                                        <tr>
                                            <td><span class="main">Name der Aktion</span></td>
                                            <td><span class="main"><?php echo xtc_draw_input_field('name', $pInfo->name, 'style="width: 100%"'); ?></span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="main">Meta Title</span></td>
                                            <td><span class="main"><?php echo xtc_draw_input_field('meta_title', $pInfo->meta_title, 'style="width: 100%" maxlength="' . META_TITLE_LENGTH . '"'); ?></span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="main">Meta Description</span></td>
                                            <td><span class="main"><?php echo xtc_draw_input_field('meta_description', $pInfo->meta_description, 'style="width: 100%" maxlength="' . META_DESCRIPTION_LENGTH . '"'); ?></span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="main">Mety Keywords</span></td>
                                            <td><span class="main"><?php echo xtc_draw_input_field('meta_keywords', $pInfo->meta_keywords, 'style="width: 100%" maxlength="' . META_PRODUCTS_KEYWORDS_LENGTH . '"'); ?></span></td>
                                        </tr>
                                        <tr>
											<td><span class="main">Inhalt</span></td>
                                            <td><span class="main"> <?php echo xtc_draw_textarea_field('main_promotions_description', 'soft', '80%', '10', $pInfo->description, 'style="width:80%; height:50px;"'); ?></span></td>
                                        </tr>
                                        <tr>
                                        	<td><span class="main">Bild</span></td>
                                        	<td>
                                            	<table class="tableConfig borderall">
                                                    <tr>
                                                      <td class="dataTableConfig col-left">Bild</td>
                                                      <td class="dataTableConfig col-middle"><?php echo $pInfo->image; ?></td>
                                                      <td class="dataTableConfig col-right" rowspan="3"><?php echo $pInfo->image ? xtc_image(DIR_FS_CATALOG_IMAGES.$pInfo->image, 'Standard Image', 400) : xtc_draw_separator('pixel_trans.gif', PRODUCT_IMAGE_THUMBNAIL_WIDTH, 10); ?></td>
                                                    </tr>
                                                    <tr>
                                                      <td class="dataTableConfig col-left">Neues Bild</td>
                                                      <td class="dataTableConfig col-middle"><?php echo xtc_draw_file_field('image', false, 'class="imgupload"'); ?></td>      
                                                    </tr>    
                                                    <tr>
                                                      <td class="dataTableConfig col-left">Löschen</td>
                                                      <td class="dataTableConfig col-middle"><?php echo xtc_draw_checkbox_field('del_pic', 'on'); ?></td>      
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                       		<td><span class="main"></span></td>
                                       	 	<td><?php echo xtc_draw_hidden_field('pID', $pInfo->id); ?>                               
                    							<?php echo '<input type="submit" class="button" value="'.BUTTON_SAVE.'"/>'; ?>
                    						</td>
                    					</tr>
    								</table>
							</td>				
                        <?php }?>
					</tr>
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
<br />
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>