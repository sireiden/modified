<?php
/* -------------------------------------------------------------------------------------
* 	ID:						$Id: inc.commerz_finanz.php 2 2011-06-29 12:08:34Z siekiera $
* 	Letzter Stand:			$Revision: 6 $
* 	zuletzt geÃ¤ndert von:	$Author: siekiera $
* 	Datum:					$Date: 2011-06-06 14:08:34 +0200 (Mon, 06 Jun 2011) $
*
* 	SEO:mercari by Siekiera Media
* 	http://www.seo-mercari.de
*
* 	Copyright (c) since 2011 SEO:mercari
* --------------------------------------------------------------------------------------
* 	based on:
* 	(c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
* 	(c) 2002-2003 osCommerce - www.oscommerce.com
* 	(c) 2003     nextcommerce - www.nextcommerce.org
* 	(c) 2005     xt:Commerce - www.xt-commerce.com
*
* 	Released under the GNU General Public License
* ----------------------------------------------------------------------------------- */

function commerz_finanz($pID = '', $tax_class = '', $show_table = false, $total = '') {
	global $xtPrice;
	
	$c = '';
	
	if(empty($total))
		$price = $xtPrice->xtcGetPrice($pID, false, 1, $tax_class);
	else
		$price = $total;
	
	$products_price = $price;
	$total_price = $price;
	
	$dropdown = '';
	$ratelist = '';
	


	
	/*
	if(COMMERZFINANZ_ZINS_EFF_72 > 0)
		$rate72 = round($price*(pow(1+(COMMERZFINANZ_ZINS_EFF_72/100), 1/12)-1) / (1-pow(1+(COMMERZFINANZ_ZINS_EFF_72/100),-72/12)),2);
	else
		$rate72 = round($price / 72, 2);
		
	if(COMMERZFINANZ_ZINS_EFF_66 > 0)
		$rate66 = round($price*(pow(1+(COMMERZFINANZ_ZINS_EFF_66/100),1/12)-1) / (1-pow(1+(COMMERZFINANZ_ZINS_EFF_66/100),-66/12)), 2);
	else
		$rate66 = round($price / 66, 2);
	
	if(COMMERZFINANZ_ZINS_EFF_60 > 0)	
		$rate60 = round($price*(pow(1+(COMMERZFINANZ_ZINS_EFF_60/100),1/12)-1) / (1-pow(1+(COMMERZFINANZ_ZINS_EFF_60/100),-60/12)), 2);
	else
		$rate60 = round($price / 60, 2);
	
	if(COMMERZFINANZ_ZINS_EFF_48 > 0)	
		$rate48 = round($price*(pow(1+(COMMERZFINANZ_ZINS_EFF_48/100),1/12)-1) / (1-pow(1+(COMMERZFINANZ_ZINS_EFF_48/100),-48/12)), 2);
	else
		$rate48 = round($price / 48, 2);
	
	if(COMMERZFINANZ_ZINS_EFF_36 > 0)	
		$rate36 = round($price*(pow(1+(COMMERZFINANZ_ZINS_EFF_36/100),1/12)-1) / (1-pow(1+(COMMERZFINANZ_ZINS_EFF_36/100),-36/12)), 2);
	else
		$rate36 = round($price / 36, 2);
	*/
	if(COMMERZFINANZ_ZINS_EFF_30 > 0)	
		$rate30 = round($price*(pow(1+(COMMERZFINANZ_ZINS_EFF_30/100),1/12)-1) / (1-pow(1+(COMMERZFINANZ_ZINS_EFF_30/100),-30/12)), 2);
	else
		$rate30 = round($price / 30, 2);
	
	if(COMMERZFINANZ_ZINS_EFF_24 > 0)	
		$rate24 = round($price*(pow(1+(COMMERZFINANZ_ZINS_EFF_24/100),1/12)-1) / (1-pow(1+(COMMERZFINANZ_ZINS_EFF_24/100),-24/12)), 2);
	else
		$rate24 = round($price / 24, 2);
	
	if(COMMERZFINANZ_ZINS_EFF_18 > 0)	
		$rate18 = round($price*(pow(1+(COMMERZFINANZ_ZINS_EFF_18/100),1/12)-1) / (1-pow(1+(COMMERZFINANZ_ZINS_EFF_18/100),-18/12)), 2);
	else
		$rate18 = round($price / 18, 2);
	
	if(COMMERZFINANZ_ZINS_EFF_12 > 0)	
		$rate12 = round($price*(pow(1+(COMMERZFINANZ_ZINS_EFF_12/100),1/12)-1) / (1-pow(1+(COMMERZFINANZ_ZINS_EFF_12/100),-12/12)), 2);
	else
		$rate12 = round($price / 12, 2);
	
	if(COMMERZFINANZ_ZINS_EFF_6 > 0)	
		$rate6 = round($price*(pow(1+(COMMERZFINANZ_ZINS_EFF_6/100),1/12)-1) / (1-pow(1+(COMMERZFINANZ_ZINS_EFF_6/100),-6/12)), 2);
	else
		$rate6 = round($price / 6, 2);

	if($price < COMMERZFINANZ_MINIMUM_PRICE_TITLE || $price > COMMERZFINANZ_MAXIMUM_PRICE_TITLE) {
		$price = 0;
		$products_rate = 0;
		
	} elseif ($rate72 >= 1) {
		$price = $rate72;
		$products_rate = 72;
		$sollzinssatz = COMMERZFINANZ_SOLLZINS_72;
		$cb .='	<tr>';
		$cb .='		<td align="center">72</td>';
		$cb .='		<td align="center">72 x '.$xtPrice->xtcFormat($rate72, true).'</td>';
		$cb .='		<td align="center">'.($sollzinssatz > 0 ? COMMERZFINANZ_ZINS_EFF_72 : '0.00').'%</td>';
		$cb .='		<td align="center">'.$sollzinssatz.'%</td>';
		$cb .='		<td align="center">'.$xtPrice->xtcFormat(72*$rate72, true).'</td>';
		$cb .='	</tr>';
	
	} elseif ($rate66 >= 1) {
		$price = $rate66;
		$products_rate = 66;
		$sollzinssatz = COMMERZFINANZ_SOLLZINS_66;
		$cb .='	<tr>';
		$cb .='		<td align="center">66</td>';	
		$cb .='		<td align="center">66 x '.$xtPrice->xtcFormat($rate66, true).'</td>';
		$cb .='		<td align="center">'.($sollzinssatz > 0 ? COMMERZFINANZ_ZINS_EFF_66 : '0.00').'%</td>';
		$cb .='		<td align="center">'.$sollzinssatz.'%</td>';
		$cb .='		<td align="center">'.$xtPrice->xtcFormat(66*$rate66, true).'</td>';
		$cb .='	</tr>';
			
	} elseif ($rate60 >= 1) {
		$price = $rate60;
		$products_rate = 60;
		$sollzinssatz = COMMERZFINANZ_SOLLZINS_60;
		$cb .='	<tr>';
		$cb .='		<td align="center">60</td>';	
		$cb .='		<td align="center">60 x '.$xtPrice->xtcFormat($rate60, true).'</td>';
		$cb .='		<td align="center">'.($sollzinssatz > 0 ? COMMERZFINANZ_ZINS_EFF_60 : '0.00').'%</td>';
		$cb .='		<td align="center">'.$sollzinssatz.'%</td>';
		$cb .='		<td align="center">'.$xtPrice->xtcFormat(60*$rate60, true).'</td>';
		$cb .='	</tr>';
				
	} elseif ($rate48 >= 1) {
		$price = $rate48;
		$products_rate = 48;
		$sollzinssatz = COMMERZFINANZ_SOLLZINS_48;
		$cb .='	<tr>';
		$cb .='		<td align="center">48</td>';
		$cb .='		<td align="center">48 x '.$xtPrice->xtcFormat($rate48, true).'</td>';
		$cb .='		<td align="center">'.($sollzinssatz > 0 ? COMMERZFINANZ_ZINS_EFF_48 : '0.00').'%</td>';
		$cb .='		<td align="center">'.$sollzinssatz.'%</td>';
		$cb .='		<td align="center">'.$xtPrice->xtcFormat(48*$rate48, true).'</td>';
		$cb .='	</tr>';
					
	} elseif ($rate36 >= 1) {
		$price = $rate36;
		$products_rate = 36; 
		$sollzinssatz = COMMERZFINANZ_SOLLZINS_36;
		$cb .='	<tr>';
		$cb .='		<td align="center">36</td>';
		$cb .='		<td align="center">36 x '.$xtPrice->xtcFormat($rate36, true).'</td>';
		$cb .='		<td align="center">'.($sollzinssatz > 0 ? COMMERZFINANZ_ZINS_EFF_36 : '0.00').'%</td>';
		$cb .='		<td align="center">'.$sollzinssatz.'%</td>';
		$cb .='		<td align="center">'.$xtPrice->xtcFormat(36*$rate36, true).'</td>';
		$cb .='	</tr>';
						
	} elseif ($rate30 >= 1) {
		$price = $rate30;
		$price_lowest = $price;
		$products_rate = 30;
		$sollzinssatz = COMMERZFINANZ_SOLLZINS_30;
		$cb .='	<tr>';
		$cb .='		<td align="center">30</td>';
		$cb .='		<td align="center">30 x '.$xtPrice->xtcFormat($rate30, true).'</td>';
		$cb .='		<td align="center">'.($sollzinssatz > 0 ? COMMERZFINANZ_ZINS_EFF_30 : '0.00').'%</td>';
		$cb .='		<td align="center">'.$sollzinssatz.'%</td>';
		$cb .='		<td align="center">'.$xtPrice->xtcFormat($price, true).'</td>';
		$cb .='	</tr>';
							
	} elseif ($rate24 >= 1) {
		$price = $rate24;
		$price_lowest = $price;
		$products_rate = 24;
		$sollzinssatz = COMMERZFINANZ_SOLLZINS_24;
		$cb .='	<tr>';
		$cb .='		<td align="center">24</td>';
		$cb .='		<td align="center">24 x '.$xtPrice->xtcFormat($rate24, true).'</td>';
		$cb .='		<td align="center">'.($sollzinssatz > 0 ? COMMERZFINANZ_ZINS_EFF_24 : '0.00').'%</td>';
		$cb .='		<td align="center">'.$sollzinssatz.'%</td>';
		$cb .='		<td align="center">'.$xtPrice->xtcFormat($price, true).'</td>';
		$cb .='	</tr>';
								
	} elseif ($rate18 >= 15) {
		$price = $rate18;
		$price_lowest = $price;
		$products_rate = 18;
		$sollzinssatz = COMMERZFINANZ_SOLLZINS_18;
		$cb .='	<tr>';
		$cb .='		<td align="center">18</td>';
		$cb .='		<td align="center">18 x '.$xtPrice->xtcFormat($rate18, true).'</td>';
		$cb .='		<td align="center">'.($sollzinssatz > 0 ? COMMERZFINANZ_ZINS_EFF_18 : '0.00').'%</td>';
		$cb .='		<td align="center">'.$sollzinssatz.'%</td>';
		$cb .='		<td align="center">'.$xtPrice->xtcFormat($price, true).'</td>';
		$cb .='	</tr>';
									
	} elseif ($rate12 >= 1) {
		$price = $rate12;
		$price_lowest = $price;
		$products_rate = 12;
		$sollzinssatz = COMMERZFINANZ_SOLLZINS_12;
		$cb .='	<tr>';
		$cb .='		<td align="center">12</td>';
		$cb .='		<td align="center">12 x '.$xtPrice->xtcFormat($rate12, true).'</td>';
		$cb .='		<td align="center">'.($sollzinssatz > 0 ? COMMERZFINANZ_ZINS_EFF_12 : '0.00').'%</td>';
		$cb .='		<td align="center">'.$sollzinssatz.'%</td>';
		$cb .='		<td align="center">'.$xtPrice->xtcFormat($price, true).'</td>';
		$cb .='	</tr>';
										
	} elseif ($rate6 >= 1) {
		$price = $rate6;
		$price_lowest = $price;
		$products_rate = 6;
		$sollzinssatz = COMMERZFINANZ_SOLLZINS_6;
		$cb .='<tr>';
		$cb .='<td align="center">6</td>';
		$cb .='<td align="center">6 x '.$xtPrice->xtcFormat($rate6, true).'</td>';
		$cb .='<td align="center">'.($sollzinssatz > 0 ? COMMERZFINANZ_ZINS_EFF_6 : '0.00').'%</td>';
		$cb .='<td align="center">'.$sollzinssatz.'%</td>';
		$cb .='<td align="center">'.$xtPrice->xtcFormat($price, true).'</td>';
		$cb .='</tr>';
	}

	if($price > 0) {
		$total = $xtPrice->xtcFormat((str_replace(',','.', $price)*$products_rate), true);
	

		/*
		*	Tabelle zusammensetzen
		*/
		$cb = '';
		
		if ($rate6 >= 10) {
			$price = $rate6;
			$price_lowest = $price;
			#print_r($rate6);
			$products_rate = 6;
			$sollzinssatz = COMMERZFINANZ_SOLLZINS_6;
			$dropdown .= '<option value="6">6 Monate</option>'.PHP_EOL;
			$ratelist .= '<span style="display:none" class="ratelist" id="rate_6">'.$xtPrice->xtcFormat($rate6, true).'</span>';
			$sollzinsrate .= '<span style="display:none" class="sollzins" id="sollzins_6">'.str_replace('.', ',', COMMERZFINANZ_SOLLZINS_6).'</span>';
			$effektivzinsrate .= '<span style="display:none" class="effektivzins" id="effektivzins_6">'.str_replace('.', ',', COMMERZFINANZ_ZINS_EFF_6).'</span>';
			$totalprice .= '<span style="display:none" class="gesamtpreis" id="gesamtpreis_6">'.$xtPrice->xtcFormat($total_price, true).'</span>';
						
			$cb .='<tr>';
			$cb .='<td align="center">6</td>';
			$cb .='<td align="center">6 x '.$xtPrice->xtcFormat($rate6, true).'</td>';
			$cb .='<td align="center">'.($sollzinssatz > 0 ? COMMERZFINANZ_ZINS_EFF_6 : '0.00').'%</td>';
			$cb .='<td align="center">'.$sollzinssatz.'%</td>';
			$cb .='<td align="center">'.$xtPrice->xtcFormat($total_price, true).'</td>';
			$cb .='</tr>';
		}
		if ($rate12 >= 10) {
			$price = $rate12;
			$price_lowest = $price;
			$products_rate = 12;
			$sollzinssatz = COMMERZFINANZ_SOLLZINS_12;
			$dropdown .= '<option value="12">12 Monate</option>'.PHP_EOL;
			$ratelist .= '<span style="display:none" class="ratelist" id="rate_12">'.$xtPrice->xtcFormat($rate12, true).'</span>';
			$sollzinsrate .= '<span style="display:none" class="sollzins" id="sollzins_12">'.str_replace('.', ',', COMMERZFINANZ_SOLLZINS_12).'</span>';
			$effektivzinsrate .= '<span style="display:none" class="effektivzins" id="effektivzins_12">'.str_replace('.', ',', COMMERZFINANZ_ZINS_EFF_12).'</span>';
			$totalprice .= '<span style="display:none" class="gesamtpreis" id="gesamtpreis_12">'.$xtPrice->xtcFormat($total_price, true).'</span>';		
			$cb .='	<tr>';
			$cb .='		<td align="center">12</td>';
			$cb .='		<td align="center">12 x '.$xtPrice->xtcFormat($rate12, true).'</td>';
			$cb .='		<td align="center">'.($sollzinssatz > 0 ? COMMERZFINANZ_ZINS_EFF_12 : '0.00').'%</td>';
			$cb .='		<td align="center">'.$sollzinssatz.'%</td>';
			$cb .='		<td align="center">'.$xtPrice->xtcFormat($total_price, true).'</td>';
			$cb .='	</tr>';								
		}
		if ($rate18 >= 10) {
			$price = $rate18;
			$price_lowest = $price;
			$products_rate = 18;
			$sollzinssatz = COMMERZFINANZ_SOLLZINS_18;
			$dropdown .= '<option value="18">18 Monate</option>'.PHP_EOL;
			$ratelist .= '<span style="display:none" class="ratelist" id="rate_18">'.$xtPrice->xtcFormat($rate18, true).'</span>';
			$sollzinsrate .= '<span style="display:none" class="sollzins" id="sollzins_18">'.str_replace('.', ',', COMMERZFINANZ_SOLLZINS_18).'</span>';
			$effektivzinsrate .= '<span style="display:none" class="effektivzins" id="effektivzins_18">'.str_replace('.', ',', COMMERZFINANZ_ZINS_EFF_18).'</span>';
			$totalprice .= '<span style="display:none" class="gesamtpreis" id="gesamtpreis_18">'.$xtPrice->xtcFormat($total_price, true).'</span>';			
			$cb .='	<tr>';
			$cb .='		<td align="center">18</td>';
			$cb .='		<td align="center">18 x '.$xtPrice->xtcFormat($rate18, true).'</td>';
			$cb .='		<td align="center">'.($sollzinssatz > 0 ? COMMERZFINANZ_ZINS_EFF_18 : '0.00').'%</td>';
			$cb .='		<td align="center">'.$sollzinssatz.'%</td>';
			$cb .='		<td align="center">'.$xtPrice->xtcFormat($total_price, true).'</td>';
			$cb .='	</tr>';						
		}
		if ($rate24 >= 10) {
			$price = $rate24;
			$price_lowest = $price;
			$products_rate = 24;
			$sollzinssatz = COMMERZFINANZ_SOLLZINS_24;
			$dropdown .= '<option value="24">24 Monate</option>'.PHP_EOL;
			$ratelist .= '<span style="display:none" class="ratelist" id="rate_24">'.$xtPrice->xtcFormat($rate24, true).'</span>';
			$sollzinsrate .= '<span style="display:none" class="sollzins" id="sollzins_24">'.str_replace('.', ',', COMMERZFINANZ_SOLLZINS_24).'</span>';
			$effektivzinsrate .= '<span style="display:none" class="effektivzins" id="effektivzins_24">'.str_replace('.', ',', COMMERZFINANZ_ZINS_EFF_24).'</span>';
			$totalprice .= '<span style="display:none" class="gesamtpreis" id="gesamtpreis_24">'.$xtPrice->xtcFormat($total_price, true).'</span>';			
			$cb .='	<tr>';
			$cb .='		<td align="center">24</td>';
			$cb .='		<td align="center">24 x '.$xtPrice->xtcFormat($rate24, true).'</td>';
			$cb .='		<td align="center">'.($sollzinssatz > 0 ? COMMERZFINANZ_ZINS_EFF_24 : '0.00').'%</td>';
			$cb .='		<td align="center">'.$sollzinssatz.'%</td>';
			$cb .='		<td align="center">'.$xtPrice->xtcFormat($total_price, true).'</td>';
			$cb .='	</tr>';						
		}
		if ($rate30 >= 10) {
			$price = $rate30;
			$price_lowest = $price;
			$products_rate = 30;
			$sollzinssatz = COMMERZFINANZ_SOLLZINS_30;
			$dropdown .= '<option value="30">30 Monate</option>'.PHP_EOL;
			$ratelist .= '<span style="display:none" class="ratelist" id="rate_30">'.$xtPrice->xtcFormat($rate30, true).'</span>';
			$sollzinsrate .= '<span style="display:none" class="sollzins" id="sollzins_30">'.str_replace('.', ',', COMMERZFINANZ_SOLLZINS_30).'</span>';
			$effektivzinsrate .= '<span style="display:none" class="effektivzins" id="effektivzins_30">'.str_replace('.', ',', COMMERZFINANZ_ZINS_EFF_30).'</span>';
			$totalprice .= '<span style="display:none" class="gesamtpreis" id="gesamtpreis_30">'.$xtPrice->xtcFormat($total_price, true).'</span>';			
			$cb .='	<tr>';
			$cb .='		<td align="center">30</td>';
			$cb .='		<td align="center">30 x '.$xtPrice->xtcFormat($rate30, true).'</td>';
			$cb .='		<td align="center">'.($sollzinssatz > 0 ? COMMERZFINANZ_ZINS_EFF_30 : '0.00').'%</td>';
			$cb .='		<td align="center">'.$sollzinssatz.'%</td>';
			$cb .='		<td align="center">'.$xtPrice->xtcFormat($total_price, true).'</td>';
			$cb .='	</tr>';					
		}
		
		if ($rate36 >= 10) {
			$price = $rate36;
			$products_rate = 36;
			$sollzinssatz = COMMERZFINANZ_SOLLZINS_36;
			$dropdown .= '<option value="36">36 Monate</option>'.PHP_EOL;
			$ratelist .= '<span style="display:none" class="ratelist" id="rate_36">'.$xtPrice->xtcFormat($rate36, true).'</span>';
			$sollzinsrate .= '<span style="display:none" class="sollzins" id="sollzins_36">'.str_replace('.', ',', COMMERZFINANZ_SOLLZINS_36).'</span>';
			$effektivzinsrate .= '<span style="display:none" class="effektivzins" id="effektivzins_36">'.str_replace('.', ',', COMMERZFINANZ_ZINS_EFF_36).'</span>';
			$totalprice .= '<span style="display:none" class="gesamtpreis" id="gesamtpreis_36">'.$xtPrice->xtcFormat(36*$rate36, true).'</span>';			
			$cb .='	<tr>';
			$cb .='		<td align="center">36</td>';
			$cb .='		<td align="center">36 x '.$xtPrice->xtcFormat($rate36, true).'</td>';
			$cb .='		<td align="center">'.($sollzinssatz > 0 ? COMMERZFINANZ_ZINS_EFF_36 : '0.00').'%</td>';
			$cb .='		<td align="center">'.$sollzinssatz.'%</td>';
			$cb .='		<td align="center">'.$xtPrice->xtcFormat(36*$rate36, true).'</td>';
			$cb .='	</tr>';					
		}
		if ($rate48 >= 10) {
			$price = $rate48;
			$products_rate = 48;
			$sollzinssatz = COMMERZFINANZ_SOLLZINS_48;
			$dropdown .= '<option value="48">48 Monate</option>'.PHP_EOL;
			$ratelist .= '<span style="display:none" class="ratelist" id="rate_48">'.$xtPrice->xtcFormat($rate48, true).'</span>';
			$sollzinsrate .= '<span style="display:none" class="sollzins" id="sollzins_48">'.str_replace('.', ',', COMMERZFINANZ_SOLLZINS_48).'</span>';
			$effektivzinsrate .= '<span style="display:none" class="effektivzins" id="effektivzins_48">'.str_replace('.', ',', COMMERZFINANZ_ZINS_EFF_48).'</span>';
			$totalprice .= '<span style="display:none" class="gesamtpreis" id="gesamtpreis_48">'.$xtPrice->xtcFormat(48*$rate48, true).'</span>';			
			$cb .='	<tr>';
			$cb .='		<td align="center">48</td>';
			$cb .='		<td align="center">48 x '.$xtPrice->xtcFormat($rate48, true).'</td>';
			$cb .='		<td align="center">'.($sollzinssatz > 0 ? COMMERZFINANZ_ZINS_EFF_48 : '0.00').'%</td>';
			$cb .='		<td align="center">'.$sollzinssatz.'%</td>';
			$cb .='		<td align="center">'.$xtPrice->xtcFormat(48*$rate48, true).'</td>';
			$cb .='	</tr>';				
		}
		if ($rate60 >= 10) {
			$price = $rate60;
			$products_rate = 60;
			$sollzinssatz = COMMERZFINANZ_SOLLZINS_60;
			$dropdown .= '<option value="60">60 Monate</option>'.PHP_EOL;
			$ratelist .= '<span style="display:none" class="ratelist" id="rate_60">'.$xtPrice->xtcFormat($rate60, true).'</span>';
			$sollzinsrate .= '<span style="display:none" class="sollzins" id="sollzins_60">'.str_replace('.', ',', COMMERZFINANZ_SOLLZINS_60).'</span>';
			$effektivzinsrate .= '<span style="display:none" class="effektivzins" id="effektivzins_60">'.str_replace('.', ',', COMMERZFINANZ_ZINS_EFF_60).'</span>';
			$totalprice .= '<span style="display:none" class="gesamtpreis" id="gesamtpreis_60">'.$xtPrice->xtcFormat(60*$rate60, true).'</span>';			
			$cb .='	<tr>';
			$cb .='		<td align="center">60</td>';	
			$cb .='		<td align="center">60 x '.$xtPrice->xtcFormat($rate60, true).'</td>';
			$cb .='		<td align="center">'.($sollzinssatz > 0 ? COMMERZFINANZ_ZINS_EFF_60 : '0.00').'%</td>';
			$cb .='		<td align="center">'.$sollzinssatz.'%</td>';
			$cb .='		<td align="center">'.$xtPrice->xtcFormat(60*$rate60, true).'</td>';
			$cb .='	</tr>';		
		}
/*		if ($rate66 >= 1) {
			$price = $rate66;
			$products_rate = 66;
			$sollzinssatz = COMMERZFINANZ_SOLLZINS_66;
			$dropdown .= '<option value="66">66 Monate</option>'.PHP_EOL;
			$ratelist .= '<span style="display:none" class="ratelist" id="rate_66">'.$xtPrice->xtcFormat($rate66, true).'</span>';
			$sollzinsrate .= '<span style="display:none" class="sollzins" id="sollzins_66">'.str_replace('.', ',', COMMERZFINANZ_SOLLZINS_66).'</span>';
			$effektivzinsrate .= '<span style="display:none" class="effektivzins" id="effektivzins_66">'.str_replace('.', ',', COMMERZFINANZ_ZINS_EFF_66).'</span>';
			$totalprice .= '<span style="display:none" class="gesamtpreis" id="gesamtpreis_66">'.$xtPrice->xtcFormat(66*$rate66, true).'</span>';			
			$cb .='	<tr>';
			$cb .='		<td align="center">66</td>';	
			$cb .='		<td align="center">66 x '.$xtPrice->xtcFormat($rate66, true).'</td>';
			$cb .='		<td align="center">'.($sollzinssatz > 0 ? COMMERZFINANZ_ZINS_EFF_66 : '0.00').'%</td>';
			$cb .='		<td align="center">'.$sollzinssatz.'%</td>';
			$cb .='		<td align="center">'.$xtPrice->xtcFormat(66*$rate66, true).'</td>';
			$cb .='	</tr>';		
		} */
		if ($rate72 >= 10) {
			$price = $rate72;
			$products_rate = 72;
			$sollzinssatz = COMMERZFINANZ_SOLLZINS_72;
			$dropdown .= '<option value="72">72 Monate</option>'.PHP_EOL;
			$ratelist .= '<span style="display:none" class="ratelist" id="rate_72">'.$xtPrice->xtcFormat($rate72, true).'</span>';
			$sollzinsrate .= '<span style="display:none" class="sollzins" id="sollzins_72">'.str_replace('.', ',', COMMERZFINANZ_SOLLZINS_72).'</span>';
			$effektivzinsrate .= '<span style="display:none" class="effektivzins" id="effektivzins_72">'.str_replace('.', ',', COMMERZFINANZ_ZINS_EFF_72).'</span>';
			$totalprice .= '<span style="display:none" class="gesamtpreis" id="gesamtpreis_72">'.$xtPrice->xtcFormat(72*$rate72, true).'</span>';			
			$cb .='	<tr>';
			$cb .='		<td align="center">72</td>';
			$cb .='		<td align="center">72 x '.$xtPrice->xtcFormat($rate72, true).'</td>';
			$cb .='		<td align="center">'.($sollzinssatz > 0 ? COMMERZFINANZ_ZINS_EFF_72 : '0.00').'%</td>';
			$cb .='		<td align="center">'.$sollzinssatz.'%</td>';
			$cb .='		<td align="center">'.$xtPrice->xtcFormat(72*$rate72, true).'</td>';
			$cb .='	</tr>';	
		} 
	
		if($show_table) {
			$c ='<div align="left" class="commerz_info_periods">';
			//$c .='<div class="fs85">'.sprintf(COMMERZFINANZ_DESC_1, COMMERZFINANZ_ZINS_EFF).'</div>';
			$c .='<div>'.COMMERZFINANZ_HEAD.'</div>';
			$c .='<table border="0" cellpadding="3" cellpadding="1" class="commerz_finanz_table">';
			$c .='<tr>';
			$c .='<th>Monate</th>';
			$c .='<th>'.COMMERZFINANZ_LAUFZEIT_MONATRATE.'</th>';
			$c .='<th>'.COMMERZFINANZ_JAHRESZINS.'*</th>';
			$c .='<th>'.COMMERZFINANZ_SOLLZINS.'</th>';
			$c .='<th>'.COMMERZFINANZ_TOTAL.'</th>';
			$c .='</tr>';
			$c .= $cb;
			$c .='</table>';
			//$c .='<div class="fs85">'.COMMERZFINANZ_DESC_2.'</div>';
			$c .='</div>';
		}

		$total = $xtPrice->xtcFormat((str_replace(',','.', $price)*$products_rate), true);
		if($products_rate > 0) {
		  $p = sprintf(COMMERZFINANZ_DESC, $xtPrice->xtcFormat($price, true), $products_rate, $total, $sollzinssatz, constant("COMMERZFINANZ_ZINS_EFF_$products_rate"));
		}
		
		$link = xtc_href_link('popup_finanzierung.php', 'price='.$products_price.'&KeepThis=true&TB_iframe=true&height=720&width=600.', $request_type);
		
		if($products_rate > 0) {
		  $p2 =  sprintf(COMMERZFINANZ_DESC_SHORT, $xtPrice->xtcFormat($price_lowest, true), $link);
		  $p2 .= sprintf(COMMERZFINANZ_DESC_LONG, $products_rate, $total, $sollzinssatz, constant("COMMERZFINANZ_ZINS_EFF_$products_rate"));
		}
		else {
		  $p2 = '';
		}
		
		return array('price' => $p2, 'table' =>  $c, 'dropdown' => $dropdown, 'ratelist' => $ratelist, 'sollzinsrate' => $sollzinsrate, 'effektivzinsrate' => $effektivzinsrate, 'totalprice' => $totalprice);
	}
}


