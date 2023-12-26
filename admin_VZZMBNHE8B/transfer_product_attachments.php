<?php
/* --------------------------------------------------------------
 * Zubehörartikel kopieren
 * Zubehörartikel auf einen anderen Artikel kopieren
 *
 */
require('includes/application_top.php');
require (DIR_WS_INCLUDES.'head.php');
?>
<script type="text/javascript" src="includes/general.js"></script>
<script type="text/javascript">
$( document ).ready(function() {
	$(document).on('click', '.check_attachment', function() {
		$('#transfer_result').hide();
		var model = $(this).closest('tr').find('.attachment_model').val();
		var tablerow = $(this).closest('tr');
		$.ajax({
			 type: 'POST',
			 url: '<?= xtc_href_link('/ajax_request.php', '')?>',
			 data: { action: 'check_attachment_products' , model: model},
			 dataType: 'json'
		}).done(function(data) {
			$(tablerow).find('.attachment_response').html(data.response);
			$(tablerow).find('#products_id').val(data.products_id);
			$('#attachment_products').html(data.product_attachments).show();
			if(data.product_attachments != null && data.product_attachments != '') {
				$('.copy_to').show();	
			}
			else {
				$('.copy_to').hide();
			}
		});    
	});


	$(document).on('click', '.transfer_attachments', function() {
		var model = $(this).closest('tr').find('.target_products_model').val();
		var tablerow = $(this).closest('tr');
		$.ajax({
			 type: 'POST',
			 url: '<?= xtc_href_link('/ajax_request.php', '')?>',
			 data: { action: 'transfer_attachment_products' , target_model: model, products_id : $('#products_id').val() },
			 dataType: 'json'
		}).done(function(data) {
			$('#transfer_result').html(data.response).show();
			if(data.success == true) {
				$('#transfer_result').css('color', 'green');
				$(tablerow).find('.target_products_model').val('');	
			}
			else {
				$('#transfer_result').css('color', 'red');
			}
		});    
	});	
	
	
});
</script>
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
	        			<td width="100%">
	        			<table border="0" width="100%" cellspacing="0" cellpadding="0">
          					<tr>
	            				<td class="pageHeading">Zubehörartikel auf einen anderen Artikel Übertragen</td>
            					<td class="pageHeading" align="right"><?php echo xtc_draw_separator('pixel_trans.gif', 57, 40); ?></td>
          					</tr>
          				</table>
        			</tr>	
        		<tr>
            		<td>
                        <!-- Anfang //--><br /><br />
                        <table width="100%" class="tableInput border0">
                            <tbody>
                            	<tr>
                            		<td colspan="2" style="border:none;font-size:15px" id="transfer_result" style="display:none" ></td>
                            	</tr>
                            	<tr class="additional_attachment" >
                            		<td style="border:none" colspan="2"><span class="main">Modellnummer des Artikels&nbsp;<input type="hidden" name="products_id" value="" id="products_id"><input type=text" name="attachment_product" class="attachment_model" value="" size="20">&nbsp;&nbsp;<input type="button" class="button check_attachment" value="Prüfen"><span style="padding-left:20px" class="attachment_response"></span></td>
                            	</tr>
                            	<tr >
                            		<td style="border:none" id="attachment_products" style="display:none"  colspan="2">
                            	</tr>
                            	<tr style="display:none" class="copy_to">
                            		<td style="border:none"  colspan="2" class="dataTableHeadingContent">Kopieren zum Artikel mit der Modellnummer&nbsp;<input type=text" name="target_products_model" class="target_products_model" value="" size="20">&nbsp;&nbsp;<input type="button" class="button transfer_attachments" value="Übertragen"><span style="padding-left:20px" class="transfer_response"></span></td>
                            	</tr>
                            </tbody>	
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