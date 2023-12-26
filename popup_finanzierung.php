<?php

require ('includes/application_top.php');


$price = $_GET['price'];
$total_price = $xtPrice->xtcFormat($price, true);

require_once(DIR_FS_INC.'inc.commerz_finanz.php');
$commerz_finanz = commerz_finanz('', '', true, $price);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php echo HTML_PARAMS; ?>>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>" /> 
  <meta http-equiv="Content-Style-Type" content="text/css" />
  <meta name="robots" content="noindex, nofollow, noodp" />
  <title><?php echo htmlspecialchars($content_data['content_heading'], ENT_QUOTES, strtoupper($_SESSION['language_charset'])); ?></title>
  <base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
  <link rel="stylesheet" type="text/css" href="<?php echo 'templates/'.CURRENT_TEMPLATE.'/stylesheet.css'; ?>" />
  <script src="templates/ascasa/javascript/jquery-1.8.3.min.js" type="text/javascript"></script>
  <script type="text/javascript">
     $(document).ready(function(){ 
    	 $(document).on('change', '#laufzeit_picker', function() {
 			var id = 'rate_' + $(this).val();
 			var monate = $(this).val();
 			$('.monatsraten').html($(this).val());
 			$('.ratelist').each(function() {
 				if($(this).attr('id') == id) {
 					$(this).show();
 					$('#monatsrate').html($(this).html());
 				}
 				else {
 					$(this).hide();
 				}
 			});
 			$('.sollzins').each(function() {
 				$(this).hide();
 			});
 			$('#sollzins_'+ monate).show(); 

 			$('.effektivzins').each(function() {
 				if($(this).attr('id') == 'effektivzins_'+ monate) {
 					$(this).show();
 					if(monate <= 30) {
 						$('#effektivzinsrate').html('<img src="/images/finanzierung_0prozent.gif" border="0" alt="0&#37;">');
 					}
 					else {
 						$('#effektivzinsrate').html($(this).html() + '%');
 					}
 				}
 				else {
 					$(this).hide();
 				}
 			}); 			
 

 			$('.gesamtpreis').each(function() {
 				$(this).hide();
 			});
 			$('#gesamtpreis_'+ monate).show();  			 			
 			

     	 });

    	 $("#laufzeit_picker option[value=30]").attr("selected","selected");
    	 $("#laufzeit_picker").trigger('change'); 
     });
  </script>  
