<?php
/*
 * --------------------------------------------------------------------------
 * @file      webnull_attribute_price_updater.php
 * @date      18.10.17
 *
 *
 * LICENSE:   Released under the GNU General Public License
 * --------------------------------------------------------------------------
 */
//BOF - webnull_attribute_price_updater
class web0null_attribute_price_updater {
  public $title,
         $name,
         $description,
         $enabled;

  /**
   * web0null_attribute_price_updater constructor.
   */
  public function __construct() {
    $this->code        = __CLASS__;
    $this->name        = strtoupper('MODULE_' . $this->code);
    $this->title       = $this->getConstant('TITLE');
    $this->description = $this->getConstant('DESCRIPTION');
    $this->enabled     = ($this->getConstant('STATUS') == 'true');
  }

  /**
   * process
   * @param $file
   */
  public function process($file) {

  }

  /**
   * display
   *
   * @return array
   */
  public function display() {
    return [
      'text' => '<br>' . xtc_button(BUTTON_SAVE) . '&nbsp;' . xtc_button_link(BUTTON_CANCEL, xtc_href_link(FILENAME_MODULE_EXPORT, 'set=' . $_GET['set'] . '&module=' . $this->code)),
    ];
  }

  /**
   * check
   *
   * @return mixed
   */
  public function check() {
    if (!isset($this->_check)) {
      $query        = xtc_db_query("-- " . __LINE__ . __FILE__ . "
        SELECT configuration_value
        FROM   " . TABLE_CONFIGURATION . "
        WHERE  configuration_key = '" . $this->getDefine('STATUS') . "'"
      );
      $this->_check = xtc_db_num_rows($query);
    }
    return $this->_check;
  }

  /**
   * keys
   *
   * @return array
   */
  public function keys() {
    return [
      $this->getDefine('STATUS'),
      $this->getDefine('ADDITIONAL'),
      $this->getDefine('UPDATE_PRICE'),
    ];
  }

  /**
   * install
   */
  public function install() {
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES
      ('" . $this->getDefine('STATUS') . "', 'true', 6, 1, NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),', NOW()),
      ('" . $this->getDefine('ADDITIONAL') . "', 'true', 6, 1, NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),', NOW()),
      ('" . $this->getDefine('UPDATE_PRICE') . "', 'false', 6, 1, NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),', NOW());
    ");
    $this->install_db();
  }

  /**
   * remove
   */
  public function remove() {
    xtc_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key LIKE '" . $this->getDefine() . "%'");
    $this->uninstall_db();
  }

  /**
   * install_db
   */
  public function install_db() {
  }

  /**
   * uninstall_db
   */
  public function uninstall_db() {

  }

  /**
   * getConstant
   *
   * @param string $name
   * @return mixed|string
   */
  public function getConstant($name = '') {
    $constant = $this->getDefine($name);
    return defined($constant) ? constant($constant) : '';
  }

  /**
   * getDefine
   *
   * @param string $name
   * @return string
   */
  public function getDefine($name = '') {
    return strtoupper($this->name . '_' . $name);
  }
}
//EOF - web0null_attribute_price_updater