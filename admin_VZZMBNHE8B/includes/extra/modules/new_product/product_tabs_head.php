<?php

/* --------------------------------------------------------------
    Übersichtsbereich der Produkt-Tabs
   --------------------------------------------------------------*/
   
defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

?>

<script type="text/javascript">
$( document ).ready(function() {
	function update_tab_visibility() {
    	$('input[name^="tab_status"]:radio').each(function(i){
    		var tab_id = $(this).attr("name").replace("tab_status[", "").replace("]", "");
    		if(($(this).val() == 1 && $(this).is(':checked')) || ($(this).val() == 0 && !$(this).is(':checked'))) {
    			$('#tab_individual_head_' + tab_id).show();
    			$('#tab_individual_' + tab_id).show();
    		}
    		else {
    			$('#tab_individual_head_' + tab_id).hide();
    			$('#tab_individual_' + tab_id).hide();
    		}
    	});
	}

	update_tab_visibility();
	$('input[name^="tab_status"]:radio').on('change', function() {
		update_tab_visibility();
	});
	
	
});
</script>
<?php

$products_tab_query = xtc_db_query("SELECT * FROM ".TABLE_PRODUCTS_TABS." WHERE products_id = '".$pInfo->products_id."' ORDER BY sort_order ASC");
$product_tabs = array();
while($product_tab = xtc_db_fetch_array($products_tab_query)) { 
	$product_tabs[] = $product_tab;
}

$product_tab_status_array = array(array('id'=>1,'text'=>'Anzeigen'),
    array('id'=>0,'text'=>'Nicht anzeigen'),
);

$product_tab_count_query = xtc_db_query("SELECT MAX(tab_id) as current FROM " . TABLE_PRODUCTS_TABS . " WHERE products_id = '".$pInfo->products_id."'");
$product_tab_count = xtc_db_fetch_array($product_tab_count_query);

// Zusätzliche Herstellerseiten
$manufacturers_pages_query = xtc_db_query("SELECT page_id, page_title FROM " . TABLE_MANUFACTURERS_PAGES . " WHERE manufacturers_id='".(int)$pInfo->manufacturers_id."' order by sort_order ASC");
$products_manufacturer_pages = explode('|', $products_fields['products_manufacturers_pages']);
$products_manufacturer_pages = array_flip($products_manufacturer_pages);


?>
<div style="padding:5px;">
	<div style="float:left; width:56%; vertical-align:top" class="main div_header">Zusätzliche-Produkt-Tabs<?= draw_tooltip('Zusätzliche Produkt-Tabs können hier aktiviert bzw. deaktiviert werden. Außerdem können weitere Tabs angelegt werden') ?></div>
	<div style="float:left; width:41%; vertical-align:top" class="main div_header">Herstellerseiten<?= draw_tooltip('Herstellerseiten können hier als Produkt-Tabs angezeigt werden') ?></div>
	<div style="border: 1px solid #aaaaaa; padding: 4px; width: 99%; margin-top: -1px; margin-bottom: 10px; float: left;background: #F3F3F3;">
    	<div style="float:left; width:57%; vertical-align:top">
    		<table class="tableInput border0">
          		<?php foreach($product_tabs as $product_tab) { ?>
              		<tr>
                		<td style="width:260px"><span class="main"><?php echo $product_tab['tab_title'] ?></span></td>
                		<td><span class="main"><?php echo draw_on_off_selection('tab_status['.$product_tab['tab_id'].']', $product_tab_status_array, ($product_tab['status'] == '0' ? false : true), 'style="width: 155px"'); ?></span></td>
              		</tr>      		
    			<?php } ?>  
           		<tr>
            		<td style="width:260px"><span class="main">Zusätzlichen Tab anlegen</span></td>
            		<td><span class="main"><?php echo draw_on_off_selection('tab_status['.$new_product_tab_id.']', 'checkbox', false) ?></span></td>
          		</tr>
          	</table>     					
    	</div>
    	<div style="float:left;width:43%; vertical-align:top">
    		<table class="tableInput border0">
    			<?php while($manufacturers_page = xtc_db_fetch_array($manufacturers_pages_query)) {  ?>
              		<tr>
                		<td><span class="main"><?php echo $manufacturers_page['page_title'] ?><br>
                		<?php echo draw_on_off_selection('products_manufacturers_pages['.$manufacturers_page['page_id'].']', $product_tab_status_array, (!array_key_exists($manufacturers_page['page_id'], $products_manufacturer_pages) ? false : true), 'style="width: 155px"'); ?></span></td>
              		</tr>      		
    			<?php } ?>
    		</table>    		
    	</div>
    </div>
</div>
<div style="clear:both;"></div>

<?php 