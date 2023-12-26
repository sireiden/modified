<?php 
require ('../../includes/application_top_callback.php');


$secure= true;
$processing = true;
$key = 'p4jJh2Rzw4MsfSc6m1qyY9e';

if($secure === true) {
	$data = 'https://www.ascasa.de'.substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '&hash'));	
	$hash = hash_hmac ( 'sha512' , $data , $key );
	if(strtolower($_GET['hash']) != strtolower($hash)) {
		$processing = false;
	}
}

/*
xtc_php_mail(EMAIL_BILLING_ADDRESS,	EMAIL_BILLING_NAME, 'm.foerster@brainsquad.de', 'Michael Förster', '',EMAIL_BILLING_REPLY_ADDRESS,EMAIL_BILLING_REPLY_ADDRESS_NAME,
'',
'',
'Commerzbank Notification',
"<pre>".print_r($_SERVER, true).print_r($_GET, true)."</pre>",
print_r($_SERVER, true)
);
*/

if($processing === true) {
	require_once(DIR_FS_INC.'inc.commerz_finanz.php');	
	save_notification($_GET);
}
else {
	xtc_php_mail(EMAIL_BILLING_ADDRESS,	EMAIL_BILLING_NAME, 'm.foerster@brainsquad.de', 'Michael Förster', '',EMAIL_BILLING_REPLY_ADDRESS,EMAIL_BILLING_REPLY_ADDRESS_NAME,
	'',
	'',
	'Commerzbank Notification - Hash fehlerhaft',
	"<pre>".print_r($_SERVER, true).print_r($_GET, true)."</pre>",
	print_r($_SERVER, true)
	);	
}

?>