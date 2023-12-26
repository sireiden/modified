<?php
/*
 *  xtc: lexikon v3.04
 *  created by Southbridge, Sergej Stroh
 *  $id lexikon.php
 *
 * ----------------------------------------------- */
  require('includes/application_top.php');
  require_once(DIR_FS_INC . 'xtc_wysiwyg.inc.php');
	
  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  if (!$action) {
      $icon_edit = xtc_image(DIR_WS_ICONS.'icon_edit.gif', ICON_EDIT,'','','style="cursor:pointer"');
      $icon_delete = xtc_image(DIR_WS_ICONS.'delete.gif', ICON_DELETE,'','','style="cursor:pointer"');
  }
  $languages = xtc_get_languages();

  switch ($_GET['action']) {
    case 'delete': // delete keyword
      xtc_db_query("DELETE FROM ".TABLE_LEXIKON." where id = '".$_GET['lexikon_id']."'");
      xtc_redirect(xtc_href_link(FILENAME_MODULE_LEXIKON));
    case 'edit': // update keyword
    if($_GET['action'] == 'edit') {
      $lexikon_query = "SELECT id, keyword, description
                    FROM " . TABLE_LEXIKON . "
                    WHERE id = '".$_GET['lexikon_id']."'";

      $lexikon_data = xtc_db_query($lexikon_query);
      $lexikon = xtc_db_fetch_array($lexikon_data);
    }
// === INSERT, UPDATE KEYWORD =================================================
    break;
    case 'insertkeyword': // insert Data
      $keyword = xtc_db_prepare_input($_POST['keyword']);
	  $keyword = htmlentities($keyword);
      $desc = xtc_db_prepare_input(remove_word_code($_POST['description']));

      $lexikon_array = array('keyword' => $keyword, 'description' => $desc);

      if($_POST['keyword_id'] != '') {
        xtc_db_perform(TABLE_LEXIKON, $lexikon_array, 'update', "id = '".$_POST['keyword_id']."'");
      } elseif($_GET['action'] == 'insertkeyword')  {
          $sql_doppelt = "SELECT count(*) AS anzahl FROM " . TABLE_LEXIKON . " WHERE keyword = '" . $keyword . "'";
                      $query_doppelt = xtc_db_query($sql_doppelt);
                      $count_doppelt =xtc_db_fetch_array($query_doppelt);
                      if ($count_doppelt['anzahl'] > 0) {
                              $error = '<table border="0" cellspacing="0" cellpadding="0" align="center">
                                                                     <tr>
                                                                      <td>
                                                                       <div style="font-size:16px; color:red">Keyword schon vorhanden</div>
                                                                      </td>
                                                                     </tr>
                                                                    </table>';
                      }else{
                              if ($_POST['keyword'] != ''){  
                            xtc_db_perform(TABLE_LEXIKON, $lexikon_array);
                              }else{
                                      $error = '<table border="0" cellspacing="0" cellpadding="0" align="center">
                                                                     <tr>
                                                                      <td>
                                                                       <div style="font-size:16px; color:red">Kein Keyword eingegeben</div>
                                                                      </td>
                                                                     </tr>
                                                                    </table>';
                              }
                      }
		  
      }
  }

  require (DIR_WS_INCLUDES.'head.php');
  
  if (USE_WYSIWYG=='true') {
      $query=xtc_db_query("SELECT code FROM ". TABLE_LANGUAGES ." WHERE languages_id='".(int)$_SESSION['languages_id']."'");
      $data=xtc_db_fetch_array($query);
      if ($action =='newkeyword' || $action =='edit') {
          for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
              echo xtc_wysiwyg('lexikon', $data['code'], $languages[$i]['id']);
          }
      }
  }
  ?>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
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
        

<!-- titel -->
          <div class="pageHeadingImage"><?php echo xtc_image(DIR_WS_ICONS.'heading/icon_content.png'); ?></div>
          <div class="pageHeading"><?php echo HEADING_TITLE;?><br /></div>          
          <div class="main pdg2 flt-l">Tools</div>
          <div class="clear"></div>
<!---
 === EINLEITUNG ===============================================================
-->
<br />
<table width="100%" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td class="main"><?php echo CONTENT_NOTE; ?></td>
  </tr>
</table>
<?php echo $error;?>
<!--
 === NEW KEYWORD ==============================================================
