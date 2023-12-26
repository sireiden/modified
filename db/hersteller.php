<?php
/*
 Datei für die Migration des Bereichs Admin -> Artikel > Aktionen
 Verwantwortlich für den Slider auf der Startseite sowie die ggf. vorhandenen Aktions-Unterseiten
 */

/*
 Erstellern der Tabelle in der Datenbank.
 Struktur wurde verändert, damit diese künftig einheitlich ist
 */

/*
 CREATE TABLE `ascasa_ng_db`.*`main_promotions` ( `id` INT(10) NOT NULL AUTO_INCREMENT , `sort_order` INT(10) NOT NULL , `name` VARCHAR(255) NOT NULL , `image` VARCHAR(255) NOT NULL , `description` TEXT NOT NULL , `manufacturers_id` INT(10) NOT NULL , `status` TINYINT(1) NOT NULL DEFAULT '1' , `meta_title` VARCHAR(255) NOT NULL , `meta_description` VARCHAR(255) NOT NULL , `meta_keywords` VARCHAR(255) NOT NULL , `date_added` DATETIME NOT NULL , `last_modified` DATETIME NULL , PRIMARY KEY (`id`)) ENGINE = MyISAM;
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
<h1>Migration der Herstellerverwaltung</h1>
<h2>Datei für die Migration der Hersteller inkl. der eigenen Herstellerseiten
Verwantwortlich für die Hersteller, den Hersteller-Slider sowie die zusätzlichen Herstellersseiten</h2>
<h3>Die Struktur der Tabelle wurde deutlich geändert, damit beliebig viele Herstellersseiten flexibel anzulegen und zu löschen sind. Die Migration kann durch Aktivierung des Scripts erfolgen, hier werden die Konventionen dann entsprechend umgeschrieben.</h3>


<?php
$dblink = new PDO("mysql:host=localhost;", 'root', 'REMOVED4SECURITY');
$dblink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$create_manufacturers_stmt = $dblink->prepare('show create table mod_ascasa_live.manufacturers');
$create_manufacturers_stmt->execute();
$create_manufacturers = $create_manufacturers_stmt->fetchAll();

$create_manufacturers_info_stmt = $dblink->prepare('show create table mod_ascasa_live.manufacturers_info');
$create_manufacturers_info_stmt->execute();
$create_manufacturers_info = $create_manufacturers_info_stmt->fetchAll();

$create_manufacturers_ng_stmt = $dblink->prepare('show create table ascasa_ng_db.manufacturers');
$create_manufacturers_ng_stmt->execute();
$create_manufacturers_ng = $create_manufacturers_ng_stmt->fetchAll();

$create_manufacturers_info_ng_stmt = $dblink->prepare('show create table ascasa_ng_db.manufacturers_info');
$create_manufacturers_info_ng_stmt->execute();
$create_manufacturers_info_ng = $create_manufacturers_info_ng_stmt->fetchAll();

$create_manufacturers_pages_ng_stmt = $dblink->prepare('show create table ascasa_ng_db.manufacturers_pages');
$create_manufacturers_pages_ng_stmt->execute();
$create_manufacturers_pages_ng = $create_manufacturers_pages_ng_stmt->fetchAll();

?>
<table>
  <tr>
    <th>Bisherige Tabellen</th>
    <th>Neue Tabelle</th>
  </tr>
  <tr>
    <td>
    	<p><b>manufacturers</b><br><?= str_replace(',', '<br />', $create_manufacturers[0]['Create Table'])?></p>
    	<p><b>manufacturers_info</b><br><?= str_replace(',', '<br />', $create_manufacturers_info[0]['Create Table'])?></p>
    </td>
    <td>
    	<p><b>manufacturers</b><br><?= str_replace(',', '<br />', $create_manufacturers_ng[0]['Create Table'])?></p>
    	<p><b>manufacturers_info</b><br><?= str_replace(',', '<br />', $create_manufacturers_info_ng[0]['Create Table'])?></p>
    	<p><b>manufacturers_pages</b><br><?= str_replace(',', '<br />', $create_manufacturers_pages_ng[0]['Create Table'])?></p>
    </td>
  </tr>
</table>
<?php 

$drop_manufacturers_stmt = $dblink->prepare('DROP TABLE `ascasa_ng_db`.`manufacturers`');
$drop_manufacturers_stmt->execute();
$drop_manufacturers_info_stmt = $dblink->prepare('DROP TABLE `ascasa_ng_db`.`manufacturers_info`');
$drop_manufacturers_info_stmt->execute();
$drop_manufacturers_pages_stmt = $dblink->prepare('DROP TABLE `ascasa_ng_db`.`manufacturers_pages`');
$drop_manufacturers_pages_stmt->execute();


$create_manufacturers_stmt = $dblink->prepare("CREATE TABLE `ascasa_ng_db`.`manufacturers` (
    `manufacturers_id` int(11) NOT NULL AUTO_INCREMENT,
    `manufacturers_name` varchar(64) COLLATE latin1_german1_ci NOT NULL,
    `manufacturers_image` varchar(64) COLLATE latin1_german1_ci DEFAULT NULL,
    `manufacturers_status` tinyint(1) NOT NULL DEFAULT '1',
    `manufacturers_slider_status` tinyint(1) NOT NULL DEFAULT '0',
    `date_added` datetime DEFAULT NULL,
    `last_modified` datetime DEFAULT NULL,
    PRIMARY KEY (`manufacturers_id`),
    KEY `idx_manufacturers_name` (`manufacturers_name`)
    ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci");
$create_manufacturers_stmt->execute(array()); 

$create_manufacturers_info_stmt = $dblink->prepare("CREATE TABLE `ascasa_ng_db`.`manufacturers_info` (
  `manufacturers_id` int(11) NOT NULL,
  `languages_id` int(11) NOT NULL,
  `manufacturers_description` text COLLATE latin1_german1_ci,
  `manufacturers_meta_title` text COLLATE latin1_german1_ci NOT NULL,
  `manufacturers_meta_description` text COLLATE latin1_german1_ci NOT NULL,
  `manufacturers_meta_keywords` text COLLATE latin1_german1_ci NOT NULL,
  `manufacturers_url` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `url_clicked` int(5) NOT NULL DEFAULT '0',
  `date_last_click` datetime DEFAULT NULL,
  PRIMARY KEY (`manufacturers_id`,`languages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci");
$create_manufacturers_info_stmt->execute(array()); 

$create_manufacturers_pages_stmt = $dblink->prepare("CREATE TABLE `ascasa_ng_db`.`manufacturers_pages` (
  `manufacturers_id` int(11) DEFAULT NULL,
  `page_id` int(5) NOT NULL DEFAULT '0',
  `sort_order` int(5) NOT NULL DEFAULT '0',
  `page_title` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `page_text` longtext COLLATE latin1_german1_ci NOT NULL,
  `page_meta_title` varchar(200) COLLATE latin1_german1_ci NOT NULL,
  `page_meta_description` varchar(200) COLLATE latin1_german1_ci NOT NULL,
  `page_meta_keywords` varchar(200) COLLATE latin1_german1_ci NOT NULL,
  KEY `manufacturers_id` (`manufacturers_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci");
$create_manufacturers_pages_stmt->execute(array());

$insert_manufacturers_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.manufacturers SET
                                              manufacturers_id              = :manufacturers_id,
                                              manufacturers_name            = :manufacturers_name,
                                              manufacturers_image           = :manufacturers_image,
                                              manufacturers_status          = :manufacturers_status,
                                              manufacturers_slider_status   = :manufacturers_slider_status,
                                              date_added                    = :date_added,
                                              last_modified                 = :last_modified');

$insert_manufacturers_info_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.manufacturers_info SET
                                              manufacturers_id                  = :manufacturers_id,
                                              languages_id                      = :languages_id,
                                              manufacturers_meta_title          = :manufacturers_meta_title,
                                              manufacturers_meta_keywords       = :manufacturers_meta_keywords,
                                              manufacturers_meta_description    = :manufacturers_meta_description,
                                              manufacturers_url                 = :manufacturers_url,
                                              url_clicked                       = :url_clicked,
                                              date_last_click                   = :date_last_click');

$insert_manufacturers_pages_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.manufacturers_pages SET
                                              manufacturers_id              = :manufacturers_id,
                                              page_id                       = :page_id,
                                              sort_order                    = :sort_order,
                                              page_title                    = :page_title,
                                              page_text                     = :page_text,
                                              page_meta_title               = :page_meta_title,
                                              page_meta_description         = :page_meta_description,                                              
                                              page_meta_keywords            = :page_meta_keywords
                                              ');
                                                                                             

// Migration Tabelle manufacturers
$manufacturers_stmt = $dblink->prepare('SELECT * FROM mod_ascasa_live.manufacturers ORDER BY manufacturers_id ASC');
$manufacturers_stmt->execute(array());
while($manufacturer = $manufacturers_stmt->fetch()) {
    $insert_manufacturers_stmt->execute(array(
      ':manufacturers_id'               => $manufacturer['manufacturers_id'],
      ':manufacturers_name'             => $manufacturer['manufacturers_name'],
      ':manufacturers_image'            => $manufacturer['manufacturers_image'],
      ':manufacturers_status'           => $manufacturer['manufacturers_active'],
      ':manufacturers_slider_status'    => $manufacturer['manufacturers_slideshow'],
      ':date_added'                     => $manufacturer['date_added'],
      ':last_modified'                  => $manufacturer['last_modified']             
  ));
}
//Migration Tabelle manufacturers_info und manufacturers_pages
$manufacturers_info_stmt = $dblink->prepare('SELECT * FROM mod_ascasa_live.manufacturers_info ORDER BY manufacturers_id ASC');
$manufacturers_info_stmt->execute(array());
while($manufacturer_info = $manufacturers_info_stmt->fetch()) {
    $insert_manufacturers_info_stmt->execute(array(
        ':manufacturers_id'                 => $manufacturer_info['manufacturers_id'],
        ':languages_id'                     => $manufacturer_info['languages_id'],
        ':manufacturers_meta_title'         => $manufacturer_info['manufacturers_meta_title'],
        ':manufacturers_meta_description'   => $manufacturer_info['manufacturers_meta_description'],
        ':manufacturers_meta_keywords'      => $manufacturer_info['manufacturers_meta_keywords'],
        ':manufacturers_url'                => $manufacturer_info['manufacturers_url'],
        ':url_clicked'                      => $manufacturer_info['url_clicked'],
        ':date_last_click'                  => $manufacturer_info['date_last_click']
    ));
    
    for($i=1;$i<=5;$i++) {
        if(!empty($manufacturer_info['additional_info_'.$i.'_title']) || !empty($manufacturer_info['additional_info_'.$i.'_text'])) {
            $insert_manufacturers_pages_stmt->execute(array(
                ':manufacturers_id'         => $manufacturer_info['manufacturers_id'],
                ':page_id'                  => $i,
                ':sort_order'               => $i,
                ':page_title'               => $manufacturer_info['additional_info_'.$i.'_title'],
                ':page_text'                => $manufacturer_info['additional_info_'.$i.'_text'],
                ':page_meta_title'          => $manufacturer_info['additional_info_'.$i.'_meta_title'],
                ':page_meta_description'    => $manufacturer_info['additional_info_'.$i.'_meta_description'],
                ':page_meta_keywords'       => $manufacturer_info['additional_info_'.$i.'_meta_keywords']
            ));
        }
    }
}
?>