function build_commerz_url() {
	global $insert_id,$xtPrice,$order,$order_totals;
	// Session säubern
	unset($_SESSION['financing_started']);
	
	error_log(print_r($order_totals, true));

	if (!class_exists('order_total')) {
		require(DIR_WS_CLASSES.'order_total.php');
		$order_total_modules = new order_total();
		$order_totals = $order_total_modules->process();
	}
	
	for($i = 0, $n = sizeof($order_totals); $i < $n; $i ++) {
		switch($order_totals[$i]['code']) {
			case 'ot_total':
				$paymentAmount=$order_totals[$i]['value'];
				break;
		}
	}
	// AMT
	//$paymentAmount=$_SESSION['hpLastData']['commerz_amount'];
	$paymentAmount = round($paymentAmount, $xtPrice->get_decimal_places($order->info['currency']));
	$paymentAmount = number_format($paymentAmount, $xtPrice->get_decimal_places($order->info['currency']), ',', '');
	$currencyCodeType = urlencode($order->info['currency']);
	// Payment Type
	// The returnURL is the location where buyers return when a
	// payment has been succesfully authorized.
	// The cancelURL is the location buyers are sent to when they hit the
	// cancel button during authorization of payment during the PayPal flow
	$successURL =HTTPS_SERVER.DIR_WS_CATALOG.FILENAME_CHECKOUT_SUCCESS.'?'.xtc_session_name().'='.xtc_session_id();
	$cancelURL =HTTPS_SERVER.DIR_WS_CATALOG.FILENAME_CHECKOUT_PAYMENT.'?'.xtc_session_name().'='.xtc_session_id().'&error=true&error_message='.com_mn_iconv($_SESSION['language_charset'], "UTF-8", 'Bitte wählen Sie eine Zahlungsart aus!');
	$failureURL =HTTPS_SERVER.DIR_WS_CATALOG.FILENAME_CHECKOUT_PAYMENT.'?'.xtc_session_name().'='.xtc_session_id().'&commerz_declied=1&error=true&error_message='.com_mn_iconv($_SESSION['language_charset'], "UTF-8", 'Bitte wählen Sie eine alternative Zahlungsart aus!'); 
	$notifyURL = HTTPS_SERVER.DIR_WS_CATALOG.'callback/commerz/notify.php';

	if ($_SESSION['customer_gender'] == 'm')
		$salutation = 'HERR';
	else
		$salutation = 'FRAU';	
	
	$firstname = com_mn_iconv($_SESSION['language_charset'], "UTF-8", $order->customer['firstname']);
	$lastname = com_mn_iconv($_SESSION['language_charset'], "UTF-8", $order->customer['lastname']);	
	$street = com_mn_iconv($_SESSION['language_charset'], "UTF-8", $order->customer['street_address']);
	$city = com_mn_iconv($_SESSION['language_charset'], "UTF-8", $order->customer['city']);
	$zip = $order->customer['postcode'];
	$telephone = com_mn_iconv($_SESSION['language_charset'], "UTF-8", $order->customer['telephone']);
	$mobile = com_mn_iconv($_SESSION['language_charset'], "UTF-8", $order->customer['telephone_optional']);
		
	$order_id = $insert_id;
	
	$iframe_link = 'https://finanzieren.consorsfinanz.de/web/ecommerce/gewuenschte-rate';
	
	$string = '<form id="finanzierung_form" method="post" action="'.$iframe_link.'">';
	$string .= '<input type="hidden" name="vendorid" value="'.MODULE_PAYMENT_COMMERZFINANZ_NUMBER.'">';
	$string .= '<input type="hidden" name="order_amount" value="'.$paymentAmount.'">';
	$string .= '<input type="hidden" name="order_id" value="'.$order_id.'">';
	$string .= '<input type="hidden" name="salutation" value="'.$salutation.'">';
	$string .= '<input type="hidden" name="firstname" value="'.$firstname.'">';
	$string .= '<input type="hidden" name="lastname" value="'.$lastname.'">';
	$string .= '<input type="hidden" name="email" value="'.$zip.'">';
	$string .= '<input type="hidden" name="street" value="'.$street.'">';
	$string .= '<input type="hidden" name="zip" value="'.$zip.'">';
	$string .= '<input type="hidden" name="city" value="'.$city.'">';
	$string .= '<input type="hidden" name="phone" value="'.$telephone.'">';
	$string .= '<input type="hidden" name="mobile" value="'.$mobile.'">';
	$string .= '<input type="hidden" name="cancelURL" value="'.$cancelURL.'">';
	$string .= '<input type="hidden" name="successURL" value="'.$successURL.'L">';
	$string .= '<input type="hidden" name="failureURL" value="'.$failureURL.'">';
	$string .= '<input type="hidden" name="notifyURL" value="'.$notifyURL.'">';
	$string .= '<input type="hidden" name="shoplogoURL" value="https://www.ascasa.de/templates/ascasa/img/logo.gif">';
	$string .= '<input type="hidden" name="shopbrandname" value="ascasa GmbH">';
	
	/*
	// String zusammenbauen
	$string="&order_amount=".$paymentAmount.
	"&order_id=".$order_id.
	"&salutation=".$salutation.
	"&firstname=".$firstname.
	"&lastname=".$lastname.
	"&email=".$email.
	"&street=".$street.
	"&zip=".$zip.
	"&city=".$city.
	"&cancelURL=".$cancelURL.
	"&successURL=".$successURL.
	"&failureURL=".$failureURL.
	"&notifyURL=".$notifyURL;
	*/
	
	
//	$iframe_link = 'https://ecm-1.commerzfinanz.com/ecommerce/entry?vendorid=2166684';
//	$iframe_link .= $string;
	
	$_SESSION['financing_started'] = 1;
	$_SESSION['financing_iframe'] = $string;
	$_SESSION['financing_order_id'] = $order_id;
}


