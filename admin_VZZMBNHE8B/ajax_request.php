<?php
/*
 * Handler für Ajax-Requests 
 */
require('includes/application_top.php');

if(isset($_POST['action']) && $_POST['action'] == 'check_model') {

    $product_query = xtc_db_query("SELECT pd.products_name, p.products_id
                                     FROM ".TABLE_PRODUCTS." p,
                                          ".TABLE_PRODUCTS_DESCRIPTION." pd
                                    WHERE p.products_model = '".(string) $_POST['model']."'
                                      AND p.products_id = pd.products_id
                                      AND pd.language_id = '".$_SESSION['languages_id']."'");
    
    if(xtc_db_num_rows($product_query) == 0) {
        echo json_encode(array('response' => 'Es wurde kein Artikel mit der Modellnummer gefunden', 'products_id' => ' '));
    }
    else if(xtc_db_num_rows($product_query) == 1) {
        $product = xtc_db_fetch_array($product_query);
        $products_link = '<a target="_blank" href="'. xtc_href_link(FILENAME_CATEGORIES, xtc_get_all_get_params(array('cPath', 'action', 'pID', 'cID')) . 'cPath=0&pID=' . $product['products_id'] ) . '&action=new_product' . '">' . utf8_encode($product['products_name']). '</a>';
        echo json_encode(array('response' => $products_link, 'products_id' => $product['products_id'] ));
    }
    else {
        echo json_encode(array('response' => 'Es wurde kein eindeutiger Artikel mit der Modellnummer gefunden', 'products_id' => ' '));
    }
    exit;
}

if(isset($_POST['action']) && $_POST['action'] == 'check_attachment_products') {
    $product_query = xtc_db_query("SELECT pd.products_name, p.products_id
                                     FROM ".TABLE_PRODUCTS." p,
                                          ".TABLE_PRODUCTS_DESCRIPTION." pd
                                    WHERE p.products_model = '".(string) $_POST['model']."'
                                      AND p.products_id = pd.products_id
                                      AND pd.language_id = '".$_SESSION['languages_id']."'");
    
    if(xtc_db_num_rows($product_query) == 0) {
        echo json_encode(array('response' => 'Es wurde kein Artikel mit der Modellnummer gefunden', 'products_id' => ' ', 'product_attachments' => ''));
    }
    else if(xtc_db_num_rows($product_query) == 1) {
        
        $product = xtc_db_fetch_array($product_query);
        $products_link = '<a target="_blank" href="'. xtc_href_link(FILENAME_CATEGORIES, xtc_get_all_get_params(array('cPath', 'action', 'pID', 'cID')) . 'cPath=0&pID=' . $product['products_id'] ) . '&action=new_product' . '">' . utf8_encode($product['products_name']). '</a>';
        
        $product_attachments_result = xtc_db_query("SELECT pd.products_name, p.products_id, p2a.attachment_id
                                     		FROM ".TABLE_PRODUCTS." p,
                                          	".TABLE_PRODUCTS_DESCRIPTION." pd,
											".TABLE_PRODUCTS_TO_ATTACHMENTS." p2a
                                    		WHERE p2a.products_id = '".(int) $product['products_id']."'
                                      		AND p2a.attachment_id = pd.products_id
											AND p2a.products_id = p.products_id
                                      		AND pd.language_id = '".$_SESSION['languages_id']."'
											ORDER BY sort ASC, attachment_id ASC");
        $attachment_list = '';
        while($products_attachments = xtc_db_fetch_array($product_attachments_result)) {
            $attachment_list .= $products_attachments['products_name']."<br>".PHP_EOL;
        }
        
        echo json_encode(array('response' => $products_link, 'products_id' => $product['products_id'], 'product_attachments' => utf8_encode($attachment_list)));
    }
    else {
        echo json_encode(array('response' => 'Es wurde kein eindeutiger Artikel mit der Modellnummer gefunden', 'products_id' => ' ',  'product_attachments' => ''));
    }
    exit;
}


if(isset($_POST['action']) && $_POST['action'] == 'transfer_attachment_products') {
    $product_query = xtc_db_query("SELECT pd.products_name, p.products_id
                                     FROM ".TABLE_PRODUCTS." p,
                                          ".TABLE_PRODUCTS_DESCRIPTION." pd
                                    WHERE p.products_model = '".(string) $_POST['target_model']."'
                                      AND p.products_id = pd.products_id
                                      AND pd.language_id = '".$_SESSION['languages_id']."'");
    
    if(xtc_db_num_rows($product_query) == 0) {
        echo json_encode(array('success' => false, 'response' => 'Es wurde kein Artikel mit der Modellnummer '.$_POST['target_model'].' gefunden'));
    }
    else if(xtc_db_num_rows($product_query) == 1) {
        $product = xtc_db_fetch_array($product_query);
        $products_link = '<a target="_blank" href="'. xtc_href_link(FILENAME_CATEGORIES, xtc_get_all_get_params(array('cPath', 'action', 'pID', 'cID')) . 'cPath=0&pID=' . $product['products_id'] ) . '&action=new_product' . '">' . utf8_encode($product['products_name']). '</a>';
        
        $product_attachments_result = xtc_db_query("SELECT * FROM ".TABLE_PRODUCTS_TO_ATTACHMENTS." p2a
                                    		WHERE products_id = '".(int) $_POST['products_id']."'
											ORDER BY sort ASC, attachment_id ASC");
        $i=0;
        while($products_attachments = xtc_db_fetch_array($product_attachments_result)) {
            if($i==0) {
                xtc_db_query("DELETE FROM ".TABLE_PRODUCTS_TO_ATTACHMENTS." WHERE products_id   = '".$product['products_id']."'");
            }
            
            if(!empty($products_attachments['attachment_id'])) {
                xtc_db_query("INSERT INTO ".TABLE_PRODUCTS_TO_ATTACHMENTS."
                              SET products_id   = '".$product['products_id']."',
                              attachment_id = '".xtc_db_prepare_input($products_attachments['attachment_id'])."',
                              sort = '".xtc_db_prepare_input($products_attachments['sort'])."'");
                $i++;
            }
        }
        echo json_encode(array('success' => true, 'response' => utf8_encode('Es wurden '.$i.' Zubehörartikel auf den folgenden Artikel übertragen: ') .$products_link));
    }
    else {
        echo json_encode(array('success' => false, 'response' => 'Es wurde kein eindeutiger Artikel mit der Modellnummer '.$_POST['target_model'].' gefunden'));
    }
    exit;
}

if(isset($_POST['action']) && $_POST['action'] == 'remove_attachment') {
    $product_query = xtc_db_query("DELETE FROM ".TABLE_PRODUCTS_TO_ATTACHMENTS." WHERE attachment_id = '".(int)$_POST['attachment_id']."' AND products_id = '".(int) $_POST['products_id']."'");
    echo 'done';
    exit; 
}


if(isset($_POST['action']) && $_POST['action'] == 'get_mail_template') {
    $mail_template_query=xtc_db_query("SELECT mail_text FROM ".TABLE_MAIL_TEMPLATES." WHERE id='".(int)$_POST['template_id']."'");
    $mail_template=xtc_db_fetch_array($mail_template_query);
    echo json_encode(array('success' => true, 'response' => utf8_encode($mail_template['mail_text'])));
    exit;
}