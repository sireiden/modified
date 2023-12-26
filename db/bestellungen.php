<?php
/*
 Datei für die Migration der Bestellungen
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
<h1>Migration der Bestellungen</h1>
<h2>Datei für die Migration der Bestellungen inklusiver zugehöriger Tabellen</h2>


<?php
$dblink = new PDO("mysql:host=localhost;", 'root', 'REMOVED4SECURITY');
$dblink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$tables = array('orders', 'orders_products', 'orders_products_attributes','orders_products_download', 'orders_recalculate', 'orders_status_history', 'orders_total');

foreach($tables as $table) {
    ${'create_'.$table.'_stmt'} = $dblink->prepare('show create table mod_ascasa_live.'.$table);
    ${'create_'.$table.'_stmt'}->execute();
    ${'create_'.$table} = ${'create_'.$table.'_stmt'}->fetchAll();
    
    ${'create_'.$table.'_ng_stmt'} = $dblink->prepare('show create table ascasa_ng_db.'.$table);
    ${'create_'.$table.'_ng_stmt'}->execute();
    ${'create_'.$table.'_ng'} = ${'create_'.$table.'_ng_stmt'}->fetchAll();
}

?>
<table>
<tr>
	<th>Bisherige Tabelle</th>
	<th>Neue Tabelle</th>
</tr>
<?php foreach($tables as $table) {?>
	<tr>
		<td>
    		<p>
    	    	<b><?= $table?></b><br>
        		<?= str_replace(',', '<br />', ${'create_'.$table}[0]['Create Table'])?>
        	</p>
	    </td>
    	<td>
			<p>
		    	<b><?= $table?></b><br>
    			<?= str_replace(',', '<br />', ${'create_'.$table.'_ng'}[0]['Create Table'])?>
    	</p>
    	</td>
	</tr>
<?php } ?>
</table>
<h2>Notwendige Modifikationen an der Tabelle orders im Vergleich zum Original</h2>
<br>
ALTER TABLE `ascasa_ng_db`.`orders`<br> 
ADD `customers_telephone_optional` VARCHAR(32) NULL AFTER `customers_telephone`, <br>
ADD `invoice_date` BIGINT(20) NULL DEFAULT '0' AFTER `orders_ident_key`,<br> 
ADD `csv_exported` TINYINT(1) NOT NULL DEFAULT '0' AFTER `invoice_date`, <br>
ADD `referer` TEXT NULL AFTER `csv_exported`,<br> 
ADD `idealo_order_number` VARCHAR(40) NOT NULL AFTER `referer`,<br> 
ADD `amazon_order_id` VARCHAR(255) NOT NULL AFTER `idealo_order_number`,<br> 
ADD `amazon_contract_id` VARCHAR(255) NULL AFTER `amazon_order_id`, <br>
ADD `contact_bewertung` TINYINT(1) NOT NULL DEFAULT '0' AFTER `amazon_contract_id`,<br> 
ADD `templateset` VARCHAR(50) NOT NULL AFTER `conctact_bewertung`,<br> 
ADD `kvnummer` VARCHAR(20) NOT NULL AFTER `templateset`;<br>

<h2>Notwendige Modifikationen an der Tabelle orders_products im Vergleich zum Original</h2>
<br>
ALTER TABLE `ascasa_ng_db`.`orders_products`<br> 
ADD `AmazonOrderItemCode` VARCHAR(255) NOT NULL AFTER `products_weight`,<br> 
ADD `AmazonShippingCosts` DECIMAL(10,2) NOT NULL AFTER `AmazonOrderItemCode`,<br> 
ADD `AmazonCoupon` DECIMAL(10,2) NOT NULL AFTER `AmazonShippingCosts`;<br>
<br>
<?php 

