<?php 

function export_orders($where_clause) {
    $query= "SELECT * FROM ".TABLE_ORDERS." ".$where_clause." ORDER BY orders_id ASC";
    $result = xtc_db_query($query);
    return $result;
}

function export_orders_billing($where_clause, $invoice_where) {
    $query= "SELECT o.orders_id, customers_id, customers_status, customers_name, customers_email_address, customers_vat_id, billing_firstname, billing_lastname, billing_company, billing_country, delivery_country, payment_class, invoice_date, kvnummer, os.orders_status_name FROM ".TABLE_ORDERS." o JOIN ".TABLE_ORDERS_STATUS." os ON (os.orders_status_id = o.orders_status AND os.language_id = 2) WHERE ".$invoice_where.$where_clause." ";
    $result = xtc_db_query($query);
    return $result;
}

function export_ordered_products($orders_id) {
    $query= "SELECT * FROM ".TABLE_ORDERS_PRODUCTS." WHERE orders_id = '".(int)$orders_id."'";
    $result = xtc_db_query($query);
    return $result;
}

function get_order_date($order_date) {
    $date=substr($order_date,0,10);
    $date_array=explode("-",$date);
    $date_right=$date_array[2].'.'.$date_array[1].'.'.$date_array[0];
    return $date_right;
}

function get_order_billing_lastname($customers_id) {
    $query= "SELECT customers_lastname FROM ".TABLE_CUSTOMERS." WHERE customers_id = '".(int)$customers_id."'";
    $result = xtc_db_query($query);
    $data = xtc_db_fetch_array($result);
    return $data['customers_lastname'];
}

function get_order_billing_firstname($customers_id) {
    $query= "SELECT customers_firstname FROM ".TABLE_CUSTOMERS." WHERE customers_id = '".(int)$customers_id."'";
    $result = xtc_db_query($query);
    $data = xtc_db_fetch_array($result);
    return $data['customers_firstname'];
}

function get_tr_costs($order_id) {
    $query= "SELECT value FROM ".TABLE_ORDERS_TOTAL." WHERE orders_id = '".(int)$order_id."' AND class = 'ot_shipping'";
    $result = xtc_db_query($query);
    $data = xtc_db_fetch_array($result);
    return $data['value'];
}

function get_export_costs($order_id) {
    $query= "SELECT value FROM ".TABLE_ORDERS_TOTAL." WHERE orders_id = '".(int)$order_id."' AND class = 'ot_export_fee'";
    $result = xtc_db_query($query);
    $data = xtc_db_fetch_array($result);
    return $data['value'];
}

function get_cash_discount($order_id) {
    $query= "SELECT value FROM ".TABLE_ORDERS_TOTAL." WHERE orders_id = '".(int)$order_id."' AND class = 'ot_cash_discount'";
    $result = xtc_db_query($query);
    $data = xtc_db_fetch_array($result);
    return $data['value'];
}

function get_gutscheine($order_id) {
    $query= "SELECT value FROM ".TABLE_ORDERS_TOTAL." WHERE orders_id = '".(int)$order_id."' AND class = 'ot_gv'";
    $result = xtc_db_query($query);
    $data = xtc_db_fetch_array($result);
    return $data['value'];
}

function get_order_value($order_id) {
    $query= "SELECT value FROM ".TABLE_ORDERS_TOTAL." WHERE orders_id = '".(int)$order_id."' AND class = 'ot_total'";
    $result = xtc_db_query($query);
    $data = xtc_db_fetch_array($result);
    return $data['value'];
}

function get_order_tax($order_id) {
    $query= "SELECT value FROM ".TABLE_ORDERS_TOTAL." WHERE orders_id = '".(int)$order_id."' AND class = 'ot_tax'";
    $result = xtc_db_query($query);
    $data = xtc_db_fetch_array($result);
    return $data['value'];
}

function get_tax_value($order_id) {
    $query ="SELECT products_tax FROM ".TABLE_ORDERS_PRODUCTS." WHERE orders_id = '".(int)$order_id."' LIMIT 1";
    $result = xtc_db_query($query);
    $data = xtc_db_fetch_array($result);
    $tax = number_format(($data['products_tax']),0,',','.');
    $tax .= ' %';
    return $tax;
} 

function get_directbuy_id($order_id) {
    $query= "SELECT comments FROM orders_status_history WHERE comments LIKE 'Idealo Direktkauf Bestellung%' and orders_id = '$order_id'";
    $result = xtc_db_query($query);
    $data = xtc_db_fetch_array($result);
    if(!empty($data)) {
        $idealo_order_id = str_replace('Idealo Direktkauf Bestellung ', '', $data['comments']);
        $idealo_order_id = substr($idealo_order_id, 0, stripos($idealo_order_id, "\n"));
        return $idealo_order_id;
    }
    else {
        return '';
    }
}


