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
<h1>Migration der Aktionen</h1>
<h2>Datei für die Migration des Bereichs Admin -> Artikel > Aktionen
Verwantwortlich für den Slider auf der Startseite sowie die ggf. vorhandenen Aktions-Unterseiten</h2>
<h3>Die Struktur der Tabelle wurde einheitlich auf Englisch und auf modified Namenskonventionen geändert, damit diese künftig einheitlich sind</h3>


<?php
$dblink = new PDO("mysql:host=localhost;", 'root', 'REMOVED4SECURITY');
$dblink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$create_aktionen_stmt = $dblink->prepare('show create table mod_ascasa_live.aktionen');
$create_aktionen_stmt->execute(array());
$create_aktionen = $create_aktionen_stmt->fetchAll();

$create_promotions_stmt = $dblink->prepare('show create table ascasa_ng_db.main_promotions');
$create_promotions_stmt->execute(array());
$create_promotions = $create_promotions_stmt->fetchAll();
?>
<table>
  <tr>
    <th>Bisherige Tabelle</th>
    <th>Neue Tabelle</th>
  </tr>
  <tr>
    <td><?= str_replace(',', '<br />', $create_aktionen[0]['Create Table'])?></td>
    <td><?= str_replace(',', '<br />', $create_promotions[0]['Create Table'])?></td>
  </tr>
</table>
<div class="textcompare">
	<strong>Zugriff im Adminbereich erlauben</strong><br>
	ALTER TABLE `ascasa_ng_db`.`admin_access` DROP `main_promotions`;<br>
  ALTER TABLE `ascasa_ng_db`.`admin_access` ADD `main_promotions` INT(0) NOT NULL DEFAULT '0';<br>
  UPDATE `ascasa_ng_db`.`admin_access` SET main_promotions = 1;  
</div>
 
<?php  
/*  
$drop_promotions_stmt = $dblink->prepare('DROP TABLE `ascasa_ng_db`.`main_promotions`');
$drop_promotions_stmt->execute(array());

$create_promotion_stmt = $dblink->prepare("CREATE TABLE `ascasa_ng_db`.`main_promotions` ( `id` INT(10) NOT NULL AUTO_INCREMENT , `sort_order` INT(10) NOT NULL , `name` VARCHAR(255) NOT NULL , `image` VARCHAR(255) NOT NULL , `description` TEXT NOT NULL , `manufacturers_id` INT(10) NOT NULL , `status` TINYINT(1) NOT NULL DEFAULT '1' , `meta_title` VARCHAR(255) NOT NULL , `meta_description` VARCHAR(255) NOT NULL , `meta_keywords` VARCHAR(255) NOT NULL , `date_added` DATETIME NOT NULL , `last_modified` DATETIME NULL , PRIMARY KEY (`id`)) ENGINE = MyISAM");
$create_promotion_stmt->execute(array()); 
 
$insert_promotions_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.main_promotions SET
                                              id                = :id,
                                              sort_order        = :sort_order,
                                              name              = :name,
                                              image             = :image,
                                              description       = :description,
                                              manufacturers_id  = :manufacturers_id,
                                              status            = :status,
                                              meta_title        = :meta_title,
                                              meta_description  = :meta_description,
                                              meta_keywords     = :meta_keywords,
                                              date_added        = :date_added,
                                              last_modified     = :last_modified');

$promotions_stmt = $dblink->prepare('SELECT * FROM mod_ascasa_live.aktionen ORDER BY ID ASC');
$promotions_stmt->execute(array());
while($promotion = $promotions_stmt->fetch()) {
  $insert_promotions_stmt->execute(array(
    ':id'               => $promotion['id'],
    ':sort_order'       => $promotion['position'],
    ':name'             => $promotion['name'],
    ':image'            => $promotion['bild'],
    ':description'      => $promotion['text'],
    ':manufacturers_id' => $promotion['hersteller'],
    ':status'           => $promotion['aktiv'],
    ':meta_title'       => $promotion['meta_title'],
    ':meta_description' => $promotion['meta_description'], 
    ':meta_keywords'    => $promotion['meta_keywords'], 
    ':date_added'       => $promotion['date_added'],  
    ':last_modified'    => $promotion['last_modified']               
  ));
}
*/
?>

