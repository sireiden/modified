<?php


$truncate_paypal_payment_stmt = $dblink->prepare('DELETE FROM `ascasa_ng_db`.`configuration WHERE configuration_group_id = 910');
$truncate_paypal_payment_stmt->execute();

$insert_paypal_payment_stmt = $dblink->prepare('INSERT INTO ascasa_ng_db.configuration (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) SELECT configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function FROM mod_ascasa_live.configuration WHERE configuration_group_id = 910 ;');
$insert_paypal_payment_stmt->execute();