-->
<?php
  if($_GET['action'] == 'newkeyword' || $_GET['action'] == 'edit') {
?>
<br />
<?php echo xtc_draw_form('lexikon_newkeyword', FILENAME_MODULE_LEXIKON,'action=insertkeyword','post','');?>
<?php echo xtc_draw_hidden_field('keyword_id',$lexikon['id']);?>
    <div style="padding:5px;clear:both;">
      <table class="tableConfig borderall" style="width:99%">
            <tr>
              <td class="dataTableConfig col-left" style="min-width:205px;"><?php echo LEXIKON_NEW_KEYWORD; ?></td>
              <td class="dataTableConfig col-single-right"><?php echo xtc_draw_input_field('keyword', html_entity_decode($lexikon['keyword']),'size="65"'); ?></td>
            </tr>
            <tr>
              <td class="dataTableConfig col-left" style="min-width:205px;"><?php echo LEXIKON_NEW_DESCRIPTION; ?></td>
              <td class="dataTableConfig col-single-right"><?php echo xtc_draw_textarea_field('description','','100','20', $lexikon['description'], 'style="width:99%;"'); ?></td>
            </tr>            
     </table>
   </div>
    <div style="padding:5px;clear:both;">
      <div class="flt-r mrg5 pdg2">
        <input type="submit" class="button" onclick="this.blur();" value="<?php echo BUTTON_SAVE; ?>"/>
      </div>
      <div class="flt-r mrg5 pdg2">
        <a class="button" onclick="this.blur();" href="<?php echo xtc_href_link(FILENAME_MODULE_LEXIKON); ?>"><?php echo BUTTON_BACK; ?></a>
      </div>
    </div>   
</form>
<?php
  } else { //--- IF eof
?>
<!--
 === SORTIERUNG ===============================================================
-->
<?php
      // Keywords ausgeben, wo Buchstabe vorkommt
      // BOF - sort keywords in backend lexikon list
        if($_GET['keyword'] == 'All')
          { $show = 'ORDER BY keyword'; }
        elseif($_GET['keyword'] != 'All')
          { $show = 'WHERE keyword LIKE \''. $_GET['keyword'] .'%\' ORDER BY keyword'; }
        else { $show = 'ORDER BY keyword'; }
      // EOF - sort keywords in backend lexikon list
    ?>
<table border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td align="center">&nbsp;<?php echo LEXIKON_NAVIGATION;?>&nbsp;</td>
    <td class="dataTableContent" align="center">
    <?php echo '<a class="button" href="'.xtc_href_link(FILENAME_MODULE_LEXIKON,'keyword=all') .'">'. LEXIKON_NAVIGATION_ALL .'</a>'; ?>
<?php
// Buchstaben fur die Sortierung
  for($i='A'; $i<='Z'; $i++) {
?>
    <td class="lexikon_abc" width="15" align="center">
<?php
echo '<a class="button" href="'.xtc_href_link(FILENAME_MODULE_LEXIKON,'keyword='.$i) .'">'. $i .'</a>';
// nur bis Z ausgeben
  if($i == 'Z') { break; }
?>
  </td>
<?php
  }
?>
  </tr>
</table>
<!--
 === NEW & CONFIG NAVIGATION ==================================================
-->
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="15">&nbsp;</td>
    <td>
    <a class="button" onclick="this.blur();" href="<?php echo xtc_href_link(FILENAME_MODULE_LEXIKON,'action=newkeyword'); ?>"><?php echo LEXIKON_BUTTON_NEW_KEYWORD; ?></a>
    <a class="button" onclick="this.blur();" href="<?php echo xtc_href_link(FILENAME_CONFIGURATION,'gID=1'); ?>"><?php echo LEXIKON_BUTTON_CONFIG; ?></a>&nbsp;
    </td>
  </tr>
</table>
<br />
<?php
// === UEBERSICHT ANFANG ======================================================
  $lexikon_query = "SELECT  id,
                            keyword
                    FROM " . TABLE_LEXIKON . "
                    " .$show;

  $lexikon_data = xtc_db_query($lexikon_query);
?>
<table class="tableCenter" style="width:1240px">
  <tr class="dataTableHeadingRow">
    <td class="dataTableHeadingContent txta-c"" width="5%" ><?php echo LEXIKON_DATABASE_ID; ?></td>
    <td class="dataTableHeadingContent txta-c" width="75%"><?php echo LEXIKON_KEYWORD; ?></td>
    <td class="dataTableHeadingContent txta-c" width="20%" ><?php echo LEXIKON_ACTION; ?></td>
  </tr>
<?php
  while($lexikon = xtc_db_fetch_array($lexikon_data)) {
?>
  <tr class="dataTableRow" onmouseover="this.className='dataTableRowOver'" onmouseout="this.className='dataTableRow'">
    <td class="dataTableContent txta-c" align="center"><?php echo $lexikon['id'];?></td>
    <td class="dataTableContent"><?php echo $lexikon['keyword'];?></td>
    <td class="dataTableContent txta-r nobr">
<!--
 === SELECTION, EDIT, DELETE ==================================================
-->

<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>
    <?php 
    echo '<a href="'.xtc_href_link(FILENAME_MODULE_LEXIKON,'action=edit&lexikon_id='.$lexikon['id']).'">'.$icon_edit.'  '.TEXT_EDIT.'</a>&nbsp;&nbsp;';
    echo '<a href="'.xtc_href_link(FILENAME_MODULE_LEXIKON,'action=delete&lexikon_id='.$lexikon['id']).'" onclick="return confirmLink(\''. DELETE_ENTRY .'\', \'\' ,this)">'.$icon_delete.'  '.TEXT_DELETE.'</a>&nbsp;&nbsp;';
    ?></td>
  </tr>
</table>

    </td>
  </tr>
<?php
  }
?>
</table>
<?php

      } // --- ELSE eof
    ?>
<!--
 === UEBERSICHT ENDE ==========================================================
-->
        <!-- body_text_eof //-->
        
      </tr>
    </table>
    <br><br>   
    <!-- body_eof //-->
    <!-- footer //-->
    <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
    <!-- footer_eof //-->
  </body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

</body>
</html>