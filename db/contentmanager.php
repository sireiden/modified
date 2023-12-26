<?php
?>
<html>
<head>
<style type="text/css">
html,body { font-family:Verdana; font-size:14px; margin:0 auto; height:100%; width:100%; }
h1 {margin-left:10px;font-size:20px;}
h2 {margin-left:10px;font-size:18px;}
h3 {margin-left:10px;font-size:16px;}
h4 {margin-left:10px;font-size:15px;}
.textcompare{ margin:10px 10px 15px; padding: 7px 4px 7px 6px; width:90%; background-color: #E8E8E8; border:1px solid black}
table {	margin:10px 10px 15px; width: 90%; text-align: left; border:1px solid #000000; border-collapse:collapse; vertical-align:top;}
table th { padding: 7px 4px 7px 6px; vertical-align: top; border:1px solid #000000;font-weight:bold;background-color: #e8e8e8;}
table td { padding: 7px 4px 7px 6px; vertical-align: top; border:1px solid #000000;}
hr { border: 2px solid red; margin:10px 10px 15px; width: 90%; }
</style>
</head>
<body>
<h1>Migration des Content Managers</h1>
<h2>Datei für die Migration aktuell hinterlegten Inhalte</h2>
<h3>Folgende Content-IDs sind System-IDs und müssen angepasst werden:</h3>
Folgende IDs sind identisch:<br>
Zahlung & Versand<br> 
Privatsphäre und Datenschutz<br>	
Unsere AGB<br>	
Impressum<br> 
Index<br> 
Gutschein<br>
Kontakt<br> 
Sitemap<br>
<br>
Folgende IDs müssen angepasst werden:<br>
<br>
Widerrufsrecht & Widerrufsformular<br> 
-> Erw. Konfiguration -> Zusatzmodule -> Widerrufsrecht<br>	
-> ALTERNATIV (UPDATE configuration SET configuration_value = '70' WHERE configuration_key = 'REVOCATION_ID' <br> 
Lieferzeit<br> 
-> Erw. Konfiguration -> Zusatzmodule -> Lieferzeit	<br>
-> ALTERNATIV (UPDATE configuration SET configuration_value = '108' WHERE configuration_key = 'SHIPPING_STATUS_INFOS'<br> 
Bearbeiten E-Mail Signatur<br> 
-> Konfiguration -> E-Mail Optionen -> E-Mail Signatur<br>	
-> ALTERNATIV (UPDATE configuration SET configuration_value = '108' WHERE configuration_key = 'EMAIL_SIGNATURE_ID' <br>
Rechnungsdaten<br> 
-> Erw. Konfiguration -> Zusatzmodule -> Rechnungsdaten<br>	
-> ALTERNATIV (UPDATE configuration SET configuration_value = '108' WHERE configuration_key = 'INVOICE_INFOS'<br> 
Mein Schnellkauf<br>
-> Nicht in Verwendung ?<br>

<h3>Da sich die Struktur der Datenbank nur marginal unterscheidet, findet kein Löschen der Tabelle statt</h3>
<h3>Die Migration kann durch Aktivierung des Scripts erfolgen</h3>
<?php
$dblink = new PDO("mysql:host=localhost;", 'root', 'REMOVED4SECURITY');
$dblink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$truncate_order_status_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`content_manager`');
$truncate_order_status_stmt->execute();

$insert_content_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.content_manager SET
                                              content_id              = :content_id,
                                              categories_id   = :categories_id,
                                              parent_id = :parent_id,
                                              group_ids    = :group_ids,
                                              languages_id      = :languages_id,
                                              content_title       = :content_title,
                                              content_heading = :content_heading,
                                              content_text = :content_text,
                                              sort_order = :sort_order,
                                              file_flag = :file_flag,
                                              content_file = :content_file,
                                              content_status = :content_status,
                                              content_group = :content_group,
                                              content_delete = :content_delete,
                                              content_meta_title = :content_meta_title,
                                              content_meta_description = :content_meta_description,
                                              content_meta_keywords = :content_meta_keywords,
                                              content_meta_robots = "",
                                              content_active = 1,
                                              content_group_index = 0,
                                              date_added = NOW()');

$select_content_stmt = $dblink->prepare('SELECT * FROM mod_ascasa_live.content_manager');
$select_content_stmt->execute();

while($content = $select_content_stmt->fetch(PDO::FETCH_ASSOC)) {
    $insert_content_stmt->execute($content);
}

$insert_fixed_content_stmt = $dblink->prepare("INSERT INTO `ascasa_ng_db`.`content_manager` (`content_id`, `languages_id`, `content_title`, `content_heading`, `content_text`, `sort_order`, `file_flag`, `content_file`, `content_status`, `content_group`, `content_delete`, `content_active`)
  SELECT MAX(content_id)+1, '2','E-Mail Signatur','','Firma<br />Adresse<br />Ort<br />Homepage<br />E-Mail:<br />Fon:<br />Fax:<br />USt-IdNr.:<br />Handelsregister<br />Gesch&auml;ftsf&uuml;hrer:','0','1','','0',MAX(content_group)+1,'0','0' FROM ascasa_ng_db.content_manager;");
$insert_fixed_content_stmt->execute();

$insert_fixed_content_stmt = $dblink->prepare("INSERT INTO `ascasa_ng_db`.`content_manager` (`content_id`, `languages_id`, `content_title`, `content_heading`, `content_text`, `sort_order`, `file_flag`, `content_file`, `content_status`, `content_group`, `content_delete`, `content_active`)
  SELECT MAX(content_id)+1, '2','Rechnungsdaten','Firma - Adresse - PLZ Stadt','Firma<br/>Adresse<br/>PLZ Stadt<br/><br/>Tel: 0123456789<br/>E-Mail: info@shop.de<br/>www: www.shopurl.de<br/><br/>IBAN: DE123456789011<br/>BIC: BYLEMDNE1DE<br/><br/>Diese Daten k&ouml;nnen im Content Manager ge&auml;ndert werden.','0','1','','0',MAX(content_group)+1,'0','0' FROM ascasa_ng_db.content_manager;");
$insert_fixed_content_stmt->execute();

$insert_fixed_content_stmt = $dblink->prepare("INSERT INTO `ascasa_ng_db`.`content_manager` (`content_id`, `languages_id`, `content_title`, `content_heading`, `content_text`, `sort_order`, `file_flag`, `content_file`, `content_status`, `content_group`, `content_delete`, `content_active`)
    SELECT MAX(content_id)+1, '2','Mein Schnellkauf','Mein Schnellkauf','<p>Mit &bdquo;Mein Schnellkauf&ldquo; k&ouml;nnen Sie Ihre Bestellung jetzt noch einfacher und vor allem schneller t&auml;tigen.</p><p>Sie finden auf der Detailseite eines jeden Artikels unterhalb des Warenkorb-Buttons die Schaltfl&auml;che &bdquo;<strong>Mein Schnellkauf aktivieren</strong>&ldquo;, wo Sie die f&uuml;r den Schnellkauf gew&uuml;nschte Versandart, Bezahlart, Versandadresse und Rechnungsadresse hinterlegen m&uuml;ssen um die Funktion zu aktivieren.<br />Anschlie&szlig;end finden Sie an den folgenden Stellen im Shop den Button zur Bestellung mit &bdquo;<strong>Mein Schnellkauf</strong>&ldquo;:</p><ul><li>Artikel-Detailseite</li><li>Warenkorb</li><li>Mein Konto &raquo; Meine Bestellungen</li><li>Mein Konto &raquo; Meine Bestellungen &raquo; Detailseite der Bestellung</li></ul><p>Um die Voreinstellungen f&uuml;r &bdquo;Mein Schnellkauf&ldquo; zu &auml;ndern, gehen Sie auf &bdquo;Mein Konto&ldquo; &raquo; &bdquo;<strong>Mein Schnellkauf bearbeiten</strong>&ldquo;.</p>','0','1','','0',MAX(content_group)+1,'0','0' FROM ascasa_ng_db.content_manager;");
$insert_fixed_content_stmt->execute();
