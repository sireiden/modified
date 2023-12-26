<?php
/*
 Datei für die Migration der zusätzlichen Produktfelder

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
<h1>Migration der zusätzlichen Produktfelder</h1>
<h2>Datei für die Migration der Produktfelder. Hier wurde das Konzept überarbeitet und statt wie bisher diese in der regularen products Tabelle zu Speichern, erfolgt die Speicherung in einer separaten Tabelle. Es ist somit eine gute Abgrenzung vom Core und Update-Sicherheit gewährleistet.</h2>
<h3>Die Migration kann durch Aktivierung des Scripts erfolgen</h3>

<?php
$dblink = new PDO("mysql:host=localhost;", 'root', 'REMOVED4SECURITY');
$dblink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$create_products_fields_stmt = $dblink->prepare('show create table ascasa_ng_db.products_fields');
$create_products_fields_stmt->execute();
$create_products_fields = $create_products_fields_stmt->fetchAll();


?>
<table>
  <tr>
    <th>Bisherige Tabellen</th>
    <th>Neue Tabelle</th>
  </tr>
  <tr>
    <td>
    	<p>Es gibt keine eigene Tabelle, sondern es wird die bisher die Produkttabelle verwendet</p>
    </td>
    <td>
    	<p><b>products_fields</b><br><?= str_replace(',', '<br />', $create_products_fields[0]['Create Table'])?></p>
    </td>
  </tr>
</table>
<?php 

$drop_products_fields_stmt = $dblink->prepare('DROP TABLE `ascasa_ng_db`.`products_fields`');
$drop_products_fields_stmt->execute();

$create_products_fields_stmt = $dblink->prepare("CREATE TABLE `ascasa_ng_db`.`products_fields` (
  `products_id` int(11) NOT NULL,
  `products_cash_discount` decimal(5,2) NOT NULL DEFAULT '0.00',
  `products_efficiency_class` varchar(20) COLLATE latin1_german1_ci NOT NULL,
  `products_shippinginfo` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `products_best_price` tinyint(1) NOT NULL DEFAULT '0',
  `products_directbuy` tinyint(1) NOT NULL DEFAULT '0',
  `products_directbuy_range` decimal(15,4) NOT NULL DEFAULT '0.00',
  `products_directbuy_fulfillment` varchar(100) COLLATE latin1_german1_ci NOT NULL,
  `products_specialrequest` tinyint(1) NOT NULL DEFAULT '0',
  `products_manufacturers_pages` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `products_5year_warranty` tinyint(1) NOT NULL DEFAULT '0',
  `products_warranty_conditions` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;");
$create_products_fields_stmt->execute(array());

$create_products_fields_index_stmt = $dblink->prepare("ALTER TABLE `ascasa_ng_db`.`products_fields`
  ADD PRIMARY KEY (`products_id`),
  ADD KEY `products_cash_discount` (`products_cash_discount`);");
$create_products_fields_index_stmt->execute(array());

*/

$truncate_products_fields_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`products_fields`');
$truncate_products_fields_stmt->execute();

$products_fields_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.products_fields SET
                                              products_id              = :products_id,
                                              products_cash_discount   = :products_cash_discount,
                                              products_efficiency_class = :products_efficiency_class,
                                              products_shippinginfo    = :products_shippinginfo,
                                              products_best_price      = :products_best_price,
                                              products_manufacturers_pages = :products_manufacturers_pages,
                                              products_5year_warranty = 0,
                                              products_warranty_conditions = :products_warranty_conditions 
                                              ');


$products_stmt = $dblink->prepare('SELECT * FROM mod_ascasa_live.products ORDER BY products_id ASC');
$products_stmt->execute(array());
while($product = $products_stmt->fetch()) {
    $product['products_efficiency_class'] = (is_null($product['products_efficiency_class']) ? '' : $product['products_efficiency_class']);
    $products_manufacturers_pages = '';
    for($i=1; $i<=5;$i++) {
        if($product['manufacturers_additional_info_'.$i] == 1) {
            $products_manufacturers_pages .= $i.'|';
        }
    }
    
    $products_fields_stmt->execute(array(
        ':products_id'  => $product['products_id'],
        ':products_cash_discount'      => $product['products_cash_discount'],
        ':products_efficiency_class'   => $product['products_efficiency_class'],
        ':products_shippinginfo'       => $product['products_shippinginfo'],
        ':products_best_price'         => $product['products_best_price'],
        ':products_manufacturers_pages' => $products_manufacturers_pages,
        ':products_warranty_conditions' => $product['garantie_banner']
    )) ;
}

?>


