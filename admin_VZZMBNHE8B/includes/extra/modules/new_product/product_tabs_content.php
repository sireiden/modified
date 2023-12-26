<?php

/* --------------------------------------------------------------
   Ausgabedetails der Produkt-Tabs
   --------------------------------------------------------------*/
   
defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

$csstabstyle = 'border: 1px solid #aaaaaa; padding: 4px; width: 99%; margin-top: -1px; margin-bottom: 10px; float: left;background: #F3F3F3;';
$csstab = '<style type="text/css">' .  '#tab_individual_1' . '{display: block;' . $csstabstyle . '}';
$csstab_nojs = '<style type="text/css">';
$ti=1;
foreach($product_tab_ids as $product_tab_id) {
    if($ti > 1) $csstab .= '#tab_individual_' . $ti .'{' . $csstabstyle . '}';
    $csstab_nojs .= '#tab_individual_' . $ti .'{display: block;' . $csstabstyle . '}';
    $ti++;
}
$csstab .= '#tab_individual_' . $ti .'{' . $csstabstyle . '}';
$csstab_nojs .= '#tab_individual_' . $new_product_tab_id .'{display: block;' . $csstabstyle . '}';
$csstab .= '</style>';
$csstab_nojs .= '</style>';
?>
<?php if (USE_ADMIN_LANG_TABS != 'false') { ?>
	<script type="text/javascript">
    	$.get("includes/individual_tabs_menu/individual_tabs_menu.css", function(css) {
      		$("head").append("<style type='text/css'>"+css+"<\/style>");
    	});
    	document.write('<?php echo ($csstab);?>');
	</script>
<?php 
} else { 
    echo ($csstab_nojs);
}
?>
<noscript>
	<?php echo ($csstab_nojs);?>
</noscript>
<?php 
foreach($product_tabs as $product_tab) { ?>
    <div id="tab_individual_head_<?=$product_tab['tab_id'] ?>" class="individualtab">
    	<ul>
    		<li class="tabselect"><?=$product_tab['tab_title'] ?></li>
    	</ul>
    </div>
    <div id="tab_individual_<?=$product_tab['tab_id'] ?>">                
        <table class="tableInput border0">
          <tr>
            <td class="main" style="width:190px;"><b>Position</b></td>
            <td class="main"><?php echo xtc_draw_input_field('tab_sort_order['.$product_tab['tab_id'].']', $product_tab['sort_order'], 'style="width:99%"'); ?></td>
          </tr>
          <tr>
            <td class="main" style="width:190px;"><b>Titel</b></td>
            <td class="main"><?php echo xtc_draw_input_field('tab_title['.$product_tab['tab_id'].']', $product_tab['tab_title'], 'style="width:99%" maxlength="255"'); ?></td>
          </tr>
          <tr>
            <td class="main" colspan="2"><?php echo xtc_draw_textarea_field('tab_text['.$product_tab['tab_id'].']', 'soft', '100', '25', ((isset($product_tab['tab_text'])) ? stripslashes($product_tab['tab_text']) : ''), 'style="width:99%"'); ?></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo TEXT_DELETE; ?></b></td>
            <td class="main"><?php echo xtc_draw_checkbox_field('tab_delete['.$product_tab['tab_id'].']', 'on'); ?></td>
          </tr> 
        </table>
    </div>
    <div style="clear:both;"></div>
<?php
}
?>
<div id="tab_individual_head_<?=$new_product_tab_id ?>" class="individualtab">
	<ul>
		<li class="tabselect">Neuer Produkttab</li>
	</ul>
</div>
<div id="tab_individual_<?=$new_product_tab_id ?>">                
    <table class="tableInput border0">
      <tr>
        <td class="main" style="width:190px;"><b>Position</b></td>
        <td class="main"><?php echo xtc_draw_input_field('tab_sort_order['.$new_product_tab_id.']', $new_product_tab_id, 'style="width:99%"'); ?></td>
      </tr>
      <tr>
        <td class="main" style="width:190px;"><b>Titel</b></td>
        <td class="main"><?php echo xtc_draw_input_field('tab_title['.$new_product_tab_id.']', '', 'style="width:99%" maxlength="255"'); ?></td>
      </tr>
      <tr>
        <td class="main" colspan="2"><?php echo xtc_draw_textarea_field('tab_text['.$new_product_tab_id.']', 'soft', '100', '25', '', 'style="width:99%"'); ?></td>
      </tr>
    </table>
</div>
<div style="clear:both;"></div>

            