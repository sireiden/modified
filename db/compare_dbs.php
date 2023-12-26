<?php
$dblink = new PDO("mysql:host=localhost;", 'root', 'REMOVED4SECURITY');
$dblink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

// Live-Datebank
$live_db_tables = array();
$live_db_counts = array();
$dev_db_tables = array();
$dev_db_counts = array();

$tables_stmt = $dblink->prepare("SELECT TABLE_NAME as tbl,SUM(TABLE_ROWS) as count FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = :table GROUP BY TABLE_NAME;");
$tables_stmt->execute(array(':table' => 'mod_ascasa_live'));
while($table = $tables_stmt->fetch(PDO::FETCH_ASSOC)) {
  array_push($live_db_tables, $table['tbl']);
  $live_db_counts[$table['tbl']] = $table['count'];
}
$tables_stmt->closeCursor();
$tables_stmt->execute(array(':table' => 'ascasa_ng_db'));
while($table = $tables_stmt->fetch(PDO::FETCH_ASSOC)) {
  array_push($dev_db_tables, $table['tbl']);
  $dev_db_counts[$table['tbl']] = $table['count'];
}


// Dev-DB
$dev_db = array();
$db_stmt = $dblink->prepare('SHOW tables FROM ascasa_ng_db');
$db_stmt->execute();
while($db = $db_stmt->fetch()) {
  array_push($dev_db, $db[0]);
}
// Nur in Live-DB
$live_only = array_diff($live_db_tables, $dev_db_tables);
// Nur in Dev-DB
$dev_only = array_diff($dev_db_tables, $live_db_tables);
// Schnittmenge
$live_dev = array_intersect($live_db_tables, $dev_db_tables);

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
<h1>Datenbankanalyse</h1>
<h2>Datenbankvergleich - Datenbankunterschiede</h2>
<h4>Text</h4>
<div class="textcompare">
	<strong>Nur in der Live-Datenbank</strong><br>
	<?= implode($live_only, ', ')?>
</div>	
<div class="textcompare">
	<strong>Nur in der Dev-Datenbank</strong><br>
	<?= implode($dev_only, ', ')?>
</div>	
<div class="textcompare">
	<strong>Beide Datenbanken</strong><br>
	<?= implode($live_dev, ', ')?>
</div>
<h4>Tabelle</h4>
<table>
	<tr>
		<th>Live-DB</th>
		<th>Dev-DB</th>
		<th>Gemeinsam</th>
	</tr>
	<tr>
		<td><?= implode($live_only, '<br />')?></td>
		<td><?= implode($dev_only, '<br />')?></td>
		<td><?= implode($live_dev, '<br />')?></td>
	</tr>
</table>
<hr>
<h2>Tabellenunterschiede der existierenden Tabellen</h2>
<?php 
foreach($live_dev as $table) {
    $live_tbl = array();
    $dev_tbl = array();
    $live_dev_tbl = array();
    
    $db_stmt = $dblink->prepare('select column_name from information_schema.columns where table_schema = "mod_ascasa_live" and table_name="'.$table.'"');  
    $db_stmt->execute();
    while($db = $db_stmt->fetch()) {
       array_push($live_tbl, $db[0]);
    }
    
    $db_stmt = $dblink->prepare('select column_name from information_schema.columns where table_schema = "ascasa_ng_db" and table_name="'.$table.'"');
    $db_stmt->execute();
    while($db = $db_stmt->fetch()) {
        array_push($dev_tbl, $db[0]);
    }
    // Nur in Live-DB
    $live_only = array_diff($live_tbl, $dev_tbl);
    // Nur in Dev-DB
    $dev_only = array_diff($dev_tbl, $live_tbl);
    // Schnittmenge
    $live_dev_tbl = array_intersect($live_tbl, $dev_tbl);
    ?>
    <h3>Tabelle: <?= $table ?></h3>
	<h4>Anzahl Datensätze Live-DB:<?= $live_db_counts[$table]; ?><br>Anzahl Datensätze Dev-DB:<?= $dev_db_counts[$table]; ?></h4>
    <h4>Text</h4>
    <div class="textcompare">
    	<strong>Nur in der Live-Datenbank</strong><br>
    	<?= implode($live_only, ', ')?>
    </div>	
    <div class="textcompare">
    	<strong>Nur in der Dev-Datenbank</strong><br>
    	<?= implode($dev_only, ', ')?>
    </div>	
    <div class="textcompare">
    	<strong>Beide Datenbanken</strong><br>
    	<?= implode($live_dev_tbl, ', ')?>
    </div>
    <h4>Tabelle</h4>
    <table>
    	<tr>
    		<th>Live-DB</th>
    		<th>Dev-DB</th>
    		<th>Gemeinsam</th>
    	</tr>
    	<tr>
    		<td><?= implode($live_only, '<br />')?></td>
    		<td><?= implode($dev_only, '<br />')?></td>
    		<td><?= implode($live_dev_tbl, '<br />')?></td>
    	</tr>
    </table>
    <h4>Abgleich der identischen Daten via Query</h4>
        <div class="textcompare">
        	<strong>Login zur Datenbank via SSH und anschließend das Kommando "plesk db" ausführen</strong><br>
			<?= $dev_db_counts[$table] > 0 ? '<span style="color:red;"><b>Achtung! Es existieren Einträge in der Dev-DB. Vorher prüfen, ob die Tabelle wirklich geleer werden soll<br></b></span>' : '' ?>
        	1.Kommando<br>
        	<i>TRUNCATE TABLE <?= $table?>; </i><br>
        	2. Kommando<br>
        	<i>INSERT INTO ascasa_ng_db .<?= $table?> (<?= implode($live_dev_tbl, ', ') ?>)
    			SELECT <?= implode($live_dev_tbl, ', ') ?> FROM mod_ascasa_live.<?= $table?>;
        	</i>
    	</div>

    <hr>  
    <?php  
}
?>
</body>