function save_notification($data) {
	// Bestellung suchen
	$order_query = xtc_db_query("SELECT orders_id, orders_status_id
                                    FROM " . TABLE_ORDERS_STATUS_HISTORY . "
                                    WHERE comments = '" . xtc_db_prepare_input($data['order_id']) . "'");
    $order_query_result = xtc_db_fetch_array($order_query);
    if(!empty($data['order_id'])) {
    	
    	if($data['status'] != 'success') {
    		$data['status'] = $data['status']."\n".'Grund: '.$data['status_detail'];
    	}

    	$comment = 'Gesamtbetrag: '.$data['creditAmount']."\n";
    	$comment .= 'Laufzeit: '.$data['duration']."\n";
    	$comment .= 'Vertragsnummer: '.$data['transaction_id']."\n";
    	$comment .= 'Status: '.$data['status']."\n";  

    	xtc_db_query("UPDATE " . TABLE_ORDERS . "
                        SET orders_status = '" . 21 . "', last_modified = now()
                        WHERE orders_id = '" . xtc_db_prepare_input($data['order_id']) . "'");    	
    	
    	$sql_data_array = array('orders_id' => xtc_db_prepare_input($data['order_id']),
    							'orders_status_id' => 21,
    							'date_added' => 'now()',
    							'customer_notified' => '0',
    							'comments' => 'Commerz Finanz Notification ' . "\n" . $comment . '');
    	xtc_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
    }
    else {
    	if($data['status'] == 'success') {
    		$comment  = 'Zur folgenden Commerz Finanz Benachrichtigung wurde keine passende Bestellung gefunden:'."\n";
    		$comment .= 'Gesamtbetrag: '.$data['creditAmount']."\n";
    		$comment .= 'Laufzeit: '.$data['duration']."\n";
    		$comment .= 'Order-ID: '.$data['order_id']."\n";
    		$comment .= 'Vertragsnummer: '.$data['transaction_id']."\n";
    		$comment .= 'Status: '.$data['status']."\n";    		
    		
	    	xtc_php_mail(EMAIL_BILLING_ADDRESS,	EMAIL_BILLING_NAME, 'm.foerster@brainsquad.de', 'Michael Förster', '',EMAIL_BILLING_REPLY_ADDRESS,EMAIL_BILLING_REPLY_ADDRESS_NAME,
	    	'',
	    	'',
	    	'Commerz Finanz - Bestellung nicht gefunden',
	    	nl2br($comment),
	    	$comment
	    	);    	
    	}
    }
}



function com_mn_iconv($t1,$t2,$string){
	// Stand: 29.04.2009
/*	if(function_exists('iconv')) {
		return iconv($t1, $t2, $string);
	} */
	/// Kein iconv im PHP
	if($t2 == "UTF-8") {
		// nur als Ersatz für das iconv und nur in eine richtung 1251 to UTF8
		//ISO 8859-1 to UTF-8
		if(function_exists('utf8_encode')) {
			return utf8_encode($string);
		} else {
			$string=preg_replace("/([\x80-\xFF])/e","chr(0xC0|ord('\\1')>>6).chr(0x80|ord('\\1')&0x3F)",$string);
			return($string);
		}
	} elseif($t1 == "UTF-8") {
		//UTF-8 to ISO 8859-1
		if(function_exists('utf8_decode')) {
			return utf8_decode($string);
		} else {
			$string=preg_replace("/([\xC2\xC3])([\x80-\xBF])/e","chr(ord('\\1')<<6&0xC0|ord('\\2')&0x3F)",$string);
			return($string);
		}
	} else {
		// keine Konvertierung möglich
		return($string);
	}
}

