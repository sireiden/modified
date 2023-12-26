ALTER TABLE admin_access ADD (lexikon int(1) NOT NULL default '0');
UPDATE admin_access SET lexikon='1' WHERE customers_id='1';
INSERT INTO configuration VALUES ('', 'MODULE_LEXIKON_STATUS', 'true', '1', '50', NULL, now(), 'NULL','xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration VALUES ('', 'MODULE_LEXIKON_TYPE', 'popup', '1', '51', NULL, now(), 'NULL','xtc_cfg_select_option(array(\'popup\', \'acronym\'),');
INSERT INTO configuration VALUES ('', 'PRODUCT_LIST_ITEMS_PER_PAGE', '10,20,30', 8, 3, NULL, now(), NULL, NULL); 
	 
CREATE TABLE lexikon (
id INT(5) NOT NULL AUTO_INCREMENT,
keyword VARCHAR(100) NOT NULL,
description TEXT NOT NULL,
PRIMARY KEY (id));