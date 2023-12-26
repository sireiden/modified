<?php
/* Mailtemplate in die Bestellung laden
 * 
 */

echo "
<script type=\"text/javascript\">
$( document ).ready(function() {
	$(document).on('change', '#mail_template', function() {
		var template_id = $(this).val()
		$.ajax({
			 type: 'POST',
			 url: '".xtc_href_link(FILENAME_AJAX_REQUEST, '')."',
			 data: { action: 'get_mail_template' , template_id: template_id},
			 dataType: 'json'
		}).done(function(data) {
			$('#comments').text(data.response);
		});    
	});	
});	
</script>
".PHP_EOL;
?>
