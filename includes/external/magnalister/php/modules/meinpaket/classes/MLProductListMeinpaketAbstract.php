<?php
/**
 * 888888ba                 dP  .88888.                    dP                
 * 88    `8b                88 d8'   `88                   88                
 * 88aaaa8P' .d8888b. .d888b88 88        .d8888b. .d8888b. 88  .dP  .d8888b. 
 * 88   `8b. 88ooood8 88'  `88 88   YP88 88ooood8 88'  `"" 88888"   88'  `88 
 * 88     88 88.  ... 88.  .88 Y8.   .88 88.  ... 88.  ... 88  `8b. 88.  .88 
 * dP     dP `88888P' `88888P8  `88888'  `88888P' `88888P' dP   `YP `88888P' 
 *
 *                          m a g n a l i s t e r
 *                                      boost your Online-Shop
 *
 * -----------------------------------------------------------------------------
 * $Id$
 *
 * (c) 2010 - 2014 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */
require_once(DIR_MAGNALISTER_INCLUDES.'lib/classes/MLProductList.php');

abstract class MLProductListMeinpaketAbstract extends MLProductList {
            
	protected $aPrepareData = array();
	protected function getPrepareData($aRow, $sFieldName = null) {
		if (!isset($this->aPrepareData[$aRow['products_id']])) {
                                    $this->aPrepareData[$aRow['products_id']] = MagnaDB::gi()->fetchRow("
				SELECT * 
				FROM ".TABLE_MAGNA_MEINPAKET_PROPERTIES." 
				WHERE 
					".(
						(getDBConfigValue('general.keytype', '0') == 'artNr')
							? 'products_model=\''.MagnaDB::gi()->escape($aRow['products_model']).'\''
							: 'products_id=\''.$aRow['products_id'].'\''
					)."
					AND mpID = '".$this->aMagnaSession['mpID']."'
			");
		}
		if($sFieldName === null){
			return $this->aPrepareData[$aRow['products_id']];
		}else{
			return isset($this->aPrepareData[$aRow['products_id']][$sFieldName]) ? $this->aPrepareData[$aRow['products_id']][$sFieldName] : null;
		}
	}
        
         protected function getMarketPlaceCategory($aRow) {
                        $aData = $this->getPrepareData($aRow);
                        if ($aData !== false) {
                                    return '<table class="nostyle"><tbody>
				<tr><td>MP:</td><td>'.(empty($aData['MarketplaceCategory']) ? '&mdash;' : $aData['MarketplaceCategory']).'</td><tr>
				<tr><td>Store:</td><td>'.(empty($aData['StoreCategory']) ? '&mdash;' : $aData['StoreCategory']).'</td><tr>
			</tbody></table>';
                        }
                        return '&mdash;';
            }

	protected function getSelectionName() {
		return 'apply';
	}

	protected function isPreparedDifferently($aRow) {
		$sPrimaryCategory = $this->getPrepareData($aRow, 'VariationConfiguration');
		if (!empty($sPrimaryCategory)) {
			$sCategoryDetails = $this->getPrepareData($aRow, 'CategoryAttributes');
			$categoryMatching = MeinpaketHelper::gi()->getCategoryMatching($sPrimaryCategory);
			$categoryDetails = json_decode($sCategoryDetails, true);
			return MeinpaketHelper::gi()->detectChanges($categoryMatching, $categoryDetails);
		}

		return false;
	}

	protected function isDeletedAttributeFromShop($aRow, &$message) {
	    $aVariationConfiguration = $this->getPrepareData($aRow, 'VariationConfiguration');
		if (!empty($aVariationConfiguration)) {
			$matchedAttributes = $this->getPrepareData($aRow, 'CategoryAttributes');
			$matchedAttributes = json_decode($matchedAttributes, true);
			$shopAttributes = MeinpaketHelper::gi()->flatShopVariations();

            if (!is_array($matchedAttributes)) {
                $matchedAttributes = array();
            }

			foreach ($matchedAttributes as $matchedAttribute) {
				if (MeinpaketHelper::gi()->detectIfAttributeIsDeletedOnShop($shopAttributes, $matchedAttribute, $message)) {
					return true;
				}
			}
		}

		return false;
	}
}
