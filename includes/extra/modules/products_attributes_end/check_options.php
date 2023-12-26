<?php
if($_SESSION['option_error']) {
    $module_smarty->assign('PRODUCTS_OPTION_ERROR', true);
    unset($_SESSION['option_error']);
}
?>