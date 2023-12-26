<?php

/* --------------------------------------------------------------
   Ausgabedetails der Zubehörartikel
   --------------------------------------------------------------*/
   
defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

$product_attachments_result = xtc_db_query("SELECT pd.products_name, p.products_id, p2a.attachment_id, p2a.sort
                                     		FROM ".TABLE_PRODUCTS." p,
                                          	".TABLE_PRODUCTS_DESCRIPTION." pd,
											".TABLE_PRODUCTS_TO_ATTACHMENTS." p2a
                                    		WHERE p2a.products_id = '".(int) $pInfo->products_id."'
                                      		AND p2a.attachment_id = pd.products_id
											AND p2a.products_id = p.products_id
                                      		AND pd.language_id = '".$_SESSION['languages_id']."'
											ORDER BY sort ASC, attachment_id ASC");

?>
<style type="text/css">
    .remove_attachment {cursor:pointer;font-family: Verdana, Arial, sans-serif; font-size: 10px; color: #000000; font-weight: normal; text-decoration: underline; outline: none;}
</style>
<script type="text/javascript">
$( document ).ready(function() {
	$(document).on('click', '.check_attachment', function() {
		var model = $(this).closest('tr').find('.attachment_model').val();
		var tablerow = $(this).closest('tr');
		$.ajax({
			 type: 'POST',
			 url: '<?= xtc_href_link(FILENAME_AJAX_REQUEST, '')?>',
			 data: { action: 'check_model' , model: model},
			 dataType: 'json'
		}).done(function(data) {
			$(tablerow).find('.attachment_response').html(data.response);
			$(tablerow).find('.attachment_ids').val(data.products_id);
		});    
	});

	var max_add = 1;
	$('.attachment_add').click(function() {	
		var add = $('.additional_attachment').clone().attr('class', '');
		max_add++;
		$(add).insertBefore($('.attachment_insert')).show();
	}); 


	$(document).on('click', '.remove_attachment', function() {
		if(confirm('Zubehörartikel wirklich entfernen?')) {
			var attachment_id = $(this).attr('id').replace('attachment', '');
			$.ajax({
				type: 'POST',
				url: '<?= xtc_href_link(FILENAME_AJAX_REQUEST, '')?>',
				data: { action: 'remove_attachment' , attachment_id: attachment_id, products_id: '<?= $_GET['pID'] ?>'},
				success: function(response){
					$('#attachment'+attachment_id).closest('tr').fadeOut(300, function(){ $(this).remove();});
				}
			});			
		}
	});		
	
});
</script>
<div style="padding:5px;">
	<div class="main div_header">Zubehörartikel.<?= draw_tooltip('Zubehörartikel können hier angelegt und hinzugefügt werden') ?></div>
	<div style="border: 1px solid #aaaaaa; padding: 4px; width: 99%; margin-top: -1px; margin-bottom: 10px; float: left;background: #F3F3F3;">
    	<div style="float:left; width:100%; vertical-align:top">
    		<table class="tableInput border0">
            	<tr >
            		<td colspan="3" style="border:none"><div style="float:right"><input type="button" value="Zubehörartikel hinzufügen" class="button attachment_add"></div></td>
            	</tr>    		
          		<?php while($product_attachment = xtc_db_fetch_array($product_attachments_result)) { ?>
              		<tr>
                		<td style="width:10%"><?php echo xtc_draw_input_field('product_attachment['.$product_attachment['attachment_id'].']', $product_attachment['sort'], 'style="width:99%"'); ?>
                		<td style="width:75%"><?php echo $product_attachment['products_name'] ?></td>
                		<td><span class="remove_attachment" id="attachment<?= $product_attachment['attachment_id'] ?>" class="remove_attachment">Entfernen</span></td>
              		</tr>      		
    			<?php } ?>  
            	<tr class="additional_attachment dataTableHeadingRow" style="display:none">
            		<td style="border:none"  colspan="3" class="dataTableHeadingContent">Modellnummer&nbsp;<input type="hidden" name="attachment_ids[]" value="" class="attachment_ids"><input type=text" name="attachment_product" class="attachment_model" value="" size="20">&nbsp;&nbsp;<input type="button" class="check_attachment" value="Prüfen"><span style="padding-left:20px" class="attachment_response"></span></td>
            	</tr>
            	<tr style="display:none" class="attachment_insert"></tr>
          	</table>     					
    	</div>
    </div>
</div>
<div style="clear:both;"></div>

