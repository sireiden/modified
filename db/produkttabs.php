<?php
/*
 Datei für die Migration der Produkttabs
 Verwantwortlich für den Slider auf der Startseite sowie die ggf. vorhandenen Aktions-Unterseiten


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
<h1>Migration der Produkttabs</h1>
<h2>Datei für die Migration der Produkttabs
Verwantwortlich für die Produkttabs inkl. der bisher separaten Tabs für Energielabel und Produktdatenblatt</h2>
<h3>Die Struktur der Tabelle wurde deutlich geändert, damit beliebig viele Produkttabs flexibel anzulegen und zu löschen sind. Die Migration kann durch Aktivierung des Scripts erfolgen, hier werden die Konventionen dann entsprechend umgeschrieben.</h3>


<?php
$dblink = new PDO("mysql:host=localhost;", 'root', 'REMOVED4SECURITY');
$dblink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$create_products_stmt = $dblink->prepare('show create table mod_ascasa_live.products');
$create_products_stmt->execute();
$create_products = $create_products_stmt->fetchAll();

$create_products_description_stmt = $dblink->prepare('show create table mod_ascasa_live.products_description');
$create_products_description_stmt->execute();
$create_products_description = $create_products_description_stmt->fetchAll();

$create_products_ng_stmt = $dblink->prepare('show create table ascasa_ng_db.products');
$create_products_ng_stmt->execute();
$create_products_ng = $create_products_ng_stmt->fetchAll();

$create_products_tabs_ng_stmt = $dblink->prepare('show create table ascasa_ng_db.products_tabs');
$create_products_tabs_ng_stmt->execute();
$create_products_tabs_ng = $create_products_tabs_ng_stmt->fetchAll();


?>
<table>
  <tr>
    <th>Bisherige Tabellen</th>
    <th>Neue Tabelle</th>
  </tr>
  <tr>
    <td>
    	<p><b>products</b><br><?= str_replace(',', '<br />', $create_products[0]['Create Table'])?></p>
    	<p><b>products_description</b><br><?= str_replace(',', '<br />', $create_products_description[0]['Create Table'])?></p>
    </td>
    <td>
    	<p><b>products</b><br><?= str_replace(',', '<br />', $create_products_ng[0]['Create Table'])?></p>
    	<p><b>products_tabs</b><br><?= str_replace(',', '<br />', $create_products_tabs_ng[0]['Create Table'])?></p>
    </td>
  </tr>
</table>
<?php 

$drop_products_tabs_stmt = $dblink->prepare('DROP TABLE `ascasa_ng_db`.`products_tabs`');
$drop_products_tabs_stmt->execute();

$create_products_tabs_stmt = $dblink->prepare("CREATE TABLE `ascasa_ng_db`.`products_tabs` (
  `products_id` int(11) NOT NULL,
  `tab_id` int(5) NOT NULL,
  `sort_order` tinyint(4) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `tab_title` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `tab_text` text COLLATE latin1_german1_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;");
$create_products_tabs_stmt->execute(array()); 

$products_tabs_index_stmt = $dblink->prepare("ALTER TABLE `ascasa_ng_db`.`products_tabs`
  ADD UNIQUE KEY `products_id` (`products_id`,`tab_id`);");
$products_tabs_index_stmt->execute(array()); 

*/

$truncate_products_tabs_stmt = $dblink->prepare('TRUNCATE TABLE `ascasa_ng_db`.`products_tabs`');
$truncate_products_tabs_stmt->execute();


$products_tabs_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.products_tabs SET
                                              products_id              = :products_id,
                                              tab_id                   = :tab_id,
                                              sort_order               = :sort_order,
                                              status                   = :status,
                                              tab_title                = :tab_title,
                                              tab_text                 = :tab_text
                                              ');
                                                                                             

//Migration Tabelle manufacturers_info und manufacturers_pages
$products_stmt = $dblink->prepare('SELECT * FROM mod_ascasa_live.products ORDER BY products_id ASC');
$products_stmt->execute(array());
while($product = $products_stmt->fetch()) {
    $products_description_stmt=$dblink->prepare('SELECT * FROM mod_ascasa_live.products_description WHERE products_id = :products_id');
    $products_description_stmt->execute(array(':products_id' => $product['products_id']));
    $products_description=$products_description_stmt->fetch();
    
    $i=1;
    // Einfügen der Energielabel
    if($product['products_efficiency'] == 1 || !empty($products_description['products_efficiency_text'])) {
        $products_tabs_stmt->execute(array(
            ':products_id'  => $product['products_id'],
            ':tab_id'       => $i,
            ':sort_order'   => $i,
            ':status'       => $product['products_efficiency'],
            ':tab_title'    => 'Energielabel',
            ':tab_text'     =>  $products_description['products_efficiency_text']
        )) ;  
        $i++;
    }
    // Einfügen des Produktdatenblatts
    if($product['products_datasheet'] == 1 || !empty($products_description['Produktdatenblatt'])) {
        $products_tabs_stmt->execute(array(
            ':products_id'  => $product['products_id'],
            ':tab_id'       => $i,
            ':sort_order'   => $i,
            ':status'       => $product['products_datasheet'],
            ':tab_title'    => 'Produktdatenblatt',
            ':tab_text'     =>  $products_description['products_datasheet_text']
        )) ;
        $i++;
    }
    
    for($b=1;$b<=5;$b++) {
        if($product['products_tab_'.$b] == 1 || !empty($product['products_tab_'.$b.'_headline']) || !empty($products_description['products_tab_'.$b.'_text'])) {
            $products_tabs_stmt->execute(array(
                ':products_id'  => $product['products_id'],
                ':tab_id'       => $i,
                ':sort_order'   => $i,
                ':status'       => $product['products_tab_'.$b],
                ':tab_title'    => $product['products_tab_'.$b.'_headline'],
                ':tab_text'     => $products_description['products_tab_'.$b.'_text']
            )) ;
            $i++;
        }
    }
}
?>

