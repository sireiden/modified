<?php

require('includes/application_top.php');

// include needed functions
require_once (DIR_FS_INC.'xtc_get_category_path.inc.php');
require_once (DIR_FS_INC.'xtc_get_parent_categories.inc.php');

if(isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'setflag':
            xtc_db_query("update ".TABLE_MEGAMENU." set
                                 megamenu_status = '" . xtc_db_input($_GET['flag']) . "'
                                 where megamenu_id = '" . xtc_db_input($_GET['mID']) . "'");
            break;
    }
}





//display per page
require (DIR_WS_INCLUDES.'head.php');
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
      <div class="main pdg2">Hier erfolgt die Verwaltung des Megamenüs</div>
      <div class="smallText pdg2 flt-r"><a href="<?= xtc_href_link(FILENAME_MEGAMENU_EDIT, '&new=1', 'NONSSL') ?>" class="button">Neue Hauptkategorie anlegen</a></div>
      <?php if(isset($_GET['error_message'])) { ?>
      <br style="clear:both"><div class="error_message"><?= $_GET['error_message'] ?></div><br>
      <?php } ?>
       <?php if(isset($success_message)) { ?>
      <br style="clear:both"><div class="success_message"><?= $success_message ?></div><br>
      <?php } ?>     
      <table class="tableCenter">
      	<tr class="dataTableHeadingRow">
      		<td class="dataTableHeadingContent">Bezeichnung</td>
      		<td class="dataTableHeadingContent">Position</td>
      		<td class="dataTableHeadingContent">Status</td>
      		<td class="dataTableHeadingContent">Aktion</td>
      	</tr>
      	<?php 
      	$megamenu_query_raw = "select * from ".TABLE_MEGAMENU." ORDER BY sort_order ASC";
      	$megamenu_query= xtc_db_query($megamenu_query_raw);
      	while ($megamenu = xtc_db_fetch_array($megamenu_query)) {
      	?>
    		<tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='pointer'" onmouseout="this.className='dataTableRow'">
    			<td class="dataTableContent"><?= $megamenu['megamenu_name']?></td>
    			<td class="dataTableContent"><?= $megamenu['sort_order']?></td>
    			<td class="dataTableContent">
					<?php
		            //show status icons (green & red circle) with links
					if ($megamenu['megamenu_status'] == 1) {
					    echo xtc_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '<a href="' . xtc_href_link(FILENAME_MEGAMENU, 'mID=' . $megamenu['megamenu_id'] . '&action=setflag&flag=0') .'">&nbsp;&nbsp;' . xtc_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
                    } 
                    else {
                        echo '<a href="' . xtc_href_link(FILENAME_MEGAMENU, 'mID=' . $megamenu['megamenu_id'] . '&action=setflag&flag=1') .'">' . xtc_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . xtc_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
                    }
                    ?>
    			</td>
    			<td class="dataTableContent"><a href="<?= xtc_href_link(FILENAME_MEGAMENU_EDIT, 'megamenu_id='.$megamenu['megamenu_id'], 'NONSSL') ?>" class="button">Bearbeiten</a></td>
    		</tr>
    	<?php } ?>
      </table>           
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