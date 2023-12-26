<?php

/* -----------------------------------------------------------------------------------------
   Interne Zahlungsart: KAR
   ---------------------------------------------------------------------------------------*/

class karintern {
	var $code, $title, $description, $enabled;

	function __construct() {
		$this->code = 'karintern';
		$this->title = MODULE_PAYMENT_KARINTERN_TEXT_TITLE;
		$this->description = MODULE_PAYMENT_KARINTERN_TEXT_DESCRIPTION;
		$this->sort_order = MODULE_PAYMENT_KARINTERN_SORT_ORDER;
		$this->enabled = ((MODULE_PAYMENT_KARINTERN_STATUS == 'True') ? true : false);
		$this->info = MODULE_PAYMENT_KARINTERN_TEXT_INFO;
	}

	function update_status() {
		global $order;

		if (($this->enabled == true) && ((int) MODULE_PAYMENT_KARINTERN_ZONE > 0)) {
			$check_flag = false;
			$check_query = xtc_db_query("select zone_id from ".TABLE_ZONES_TO_GEO_ZONES." where geo_zone_id = '".MODULE_PAYMENT_KARINTERN_ZONE."' and zone_country_id = '".$order->billing['country']['id']."' order by zone_id");
			while ($check = xtc_db_fetch_array($check_query)) {
				if ($check['zone_id'] < 1) {
					$check_flag = true;
					break;
				}
				elseif ($check['zone_id'] == $order->billing['zone_id']) {
					$check_flag = true;
					break;
				}
			}

			if ($check_flag == false) {
				$this->enabled = false;
			}
		}
	}

	function javascript_validation() {
		return false;
	}

	function selection() {
		return false;		
	}

	function pre_confirmation_check() {
		return false;
	}

	function confirmation() {
				return false;		
    return array ('title' => MODULE_PAYMENT_KARINTERN_TEXT_DESCRIPTION);
	}

	function process_button() {
		return false;
	}

	function before_process() {
		return false;
	}

	function after_process() {
		global $insert_id;
		if ($this->order_status)
			xtc_db_query("UPDATE ".TABLE_ORDERS." SET orders_status='".$this->order_status."' WHERE orders_id='".$insert_id."'");

	}

	function get_error() {
		return false;
	}

	function check() {
		if (!isset ($this->_check)) {
			$check_query = xtc_db_query("select configuration_value from ".TABLE_CONFIGURATION." where configuration_key = 'MODULE_PAYMENT_KARINTERN_STATUS'");
			$this->_check = xtc_db_num_rows($check_query);
		}
		return $this->_check;
	}

	function install() {
		xtc_db_query("insert into ".TABLE_CONFIGURATION." ( configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_PAYMENT_KARINTERN_STATUS', 'True', '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now());");
		xtc_db_query("insert into ".TABLE_CONFIGURATION." ( configuration_key, configuration_value,  configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_KARINTERN_SORT_ORDER', '0', '6', '0', now())");
  }

	function remove() {
		xtc_db_query("delete from ".TABLE_CONFIGURATION." where configuration_key in ('".implode("', '", $this->keys())."')");
	}

	function keys() {
		return array ('MODULE_PAYMENT_KARINTERN_STATUS', 'MODULE_PAYMENT_KARINTERN_SORT_ORDER');
	}
}
?>