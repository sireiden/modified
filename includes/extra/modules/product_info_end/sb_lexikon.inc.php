<?php
$info_smarty->assign('PRODUCTS_DESCRIPTION', sb_lexikon(stripslashes(remove_word_code($product->data['products_description']))));


/*
	Glossar
	@version 1.1
	Copyright (C) 2007 Sergej Stroh.
	
	www.southbridge.de
	info@southbridge.de
	
	@date 2007.10.05
	@file sb_lexikon.inc.php
	
*/

		/*
				UNDEFINED CONSTANT?
		*/
		if(!defined('MODULE_LEXIKON_TYPE')) define('MODULE_LEXIKON_TYPE','popup');
		
		/*
			@text				Text
			@workas			Link, Link with Image
			@type				Acronym, PopUp
		*/
		function sb_lexikon($text,$type=MODULE_LEXIKON_TYPE)
		{
				// MODUL ON
				if(defined('MODULE_LEXIKON_STATUS') && MODULE_LEXIKON_STATUS == 'true')
				{
						$keywords = array();
						$get_keywords_query = xtc_db_query("SELECT id, keyword, description FROM ". TABLE_LEXIKON);
						
						switch ($type)
						{
								case 'acronym':
										while($lexikon = xtc_db_fetch_array($get_keywords_query))
										{
												array_push($keywords, array($mywords = $lexikon['keyword']));
												$explode_me = explode("||", $mywords);
												$num = 0;
								
												while(list($ident, $value) = each($explode_me)) 
												{
														$value = " " . $value . "";
														$treffer = '/' . $value . '/';
															
														$keyword_new = ' <acronym style="cursor:help;" title="'.strip_tags($lexikon['description']).'">'.$lexikon['keyword'].'</acronym>';
														$text = @preg_replace($treffer, $keyword_new, $text, 1);

														$num++;
												}
										}
										break;
								case 'popup':
										while($lexikon = xtc_db_fetch_array($get_keywords_query))
										{
												array_push($keywords, array($mywords = $lexikon['keyword']));
												$explode_me = explode("||", $mywords);
				
												while(list($ident, $value) = each($explode_me))
												{
														$value = "" . $value . "";
														$treffer = '/' . $value . '/';
														
														// LINK WITH IMAGE
//$keyword_new = ' <span class="lexikon_keyword">'.$lexikon['keyword'].'</span><img style="cursor:help;" onclick="javascript:window.open(\''.xtc_href_link(FILENAME_LEXIKON,'keyword='. $lexikon['id']).'\', \'Lexikon\', \'scrollbars=yes, toolbar=0, width=400, height=200\')" src="templates/'.CURRENT_TEMPLATE.'/img/lexikon_icon.gif" alt="' .$lexikon['keyword'] .'" border="0" /></a>';

														// LINK ONLY WITHOUT IMAGE
														//$keyword_new= ' <a style="cursor:help;" onClick="javascript:window.open(\''.xtc_href_link(FILENAME_LEXIKON,'keyword='. $lexikon['id']).'\', \'Lexikon\', \'scrollbars=yes, toolbar=0, width=400, height=200\')" title="' .$lexikon['keyword'] .'">'.$lexikon['keyword'].'</a>';
														
														//BY GERO
														$keyword_new= ' <span class="lex">'.$lexikon['keyword'].'<span class="lex_details">'.remove_word_code($lexikon['description']).'</span></span>';
//$keyword_new= ' <span class="lex"  onClick="javascript:window.open(\''.xtc_href_link(FILENAME_LEXIKON,'keyword='. $lexikon['id']).'\', \'Lexikon\', \'scrollbars=yes, toolbar=0, width=600, height=450\').focus()" title="' .$lexikon['keyword'] .'">'.$lexikon['keyword'].'</span>';

//$keyword_new= ' <acronym style="cursor:help; color:#151515; background-color:#FF6" onClick="javascript:window.open(\''.xtc_href_link(FILENAME_LEXIKON,'keyword='. $lexikon['id']).'\', \'Lexikon\', \'scrollbars=yes, toolbar=0, width=600, height=450\').focus()" title="' .$lexikon['keyword'] .'">'.$lexikon['keyword'].'</acronym>';
	
//$keyword_new= ' <font color="#DF7401"; style="cursor:alias;" onClick="javascript:window.open(\''.xtc_href_link(FILENAME_LEXIKON,'keyword='. $lexikon['id']).'\', \'Lexikon\', \'scrollbars=yes, toolbar=0, width=600, height=450\').focus()" title="' .$lexikon['keyword'] .'">'.$lexikon['keyword'].'</font>';
														//BY GERO
														$text = preg_replace($treffer, $keyword_new, $text, 1, $count);
												}
										}
										break;
						}
				}
		
				return $text;
	}
?>