<?php

// holds functions for manipulating products & categories
class megamenu {
    //new module support
    function __construct() {
        require_once (DIR_WS_CLASSES.'categoriesModules.class.php');
        $this->catModules = new categoriesModules();
    }
    
    // inserts / updates a product from given data
    function insert_megamenu_main($megamenu_data,  $action = 'insert') {
        global $messageStack;
        
        $megamenu_id = xtc_db_prepare_input($megamenu_data['megamenu_id']);
        
        $sql_data_array = array (
            'megamenu_type' => xtc_db_prepare_input($megamenu_data['megamenu_type']),
            'megamenu_name' => xtc_db_prepare_input($megamenu_data['megamenu_name']),
            'megamenu_title' => xtc_db_prepare_input($megamenu_data['megamenu_title']),
            'megamenu_link' => xtc_db_prepare_input($megamenu_data['megamenu_link']),
            'megamenu_status' => xtc_db_prepare_input($megamenu_data['megamenu_status']),
            'categories_id' => xtc_db_prepare_input($megamenu_data['categories_id']),
            'sort_order' => xtc_db_prepare_input($megamenu_data['sort_order']),
            'megamenu_text' => xtc_db_prepare_input(remove_word_code($megamenu_data['megamenu_text'])),
        );
        
        
        if ($action == 'insert') {
            $insert_sql_data = array ('date_added' => 'now()');
            $sql_data_array = xtc_array_merge($sql_data_array, $insert_sql_data);
            xtc_db_perform(TABLE_MEGAMENU, $sql_data_array);
            $megamenu_id = xtc_db_insert_id();
        } elseif ($action == 'update') {
            $sql_data_array['megamenu_status'] = $megamenu_data['megamenu_status'][$megamenu_id];
            $sql_data_array['megamenu_type'] = $megamenu_data['megamenu_type'][$megamenu_id];
            $update_sql_data = array ('last_modified' => 'now()');
            $sql_data_array = xtc_array_merge($sql_data_array, $update_sql_data);
            xtc_db_perform(TABLE_MEGAMENU, $sql_data_array, 'update', "megamenu_id = '".(int)$megamenu_id."'");
        }
        
        
        //MO_PICS
        $this->uploadMoImages($megamenu_id,$megamenu_data,$action);

        return $megamenu_id;
    }
    
 
    // Sets the status of a product
    function set_product_status($products_id, $status) {
        $sql_data_array = array('products_status' => $status,
            'products_last_modified' => 'now()');
        xtc_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '".$products_id."'");
    }
    
    
    // Sets a product active on startpage
    function set_product_startpage($products_id, $status) {
        $sql_data_array = array('products_startpage' => $status,
            'products_last_modified' => 'now()');
        xtc_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '".$products_id."'");
    }
    
    
    // Set a product remove on startpage
    function set_product_remove_startpage_sql($products_id, $status) {
        global $messageStack;
        
        if ($status == '0') {
            $check_query = xtc_db_query("SELECT COUNT(*) AS total
                                     FROM ".TABLE_PRODUCTS_TO_CATEGORIES."
                                    WHERE products_id = '".$products_id."'
                                      AND categories_id != '0'");
            $check = xtc_db_fetch_array($check_query);
            if ($check['total'] >= '1') {
                xtc_db_query("DELETE FROM ".TABLE_PRODUCTS_TO_CATEGORIES." WHERE products_id = '".$products_id."' and categories_id = '0'");
            }
        }
    }
    
    
    // Counts how many products exist in a category
    function count_category_products($category_id, $include_deactivated = false) {
        $products_count = 0;
        
        $where = '';
        if ($include_deactivated) {
            $where = " WHERE p.products_status = '1'";
        }
        $products_query = xtc_db_query("SELECT count(*) as total
                                      FROM ".TABLE_PRODUCTS." p
                                      JOIN ".TABLE_PRODUCTS_TO_CATEGORIES." p2c
                                           ON p.products_id = p2c.products_id
                                              AND p2c.categories_id = '".(int)$category_id."'
                                           ".$where);
        $products = xtc_db_fetch_array($products_query);
        $products_count += $products['total'];
        $childs_query = xtc_db_query("SELECT categories_id
                                    FROM ".TABLE_CATEGORIES."
                                   WHERE parent_id = '".(int)$category_id."'");
        if (xtc_db_num_rows($childs_query)) {
            while ($childs = xtc_db_fetch_array($childs_query)) {
                $products_count += $this->count_category_products($childs['categories_id'], $include_deactivated);
            }
        }
        return $products_count;
    }
    
    
    // Counts how many subcategories exist in a category
    function count_category_childs($category_id) {
        $categories_count = 0;
        $categories_query = xtc_db_query("SELECT categories_id
                                        FROM ".TABLE_CATEGORIES."
                                       WHERE parent_id = '".(int)$category_id."'");
        while ($categories = xtc_db_fetch_array($categories_query)) {
            $categories_count ++;
            $categories_count += $this->count_category_childs($categories['categories_id']);
        }
        return $categories_count;
    }
    
    
    function edit_cross_sell($cross_data) {
        if ($cross_data['special'] == 'add_entries') {
            if (isset ($cross_data['ids'])) {
                foreach ($cross_data['ids'] AS $pID) {
                    $sql_data_array = array ('products_id' => $cross_data['current_product_id'],
                        'xsell_id' => $pID,
                        'products_xsell_grp_name_id' => $cross_data['group_name'][$pID]);
                    // check if product is already linked
                    $check_query = xtc_db_query("SELECT *
                                         FROM ".TABLE_PRODUCTS_XSELL."
                                        WHERE products_id = '".$cross_data['current_product_id']."'
                                          AND xsell_id = '".$pID."'");
                    if (xtc_db_num_rows($check_query) < 1) {
                        xtc_db_perform(TABLE_PRODUCTS_XSELL, $sql_data_array);
                    }
                }
            }
        }
        
        if ($cross_data['special'] == 'edit') {
            if (isset ($cross_data['ids'])) {
                // delete
                foreach ($cross_data['ids'] AS $pID) {
                    xtc_db_query("DELETE FROM ".TABLE_PRODUCTS_XSELL." WHERE ID='".$pID."'");
                }
            }
            if (isset ($cross_data['sort'])) {
                // edit sorting
                foreach ($cross_data['sort'] AS $ID => $sort_order) {
                    $sql_data_array = array('sort_order' => $sort_order,
                        'products_xsell_grp_name_id' => $cross_data['group_name'][$ID]);
                    xtc_db_perform(TABLE_PRODUCTS_XSELL, $sql_data_array, 'update', "ID = '".$ID."'");
                }
            }
        }
    }
    
    
    function add_data_fields($add_data_string, $data, $language_id = '') {
        $add_data_array = explode(',',preg_replace("'[\r\n\s]+'",'',$add_data_string));
        $add_data_fields_array = array();
        for ($i = 0, $n = sizeof($add_data_array); $i < $n; $i ++) {
            if ($language_id != '') {
                $add_data_fields_array[$add_data_array[$i]] = xtc_db_prepare_input(implode(',', (array)$data[$add_data_array[$i]][$language_id]));
            } else {
                $add_data_fields_array[$add_data_array[$i]] = xtc_db_prepare_input(implode(',', (array)$data[$add_data_array[$i]]));
            }
        }
        return $add_data_fields_array;
    }
    
    
    function create_templates_dropdown_menu($template, $path, $default_value, $style = '') {
        $files = array ();
        
        if (is_dir(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.$path)) {
            foreach(auto_include(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.$path, 'html') as $file) {
                $files[] = array (
                    'id' => basename($file),
                    'text' => basename($file),
                );
            }
        }
        
        $default_array = array (array ('id' => 'default', 'text' => (count($files) > 0) ? TEXT_SELECT : TEXT_NO_FILE));
        $files = array_merge($default_array, $files);
        return xtc_draw_pull_down_menu($template, $files, $default_value, $style);
    }
    
    
    //set products images file rights
    function set_products_images_file_rights($image_name) {
        if ($image_name != '') {
            @ chmod(DIR_FS_CATALOG_INFO_IMAGES.$image_name, 0644);
            @ chmod(DIR_FS_CATALOG_THUMBNAIL_IMAGES.$image_name, 0644);
            @ chmod(DIR_FS_CATALOG_POPUP_IMAGES.$image_name, 0644);
        }
    }
    
    
    function create_permission_checkboxes($t_array) {
        $customers_statuses_array = xtc_get_customers_statuses();
        $input = '<label>' . xtc_draw_checkbox_field('groups[]', 'all', '','', 'id="cgAll"').TXT_ALL.'</label><br />'. PHP_EOL;
        for ($i = 0, $n = sizeof($customers_statuses_array); $i < $n; $i ++) {
            $checked = ($t_array['group_permission_'.$customers_statuses_array[$i]['id']] == 1)? ' checked' : '';
            $preselect = $i==0 ? true : false; //preselect all
            //$preselect = $customers_statuses_array[$i]['id']=='0' ? true : false; //preselect admin
            if( !isset($_GET['pID']) && !isset($_GET['cID']) && $preselect) {
                $checked = ' checked';
            }
            $input .= '<label>'.  xtc_draw_checkbox_field('groups[]', $customers_statuses_array[$i]['id'], $checked,'', 'id="cg'.$customers_statuses_array[$i]['id'].'"') . $customers_statuses_array[$i]['text'].'</label><br />'. PHP_EOL;
        }
        return $input;
    }
    
    
    function get_categories_desc_fields($category_id, $language_id) {
        if (!empty($category_id)) {
            if (empty($language_id)) {
                $language_id = $_SESSION['languages_id'];
            }
            $category_query = xtc_db_query("SELECT *
                                        FROM ".TABLE_CATEGORIES_DESCRIPTION."
                                       WHERE categories_id = '".(int)$category_id."'
                                         AND language_id = '".(int)$language_id."'");
            return xtc_db_fetch_array($category_query);
        }
    }
    
    
    function get_products_desc_fields($product_id, $language_id) {
        if (!empty($product_id)) {
            if (empty($language_id)) {
                $language_id = $_SESSION['languages_id'];
            }
            $product_query = xtc_db_query("SELECT *
                                       FROM ".TABLE_PRODUCTS_DESCRIPTION."
                                      WHERE products_id = '".(int)$product_id."'
                                        AND language_id = '".(int)$language_id."'");
            return xtc_db_fetch_array($product_query);
        }
    }
    
    
    function set_page_parameter() {
        $this->page_parameter = isset($_GET['page']) ? '&page='.(int)$_GET['page'] : '';
        $this->page_parameter_plain = isset($_GET['page']) ? 'page='.(int)$_GET['page'] : '';
    }
    
    
    function uploadImage($products_id, $products_data) {
        $accepted_products_image_files_extensions = array("jpg","jpeg","jpe","gif","png","bmp","tiff","tif","bmp");
        $accepted_products_image_files_mime_types = array("image/jpeg","image/gif","image/png","image/bmp");
        
        //are we asked to delete some pics?
        if (isset($products_data['del_pic']) && $products_data['del_pic'] != '') {
            $dup_check_query = xtc_db_query("SELECT COUNT(*) AS total
                                         FROM ".TABLE_PRODUCTS."
                                        WHERE products_image = '".xtc_db_input($products_data['del_pic'])."'");
            $dup_check = xtc_db_fetch_array($dup_check_query);
            if ($dup_check['total'] < 2) {
                @xtc_del_image_file($products_data['del_pic']);
            }
            xtc_db_query("UPDATE ".TABLE_PRODUCTS."
                       SET products_image = NULL
                     WHERE products_id    = '".(int)$products_id."'");
        }
        
        if ($products_image = xtc_try_upload('products_image', DIR_FS_CATALOG_ORIGINAL_IMAGES, '777', $accepted_products_image_files_extensions, $accepted_products_image_files_mime_types)) {
            $pname_arr = explode('.', $products_image->filename);
            $nsuffix = array_pop($pname_arr);
            $products_image_name = $products_image_name_process = $this->image_name($products_id, 0, $nsuffix, $pname_arr, false, $products_data);
            $dup_check_query = xtc_db_query("SELECT COUNT(*) AS total
                                        FROM ".TABLE_PRODUCTS."
                                       WHERE products_image = '".xtc_db_input($products_data['products_previous_image_0'])."'");
            $dup_check = xtc_db_fetch_array($dup_check_query);
            if ($dup_check['total'] < 2) {
                @ xtc_del_image_file($products_data['products_previous_image_0']);
            }
            //workaround if there are v2 images mixed with v3
            $dup_check_query = xtc_db_query("SELECT COUNT(*) AS total
                                         FROM ".TABLE_PRODUCTS."
                                        WHERE products_image = '".xtc_db_input($products_image->filename)."'");
            $dup_check = xtc_db_fetch_array($dup_check_query);
            if ($dup_check['total'] == 0) {
                rename(DIR_FS_CATALOG_ORIGINAL_IMAGES.$products_image->filename, DIR_FS_CATALOG_ORIGINAL_IMAGES.$products_image_name);
            } else {
                copy(DIR_FS_CATALOG_ORIGINAL_IMAGES.$products_image->filename, DIR_FS_CATALOG_ORIGINAL_IMAGES.$products_image_name);
            }
            
            //image processing
            $this->image_process($products_image_name, $products_image_name_process);
            
            //set file rights
            $this->set_products_images_file_rights($products_image_name);
            
            return $products_image_name;
        }
        return false;
    }
    
    
    function uploadMoImages($megamenu_id, $megamenu_data, $action) {
        $accepted_mo_pics_image_files_extensions = array("jpg","jpeg","jpe","gif","png","bmp","tiff","tif","bmp");
        $accepted_mo_pics_image_files_mime_types = array("image/jpeg","image/gif","image/png","image/bmp");
        
        //are we asked to delete some pics?
        if (isset($megamenu_data['del_mo_pic']) && count($megamenu_data['del_mo_pic']) > 0) {
            foreach ($megamenu_data['del_mo_pic'] as $dummy => $val) {
                $dup_check_query = xtc_db_query("SELECT COUNT(*) AS total
                                           FROM ".TABLE_MEGAMENU_IMAGES."
                                          WHERE image_name = '".xtc_db_input($val)."'");
                $dup_check = xtc_db_fetch_array($dup_check_query);
                if ($dup_check['total'] < 2) {
                    @ xtc_del_image_file($val);
                }
                xtc_db_query("DELETE FROM ".TABLE_MEGAMENU_IMAGES." WHERE megamenu_id = '".(int)$megamenu_id."' AND image_name  = '".xtc_db_input($val)."'");
            }
        }
        
        for ($img = 0; $img < 10; $img ++) {
            if ($pIMG = xtc_try_upload('mo_pics_'.$img, DIR_FS_CATALOG_IMAGES.'megamenu/', '777', $accepted_mo_pics_image_files_extensions, $accepted_mo_pics_image_files_mime_types)) {
                $pname_arr = explode('.', $pIMG->filename);
                $nsuffix = array_pop($pname_arr);
                $products_image_name = $products_image_name_process = $this->image_name($megamenu_id, ($img +1), $nsuffix, $pname_arr, false, $megamenu_data);
                $dup_check_query = xtc_db_query("SELECT COUNT(*) AS total
                                           FROM ".TABLE_MEGAMENU_IMAGES."
                                          WHERE image_name = '".xtc_db_input($megamenu_data['products_previous_image_'. ($img +1)])."'");
                $dup_check = xtc_db_fetch_array($dup_check_query);
                if ($dup_check['total'] < 2) {
                    @ xtc_del_image_file($megamenu_data['products_previous_image_'. ($img +1)]);
                }
                rename(DIR_FS_CATALOG_IMAGES.'megamenu'.'/'.$pIMG->filename, DIR_FS_CATALOG_IMAGES.'megamenu/'.$products_image_name);
                //get data & write to table
                $mo_img = array ('megamenu_id' => xtc_db_prepare_input($megamenu_id),
                    'image_nr' => xtc_db_prepare_input($img +1),
                    'image_name' => xtc_db_prepare_input($products_image_name));
                
                if ($action == 'insert' || !$dup_check['total']) {
                    xtc_db_perform(TABLE_MEGAMENU_IMAGES, $mo_img);
                } elseif ($action == 'update' && $dup_check['total']) {
                    xtc_db_perform(TABLE_MEGAMENU_IMAGES, $mo_img, 'update', "image_name = '".xtc_db_input($megamenu_data['products_previous_image_'. ($img +1)])."'");
                }

            }
        }
    }
    
    
    
    function image_name($data_id, $counter, $suffix, $name_arr = array(), $srcID = false, $data_arr = array()) {
        $separator = (((string)$counter != '') ? '_' : '');
        $image_name = $data_id.$separator.$counter.'.'.$suffix;
        //new module support
        $image_name = $this->catModules->image_name($image_name, $data_id, $counter, $suffix, $name_arr, $srcID, $data_arr);
        return $image_name;
    }
    
    
    function priceCheck($price, $products_tax_rate) {
        if (PRICE_IS_BRUTTO == 'true' && $price) {
            $price = round(($price / ($products_tax_rate + 100) * 100), PRICE_PRECISION);
        } else {
            $price = xtc_round($price, PRICE_PRECISION);
        }
        return $price;
    }
    
    
    function saveSpecialsData($products_data) {
        // insert or update specials
        if (isset($products_data['specials_price']) && !empty($products_data['specials_price'])) {
            if (!isset($products_data['specials_quantity']) || empty($products_data['specials_quantity'])) {
                $products_data['specials_quantity'] = 0;
            }
            
            if (substr($products_data['specials_price'], -1) != '%') {
                if (!isset($products_data['products_tax_class_id']) || (int)$products_data['products_tax_class_id'] <= 0) {
                    $price_query = xtc_db_query("SELECT products_tax_class_id
                                         FROM ".TABLE_PRODUCTS."
                                        WHERE products_id = '".(int)$products_data['products_id']."'");
                    $price = xtc_db_fetch_array($price_query);
                    $products_data['products_tax_class_id'] = $price['products_tax_class_id'];
                }
                $tax_rate = xtc_get_tax_rate($products_data['products_tax_class_id']);
                
                $products_data['specials_price'] = $this->priceCheck($products_data['specials_price'], $tax_rate);
            } else {
                if (!isset($products_data['products_price']) || (double)$products_data['products_price'] <= 0.00) {
                    $price_query = xtc_db_query("SELECT products_price
                                         FROM ".TABLE_PRODUCTS."
                                        WHERE products_id = '".(int)$products_data['products_id']."'");
                    $price = xtc_db_fetch_array($price_query);
                    $products_data['products_price'] = $price['products_price'];
                }
                $products_data['specials_price'] = ($products_data['products_price'] - (($products_data['specials_price'] / 100) * $products_data['products_price']));
            }
            
            $expires_date = isset($products_data['specials_expires']) && !empty($products_data['specials_expires']) ? date('Y-m-d H:i:s', strtotime($products_data['specials_expires'].' 23:59:59')) : '';
            $start_date = isset($products_data['specials_start']) && !empty($products_data['specials_start']) ? date('Y-m-d H:i:s', strtotime($products_data['specials_start'].' 00:00:00')) : '';
            
            $sql_data_array = array('products_id' => $products_data['products_id'],
                'specials_quantity' => (int)$products_data['specials_quantity'],
                'specials_new_products_price' => xtc_db_prepare_input($products_data['specials_price']),
                'specials_date_added' => 'now()',
                'specials_last_modified' => 'now()',
                'start_date' => $start_date,
                'expires_date' => $expires_date,
                'status' => ((isset($products_data['specials_status'])) ? (int)$products_data['specials_status'] : '1')
            );
            
            //new module support
            $sql_data_array = $this->catModules->saveSpecialsData($sql_data_array,$products_data);
            
            if ($products_data['specials_action'] == 'insert') {
                unset($sql_data_array['specials_last_modified']);
                xtc_db_perform(TABLE_SPECIALS, $sql_data_array);
                $products_data['specials_id'] = xtc_db_insert_id();
            } else {
                unset($sql_data_array['specials_date_added']);
                xtc_db_perform(TABLE_SPECIALS, $sql_data_array, 'update', "specials_id = '" . (int)$products_data['specials_id']  . "'" );
            }
        }
        
        // delete specials
        if(isset($products_data['specials_delete'])) {
            xtc_db_query("DELETE FROM " . TABLE_SPECIALS . " WHERE specials_id = '" . xtc_db_input($products_data['specials_id']) . "'");
        }
        
        return $products_data['specials_id'];
    }
    
    function save_products_tags($products_data,$products_id)
    {
        if (isset($products_data['options_id']) && is_array($products_data['options_id'])) {
            foreach ($products_data['options_id'] as $options_id) {
                $options_id = str_replace('oid-','',$options_id);
                xtc_db_query("DELETE FROM ".TABLE_PRODUCTS_TAGS." WHERE products_id = '".(int)$products_id."' AND options_id = '".(int)$options_id."'");
            }
        }
        
        if (isset($products_data['products_tags_save'])) {
            xtc_db_query("DELETE FROM ".TABLE_PRODUCTS_TAGS." WHERE products_id = '".(int)$products_id."'");
        }
        
        if (isset($products_data['product_tags']) && is_array($products_data['product_tags'])) {
            foreach ($products_data['product_tags'] as $options_id => $value) {
                foreach ($value as $values_id => $subvalue) {
                    if ($subvalue == 'on') {
                        $sql_data_array = array(
                            'products_id' => (int)$products_id,
                            'options_id' => (int)$options_id,
                            'values_id' => (int)$values_id,
                            'sort_order' => $products_data['product_tags_sort'][(int)$options_id][(int)$values_id],
                        );
                        xtc_db_perform(TABLE_PRODUCTS_TAGS, $sql_data_array);
                    }
                }
            }
        }
        
    }
    
    function calculate_attribute_prices($products_data,$products_id) {
        
        $products_tax_rate_old = xtc_get_tax_rate($products_data['products_tax_class_id_old']);
        
        $attributes_query = xtc_db_query("SELECT *
                                          FROM ".TABLE_PRODUCTS_ATTRIBUTES."
                                         WHERE products_id = '".$products_id."'
                                           AND options_values_price > 0");
        if (xtc_db_num_rows($attributes_query) > 0) {
            while ($attributes = xtc_db_fetch_array($attributes_query)) {
                $values_price_brutto = $attributes['options_values_price'] * (1 + ($products_tax_rate_old / 100));
                
                $values_price_netto = $this->priceCheck($values_price_brutto, $products_tax_rate);
                xtc_db_query("UPDATE ".TABLE_PRODUCTS_ATTRIBUTES."
                           SET options_values_price = '".xtc_db_input($values_price_netto)."'
                         WHERE products_attributes_id = '".$attributes['products_attributes_id']."'");
            }
        }
    }
    
}
?>