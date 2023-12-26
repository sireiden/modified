<?php
/* Prüfung, ob auch wirklich ein Artikel ausgewählt wurde */
if (isset ($_POST['products_id']) && is_numeric($_POST['products_id'])) {
    // Überprüfung, ob für alle Optionen ein Wert gesetzt ist
    if(isset($_POST['id'])) {
        foreach($_POST['id'] as $post_id) {
            if($post_id == '') {
                $_SESSION['option_error'] = true;
                xtc_redirect(xtc_href_link($goto, xtc_get_all_get_params($parameters) . 'products_id=' . (int)$_POST['products_id'] . $info_message));
                exit;
            }
            else {
                $_SESSION['option_error'] = false;
            }
        }
    }
}