</head>
<body style="background:#fff; font-family:Arial, Helvetica, sans-serif;">
<div class="commerz_info_teaser">
<span class="headline">
Finanzierung zu 0% Sollzinsen bei ascasa<br> 
Jetzt kaufen - und in kleinen monatlichen Raten zahlen
</span>
<p>Bezahlen Sie schnell und unkompliziert in kleinen Monatsraten. Einfach Artikel auswählen, zur Kasse gehen und mit der Zahlungsart "Finanzierung" die Ware bezahlen. Die Vermittlung erfolgt ausschlie&szlig;lich f&uuml;r den Kreditgeber BNP Paribas S.A. Niederlassung Deutschland, Standort M&uuml;nchen: Schwanthalerstr. 31, 80336 M&uuml;nchen.</p>
<p>Finanzierung über den Kreditrahmen mit Mastercard®; Nettodarlehensbetrag bonitätsabhängig bis 15.000 EUR. Vertragslaufzeit auf unbestimmte Zeit. Gebundener Sollzinssatz von 0 % gilt nur für diesen Einkauf für die ersten 30 Monate ab Vertragsschluss. Danach und für alle weiteren Verfügungen beträgt der veränderliche Sollzinssatz (jährlich) 14,84 % (15,90 % effektiver Jahreszinssatz). Zahlungen werden erst auf verzinste Verfügungen angerechnet, bei unterschiedlichen Zinssätzen zuerst auf die höher verzinsten; eine Tilgung sollzinsfreier Umsätze findet statt, wenn keine verzinsten Verfügungen mehr vorhanden sind. Die monatliche Rate kann sich verändern, wenn weitere Verfügungen über den Kreditrahmen vorgenommen werden; sie beträgt min. 2,5 % der jeweils höchsten, auf volle 100 € gerundeten Inanspruchnahme des Kreditrahmens, mind. 9 EUR. Angaben zugleich repräsentatives Beispiel gem. § 6a Abs. 4 PAngV.</p>
<p class="commer-finanz-listingshort">Eine Finanzierung zu 0% Sollzinsen kann für eine Laufzeit von 6 bis 30 Monaten angeboten werden.</p>
<br />
<p>
	<div class="hrlightgrey"></div>
	<span style="font-size:14px">Berechnen Sie Ihren Finanzierungswunsch</span>
	<table style="border:none;margin-left:60px;margin-top:10px" >
		<tr>
			<td>Kaufpreis</td>
			<td><?= $total_price?></td>
		</tr>
		<tr>
			<td>Laufzeit</td>
			<td><select id="laufzeit_picker" name="laufzeit"><?= $commerz_finanz['dropdown']; ?></select></td>
		</tr>
		<tr>
			<td>Effektiver Jahreszins</td>
			<td><span id="effektivzinsrate"><img src="/images/finanzierung_0prozent.gif" border="0" alt="0&#37;"></span></td>
		</tr>
		<tr>
			<td><br>Netto-Darlehensbetrag</td>
			<td><br><?= $total_price?></td>
		</tr>
		<tr>
			<td>Monatliche Rate</td>
			<td><span class="commer-finanz-listingshort">Nur <?= $commerz_finanz['ratelist']; ?></span></td>
		</tr>								
	</table>
	<div class="hrlightgrey"></div>
</p>
<br />
<p style="border:1px solid #fd8701;background-color: #EBEBEB;padding:10px;" >Kaufpreis entspricht dem Nettodarlehensbetrag. Gebundener Sollzinssatz (j&auml;hrl.) <?= $commerz_finanz['sollzinsrate']; ?>%; effektiver Jahreszins <?= $commerz_finanz['effektivzinsrate']; ?>%, <span class="monatsraten"></span> Monatsraten @ <span id="monatsrate"></span>. Gesamtbetrag bei einer Laufzeit von <span class="monatsraten"></span> Monaten: <?= $commerz_finanz['totalprice']; ?>. Angaben zugleich repr&auml;sentatives Beispiel i.S.d º 6a Abs. 4 PangV. Vermittlung erfolgt ausschlie&szlig;lich f&uuml;r den Kreditgeber BNP Paribas S.A. Niederlassung Deutschland, Standort M&uuml;nchen: Schwanthalerstr. 31, 80336 M&uuml;nchen.</p>
<br />
<p>W&auml;hlen Sie w&auml;hrend des Bestellprozesses die Zahlungsart Finanzierungskauf aus. Am
Ende des Bestellvorganges k÷nnen Sie alle notwendigen Vertragsunterlagen bequem
herunterladen. N&ouml;here Informationen zur Vorgehensweise finden Sie auf unserer 
<a href="<?= xtc_href_link(FILENAME_CONTENT, 'coID=10', $request_type);?>" target="_blank" style="text-decoration:underline;font-weight:bold;font-size:11px;">Infoseite</a> zum 0%-Finanzierungskauf.</p>
<p>Bitte beachten Sie, dass eine 0%-Finanzierung erst ab einem Einkaufswert von 399,00 EUR m÷glich ist und dies nur in Verbindung mit Artikeln, bei denen wir einen Skonto bei Vorauskasse ausweisen.</p>
</div>
<div style="float:right;">
<img src="images/finanzierung_consorsfinanz.jpg" border="0" alt="Nicht warten - einfach finanzieren" title="Nicht warten - einfach finanzieren">
</div>
<div style="clear:both"></div>
<?= $commerz_finanz['table']; ?>
</body>
</html>