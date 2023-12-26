<?php

/* --------------------------------------------------------------
 Individuelle Ascasa Produktinformationen
 --------------------------------------------------------------*/

$products_fields_query = xtc_db_query("SELECT * FROM ".TABLE_PRODUCTS_FIELDS." WHERE products_id = '".$pInfo->products_id."'");
$products_fields = xtc_db_fetch_array($products_fields_query);

?>

<div style="padding:5px;">
	<div class="main div_header">Individuelle ascasa Felder<?= draw_tooltip('Hier können individuelle und ascasa spezifische Felder und Prodktinformationen bearbeitet werden') ?></div>
	<div style="border: 1px solid #aaaaaa; padding: 4px; width: 99%; margin-top: -1px; margin-bottom: 10px; float: left;background: #F3F3F3;">
    	<div style="float:left; width:57%; vertical-align:top">
    		<table class="tableInput border0">
              <tr>
                <td style="width:260px"><span class="main">Energieeffizienz-Klasse</span></td>
                <td><span class="main"><?php echo xtc_draw_pull_down_menu('products_efficiency_class', get_efficiency_array(), $products_fields['products_efficiency_class'], 'style="width: 155px"'); ?></span></td>
              </tr>
              <tr>
                <td><span class="main">Skonto bei Vorkasse (in %)</span></td>
                <td><span class="main"><?php echo xtc_draw_input_field('products_cash_discount', $products_fields['products_cash_discount'] ,'style="width: 155px"'); ?></span></td>
              </tr>              
    		</table>
    	</div>
    	 <div style="float:left;width:57%; vertical-align:top">
    		<table class="tableInput border0">
              <tr>
                <td style="width:260px;"><span class="main">Tiefpreisgarantie</span></td>
                <td><span class="main"><?php echo draw_on_off_selection('products_best_price', 'checkbox', $products_fields['products_best_price'] == 1 ? true : false) ?></span></td>
              </tr>
              <tr>
                <td style="width:260px;"><span class="main">5-Jahres Garantie</span></td>
                <td><span class="main"><?php echo draw_on_off_selection('products_5year_warranty', 'checkbox', $products_fields['products_5year_warranty'] == 1 ? true : false) ?></span></td>
              </tr>             
    		</table>    	
    	</div>
    	 <div style="float:left;width:43%; vertical-align:top">
    		<table class="tableInput border0">
              <tr>
                <td style="width:180px;"><span class="main">Sonderpreisanfrage</span></td>
                <td><span class="main"><?php echo draw_on_off_selection('products_specialrequest', 'checkbox', $products_fields['products_specialrequest'] == 1 ? true : false) ?></span></td>
              </tr>              
              <tr>
                <td><span class="main">Bedingungen zur Garantie verlinken</span></td>
                <td><span class="main"><?php echo draw_on_off_selection('products_warranty_conditions', 'checkbox', $products_fields['products_warranty_conditions'] == 1 ? true : false) ?></span></td>
              </tr>
    		</table>    	
    	</div>  
    	<div style="float:left; width:100%; vertical-align:top">
    		<table class="tableInput border0">
              <tr>
                <td style="width:260px"><span class="main">Versandkommentare</span></td>
                <td><span class="main"><?php echo xtc_draw_input_field('products_shippinginfo', $products_fields['products_shippinginfo'] ,'style="width: 465px"'); ?></span></td>
              </tr>              
    		</table>
    	</div>    	
    </div>
</div>
<div style="clear:both;"></div>