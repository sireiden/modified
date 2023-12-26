<?php
/* -----------------------------------------------------------------------------------------
 Anfragen eines Sonderpreises
 ---------------------------------------------------------------------------------------*/

require ('includes/application_top.php');

// include needed functions
require_once (DIR_FS_INC.'xtc_get_country_list.inc.php');
require_once (DIR_FS_INC.'xtc_validate_email.inc.php');
require_once (DIR_FS_INC.'get_customers_gender.inc.php');
require_once (DIR_FS_INC.'parse_multi_language_value.inc.php');
require_once (DIR_FS_INC.'secure_form.inc.php');
require_once (DIR_WS_LANGUAGES.$_SESSION['language'].'/contact_us.php');

$popup_smarty = new Smarty;

$popup_smarty->assign('tpl_path', DIR_WS_BASE.'templates/'.CURRENT_TEMPLATE.'/');
$popup_smarty->assign('html_params', ((TEMPLATE_HTML_ENGINE == 'xhtml') ? ' '.HTML_PARAMS : ' lang="'.$_SESSION['language_code'].'"'));
$popup_smarty->assign('doctype', ((TEMPLATE_HTML_ENGINE == 'xhtml') ? ' PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"' : ''));
$popup_smarty->assign('charset', $_SESSION['language_charset']);
$popup_smarty->assign('title', htmlspecialchars('Sonderpreis anfragen', ENT_QUOTES, strtoupper($_SESSION['language_charset'])));
if (DIR_WS_BASE == '') {
    $popup_smarty->assign('base', (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG);
}
$popup_smarty->assign('language', $_SESSION['language']);


// captcha
$use_captcha = array('contact');
if (defined('MODULE_CAPTCHA_ACTIVE')) {
    $use_captcha = explode(',', MODULE_CAPTCHA_ACTIVE);
}
defined('MODULE_CAPTCHA_CODE_LENGTH') or define('MODULE_CAPTCHA_CODE_LENGTH', 6);
defined('MODULE_CAPTCHA_LOGGED_IN') or define('MODULE_CAPTCHA_LOGGED_IN', 'True');

$action = isset($_GET['action']) && $_GET['action'] != '' ? $_GET['action'] : '';

$error = false;
if ($action == 'send') {
    
    if(!empty($_GET['products_id'])) {
        $products_query = xtc_db_query("SELECT p.products_id, p.products_status, pf.products_specialrequest FROM ".TABLE_PRODUCTS." p JOIN ".TABLE_PRODUCTS_FIELDS." pf ON p.products_id = pf.products_id WHERE p.products_id = '".(int)$_GET['products_id']."' ");
        $product = xtc_db_fetch_array($products_query);
        if(empty($product) || $product['products_status'] != 1 || $product['products_specialrequest'] != 1) {
            xtc_redirect(xtc_href_link('popup_sonderpreis.php', 'action=no_success&products_id='.(int) $_GET['products_id']));
        }
    }
    else {
     xtc_redirect(xtc_href_link('popup_sonderpreis.php', 'action=no_success'));
    }
    
    $valid_params = array(
        'gender',
        'firstname',
        'lastname',
        'company',
        'message_body',
        'phone',
        'price',
        'email'
    );
    
    // prepare variables
    foreach ($_POST as $key => $value) {
        if (!is_object(${$key}) && in_array($key , $valid_params)) {
            ${$key} = xtc_db_prepare_input($value);
        }
    }
    
    //jedes Feld kann hier auf die gewünschte Bedingung getestet und eine Fehlermeldung zugeordnet werden
    if (trim($gender) == '') {
        $messageStack->add('price_request', '<p><b>Ihre Anrede:</b> Keine oder ung&uuml;ltige Eingabe!</p>');
        $error = true;
    }
    if (trim($firstname) == '') {
        $messageStack->add('price_request', '<p><b>Ihr Vorname:</b> Keine oder ung&uuml;ltige Eingabe!</p>');
        $error = true;
    }
    if (trim($lastname) == '') {
        $messageStack->add('price_request', '<p><b>Ihr Nachname:</b> Keine oder ung&uuml;ltige Eingabe!</p>');
        $error = true;
    }
    if (!xtc_validate_email(trim($email))) {
        $messageStack->add('price_request', ERROR_EMAIL);
        $error = true;
    }
    
    if (in_array('contact', $use_captcha) && (!isset($_SESSION['customer_id']) || MODULE_CAPTCHA_LOGGED_IN == 'True')) {
        if (!isset($_SESSION['vvcode'])
            || !isset($_POST['vvcode'])
            || $_SESSION['vvcode'] == ''
            || $_POST['vvcode'] == ''
            || strtoupper($_POST['vvcode']) != $_SESSION['vvcode']
            )
        {
            $messageStack->add('price_request', ERROR_VVCODE);
            $error = true;
        }
        unset($_SESSION['vvcode']);
    }
    
    if ($messageStack->size('price_request') > 0) {
        $messageStack->add('price_request', ERROR_MAIL);
        $popup_smarty->assign('error_message', $messageStack->output('price_request'));
    }
    
    //Wenn kein Fehler Email formatieren und absenden
    if ($error === false) {
        // Datum und Uhrzeit
        $datum = date("d.m.Y");
        $uhrzeit = date("H:i");
        
        //Produktname und Link erstellen
        $products_name_query = xtc_db_query("SELECT products_name FROM ".TABLE_PRODUCTS_DESCRIPTION." WHERE products_id = '".(int)$_GET['products_id']."' and language_id = '2'");
        $products_name = xtc_db_fetch_array($products_name_query);
        $products_link = xtc_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$_GET['products_id'], 'SSL', false);
        
        $additional_fields = '';
        if (isset($company))   $additional_fields =  EMAIL_COMPANY. $company . "\n" ;
        if (isset($phone))    $additional_fields .= EMAIL_PHONE . $phone . "\n" ;
        if (isset($price) && !empty(trim($price)))      $additional_fields .= 'Wunschpreis' . $price . "\n" ;
        
        $gender = ($gender == 'm') ? 'Herr' : 'Frau';
        
        if (file_exists(DIR_FS_DOCUMENT_ROOT.'templates/'.CURRENT_TEMPLATE.'/mail/german/sonderpreis.html')
            && file_exists(DIR_FS_DOCUMENT_ROOT.'templates/'.CURRENT_TEMPLATE.'/mail/german/sonderpreis.txt')
            )
        {
            $popup_smarty->assign('language', $_SESSION['language']);
            $popup_smarty->assign('tpl_path', HTTP_SERVER.DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/');
            $popup_smarty->assign('logo_path', HTTP_SERVER.DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/img/');
            $popup_smarty->assign('PRODUCT', $products_name['products_name']);
            $popup_smarty->assign('PRODUCTSLINK', $products_link);
            $popup_smarty->assign('GENDER', $gender);
            $popup_smarty->assign('FIRSTNAME', $firstname);
            $popup_smarty->assign('LASTNAME', $lastname);
            $popup_smarty->assign('EMAIL', $email);
            $popup_smarty->assign('DATE', $datum);
            $popup_smarty->assign('TIME', $uhrzeit);
            $popup_smarty->assign('ADDITIONAL_FIELDS', nl2br($additional_fields));
            $popup_smarty->assign('MESSAGE', nl2br($message_body));
            
            // dont allow cache
            $popup_smarty->caching = false;
            
            $html_mail = $popup_smarty->fetch(CURRENT_TEMPLATE.'/mail/german/sonderpreis.html');
            $txt_mail = $popup_smarty->fetch(CURRENT_TEMPLATE.'/mail/german/sonderpreis.html');
            $txt_mail = str_replace(array('<br />', '<br/>', '<br>'), '', $txt_mail);
        } else {
            $txt_mail = sprintf(EMAIL_SENT_BY, parse_multi_language_value(CONTACT_US_NAME, $_SESSION['language_code']), parse_multi_language_value(CONTACT_US_EMAIL_ADDRESS, $_SESSION['language_code']), $datum , $uhrzeit) . "\n" .
                "--------------------------------------------------------------" . "\n" .
                'Es ist eine neue Sonderpreisanfrage eingegangen, Nachfolgend die Details der Anfrage:'."\n" .
                'Artikel: '.$products_name['products_name']."\n" .
                'Link zum Artikel: '.$products_link."\n" .
                'Anrede: '.$gender."\n" .
                'Vorname: '.$firstname."\n" .
                'Nachname: '.$lastname."\n" .
                EMAIL_EMAIL. trim($email) . "\n" .
                $additional_fields .
                "\n".EMAIL_MESSAGE."\n ". $message_body . "\n";
                $html_mail = nl2br($txt_mail);
        }

        xtc_php_mail(CONTACT_US_EMAIL_ADDRESS,
            CONTACT_US_NAME,
            CONTACT_US_EMAIL_ADDRESS,
            CONTACT_US_NAME,
            CONTACT_US_FORWARDING_STRING,
            trim($email),
            $name,
            '',
            '',
            CONTACT_US_EMAIL_SUBJECT,
            $html_mail,
            $txt_mail
            );
        
        xtc_redirect(xtc_href_link('popup_sonderpreis.php', 'action=success&products_id='.(int) $_GET['products_id']));
    }
}

if (isset ($_GET['action']) && ($_GET['action'] == 'success')) {
    $popup_smarty->assign('success', '1');  
} 
else if (isset ($_GET['action']) && ($_GET['action'] == 'no_success')) {
    $popup_smarty->assign('success', '-1');
} 
else {
    if(!empty($_GET['products_id'])) {
        $products_query = xtc_db_query("SELECT p.products_id, p.products_status, pf.products_specialrequest FROM ".TABLE_PRODUCTS." p JOIN ".TABLE_PRODUCTS_FIELDS." pf ON p.products_id = pf.products_id WHERE p.products_id = '".(int)$_GET['products_id']."'");
        $product = xtc_db_fetch_array($products_query);
        
        if(empty($product) || $product['products_status'] != 1 || $product['products_specialrequest'] != 1) {
            xtc_redirect(xtc_href_link('popup_sonderpreis.php', 'action=no_success&products_id='.(int) $_GET['products_id']));
        }
    }
    else {
        xtc_redirect(xtc_href_link('popup_sonderpreis.php', 'action=no_success'));
    }
    
    
    if (isset ($_SESSION['customer_id']) && $action == '') {
        $c_query = xtc_db_query("SELECT c.customers_email_address,
                                      c.customers_gender,
                                      c.customers_telephone,
                                      c.customers_fax,
                                      ab.entry_company,
                                      ab.entry_street_address,
                                      ab.entry_city,
                                      ab.entry_postcode
                                 FROM ".TABLE_CUSTOMERS." c
                                 JOIN ".TABLE_ADDRESS_BOOK." ab
                                      ON ab.customers_id = c.customers_id
                                         AND ab.address_book_id = c.customers_default_address_id
                                WHERE c.customers_id = '".(int)$_SESSION['customer_id']."'");
        $c_data  = xtc_db_fetch_array($c_query);
        $c_data = array_map('stripslashes', $c_data);
        
        $gender = $c_data['customers_gender'];
        $firstname = $_SESSION['customer_first_name'];
        $lastname = $_SESSION['customer_last_name'];
        $email = $c_data['customers_email_address'];
        $phone = $c_data['customers_telephone'];
        $company = $c_data['entry_company'];
    } elseif ($action == '') {
        $gender = '';
        $firstname = '';
        $lastname = '';
        $email = '';
        $phone = '';
        $company = '';
    }
     
    $popup_smarty->assign('FORM_ACTION', xtc_draw_form('price_request', xtc_href_link('popup_sonderpreis.php', 'action=send&products_id='.(int) $_GET['products_id'], 'SSL')));
    if (in_array('contact', $use_captcha) && (!isset($_SESSION['customer_id']) || MODULE_CAPTCHA_LOGGED_IN == 'True')) {
        $popup_smarty->assign('VVIMG', '<img src="'.xtc_href_link(FILENAME_DISPLAY_VVCODES, '', 'SSL').'" alt="Captcha" />');
        $popup_smarty->assign('INPUT_CODE', xtc_draw_input_field('vvcode', '', 'size="'. MODULE_CAPTCHA_CODE_LENGTH .'" maxlength="'.MODULE_CAPTCHA_CODE_LENGTH.'"', 'text', false));
    }
    
    $popup_smarty->assign('INPUT_GENDER', xtc_draw_pull_down_menuNote(array ('name' => 'gender', 'text' => '&nbsp;'. (xtc_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">'.ENTRY_GENDER_TEXT.'</span>' : '')), get_customers_gender(), $gender));
    $popup_smarty->assign('INPUT_FIRSTNAME', xtc_draw_input_fieldNote(
        array ('name' => 'firstname', 'text' => '&nbsp;'. (xtc_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">'.ENTRY_FIRST_NAME_TEXT.'</span>' : '')), $firstname));
    $popup_smarty->assign('INPUT_LASTNAME', xtc_draw_input_fieldNote(array ('name' => 'lastname', 'text' => '&nbsp;'. (xtc_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">'.ENTRY_LAST_NAME_TEXT.'</span>' : '')), $lastname));
    $popup_smarty->assign('INPUT_COMPANY', xtc_draw_input_fieldNote(array ('name' => 'company', 'class="stern_input'), $company));
    $popup_smarty->assign('INPUT_EMAIL', xtc_draw_input_fieldNote(array ('name' => 'email', 'text' => '&nbsp;'. (xtc_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">'.ENTRY_EMAIL_ADDRESS_TEXT.'</span>' : '')), $email,''));
    $popup_smarty->assign('INPUT_TEL', xtc_draw_input_fieldNote(array ('name' => 'phone', 'class="stern_input'), $phone));
    $popup_smarty->assign('INPUT_PRICE', xtc_draw_input_fieldNote(array ('name' => 'price', 'class="stern_input' ), $price,''));
    $popup_smarty->assign('INPUT_TEXT', xtc_draw_textarea_field('message_body', 'soft', 45, 15, $message_body));
    $popup_smarty->assign('BUTTON_SUBMIT', xtc_image_submit('button_send.gif', IMAGE_BUTTON_SEND));
    $popup_smarty->assign('FORM_END', '</form>');
}

$popup_smarty->display(CURRENT_TEMPLATE.'/module/popup_sonderpreis.html');
?>