function get_order_fee($order_id) {
    $query= "SELECT value FROM ".TABLE_ORDERS_TOTAL." WHERE orders_id = '".(int)$order_id."' AND (class = 'ot_cod_fee' OR class = 'ot_giropay_fee' OR class = 'ot_paypal_fee' OR class = 'ot_rechnung_fee'
	OR class = 'ot_sofortueberweisung_fee' OR class = 'ot_kreditkarte_fee' OR class = 'ot_cash_discount')";
    $result = xtc_db_query($query);
    $data = xtc_db_fetch_array($result);
    return $data['value'];
}

function get_order_status($order_status) {
    $query= "SELECT orders_status_name FROM ".TABLE_ORDERS_STATUS." WHERE orders_status_id = '".(int)$order_status."' AND language_id = '2'";
    $result = xtc_db_query($query);
    $data = xtc_db_fetch_array($result);
    return $data['orders_status_name'];
}

function get_product_details($products_id) {
    $query= "SELECT products_ean, products_manufacturers_model FROM ".TABLE_PRODUCTS." WHERE products_id = '".(int)$products_id."'";
    $result = xtc_db_query($query);
    $data = xtc_db_fetch_array($result);
    return $data;
}


function get_ot_costs($order_id, $payment_name) {
    $payment_class = '';
    switch ($payment_name) {
        case 'cod':    
            $payment_class = 'ot_cod_fee';
            break;
        case 'hpsu':
            $payment_class = 'ot_sofortueberweisung_fee';
            break;
        case 'hpcc':
            $payment_class = 'ot_kreditkarte_fee';
            break;
        case 'hpgp':
            $payment_class = 'ot_giropay_fee';
            break;
        case 'paypal':
            $payment_class = 'ot_paypal_fee';
            break;
        case 'paypalplus':
            $payment_class = 'ot_paypalplus_fee';
            break;
        case 'ratepay_rechnung':
            $payment_class = 'ot_rechnung_fee';
            break;
        case 'ratepay_sepa':
            $payment_class = 'ot_lastschrift_fee';
            break;
        case 'am_apa':
            $payment_class = 'ot_amazon_fee';
            break;
    }
    
    if(!empty($payment_class)) {
        $query= "SELECT value FROM ".TABLE_ORDERS_TOTAL." WHERE orders_id = '".(int)$order_id."' AND class = '".$payment_class."'";
        $result = xtc_db_query($query);
        $data = xtc_db_fetch_array($result);
        return $data['value'];
    }
    else {
        return '0';
    }
}



function get_order_manufacturer($products_id) {
    $query= "SELECT m.manufacturers_name FROM ".TABLE_PRODUCTS." p JOIN ".TABLE_MANUFACTURERS." m ON (m.manufacturers_id = p.manufacturers_id)  WHERE p.products_id = '".(int)$products_id."'";
    $result = xtc_db_query($query);
    $data = xtc_db_fetch_array($result);
    return $data['manufacturers_name'];
} 

function get_order_products_attribute($order_products_id) {
    $attributes= '';
    $query= "SELECT products_options_values FROM ".TABLE_ORDERS_PRODUCTS_ATTRIBUTES." WHERE orders_products_id = '".(int)$order_products_id."'";
    $result = xtc_db_query($query);
    while($data = xtc_db_fetch_array($result)) {
        $attributes .= $data['products_options_values'].' ';
    }
    return $attributes;
}	


function get_order_payment_type($payment_name) {
    switch($payment_name) {
        case 'eustandardtransfer':
        case 'moneyorder':
            return "Vorkasse";
            break;
            
        case 'ratepay_rechnung':
            return "RP-Rechnung";
            break;
            
        case 'ratepay_sepa':
            return "RP-Lastschrift";
            break;
            
        case 'paypal':
            return "PayPal";
            break;
            
        case 'paypalplus':
            return "PayPal Plus";
            break;
            
        case 'hpsu':
            return "Sofortüberweisung";
            break;
            
        case 'cash':
            return "Barzahlung";
            break;
            
        case 'cod':
            return "Nachnahme";
            break;
            
        case 'invoice':
            return "Rechnung";
            break;
            
        case 'hpcc':
            return "Kreditkarte";
            break;
            
        case 'hpgp':
            return "Heidelpay  Giropay";
            break;
            
        case 'hpdd':
            return "Lastschrift";
            break;
            
        case 'hpddsec':
            return "Lastschrift";
            break;
            
        case 'hpiv':
            return "Rechnung";
            break;
            
        case 'hpivsec':
            return "Rechnung";
            break;
            
        case 'karintern':
            return "KaR intern";
            break;
            
        case 'sepaintern':
            return "SEPA Lastschrift";
            break;
            
        case 'commerzfinanz':
            return "Finanzierung";
            break;
            
        case 'am_apa':
            return "Amazon Payments";
            break;
            
        case 'ebay':
            return "Ebay";
            break;
    }
}  




function set_csv_exported($orders_id) {
    $query= "UPDATE ".TABLE_ORDERS." SET csv_exported = '1' WHERE orders_id = '".(int)$orders_id."'";
    $result = xtc_db_query($query);
}







?>