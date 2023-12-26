<?php
/*
 Datei für die Migration der Produktzubehörs
 */


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
<h1>Migration der Zubehörartikel</h1>
<h2>Datei für die Migration der Zubehörartikel</h2>
<h3>Die Tabelle in der Datenbank kann unverändert übernommen werden, Die Migration kann durch Aktivierung des Scripts erfolgen,</h3>

<?php
$dblink = new PDO("mysql:host=localhost;", 'root', 'REMOVED4SECURITY');
$dblink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$create_products_attachments_stmt = $dblink->prepare('show create table mod_ascasa_live.products_to_attachments');
$create_products_attachments_stmt->execute();
$create_products_attachments = $create_products_attachments_stmt->fetchAll();


?>
<table>
  <tr>
    <th>Bisherige Tabellen</th>
    <th>Neue Tabelle</th>
  </tr>
  <tr>
    <td>
    	<p><b>products</b><br><?= str_replace(',', '<br />', $create_products_attachments[0]['Create Table'])?></p>
    </td>
    <td>
    	<p><b>products_to_attachments</b><br><?= str_replace(',', '<br />', $create_products_attachments[0]['Create Table'])?></p>
    </td>
  </tr>
</table>
<?php 

$drop_products_tabs_stmt = $dblink->prepare('DROP TABLE IF EXISTS `ascasa_ng_db`.`products_to_attachments`');
$drop_products_tabs_stmt->execute();

$create_products_attachments_stmt = $dblink->prepare("CREATE TABLE `ascasa_ng_db`.`products_to_attachments` (
  `products_id` int(11) NOT NULL DEFAULT '0',
  `attachment_id` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
$create_products_attachments_stmt->execute(array());

$create_products_attachments_index_stmt = $dblink->prepare("ALTER TABLE `ascasa_ng_db`.`products_to_attachments`
  ADD PRIMARY KEY (`products_id`,`attachment_id`),
  ADD KEY `idx_attachment_id` (`attachment_id`);");
$create_products_attachments_index_stmt->execute(array()); 


$insert_products_attachments_index_stmt = $dblink->prepare("INSERT INTO `ascasa_ng_db`.`products_to_attachments` SELECT * FROM `mod_ascasa_live`.`products_to_attachments`");
$insert_products_attachments_index_stmt->execute(array());