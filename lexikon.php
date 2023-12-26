<?php
/*
 *  xtc: lexikon
 *  created by Southbridge.de, Sergej Stroh
 *  $id lexikon.php v1.0 2005.07.18
 *
 * ----------------------------------------------- */

  require('includes/application_top.php');

  if((int)$_GET['keyword'] != ''){
  	$lexikon_query = xtc_db_query("SELECT
            id,
            keyword,
            description
          FROM " . TABLE_LEXIKON . "
          WHERE id = '". (int)$_GET['keyword'] ."'");
  	$lexikon = xtc_db_fetch_array($lexikon_query);
// IN VARIABLEN PACKEN
    $lex_keyword = $lexikon['keyword'];
    $lex_description = $lexikon['description'];
  } else {
      $lex_keyword = '';
      $lex_description = '';
  }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<head>
<title><?php echo $lex_keyword;?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="<?php echo 'templates/'.CURRENT_TEMPLATE.'/stylesheet.css'; ?>">
</head>
<body>

<br />
<table width="95%" align="center" border="0" cellspacing="5" cellpadding="0" style="border:1px solid #cccccc;">
  <tr>
    <td class="main"><strong><?php echo $lex_keyword;?></strong></td>
  </tr>
  <tr>
    <td class="main"><?php echo $lex_description;?></td>
  </tr>
</table>

<table width="95%" align="center" border="0" cellspacing="5" cellpadding="0">
  <tr>
    <td class="main"><a href="#" onClick='window.close();'><u><?php echo TEXT_CLOSE_WINDOW;?></u></a></td>
  </tr>
</table>

</body>
</html>