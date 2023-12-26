<?php
$dblink = new PDO("mysql:host=localhost;", 'root', 'REMOVED4SECURITY');
$dblink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$categories_stmt = $dblink->prepare('SELECT * FROM tmp_categories');
$categories_stmt->execute(array());
while($category=$categories_stmt->fetch()) {
    copy('/var/lib/psa/dumps/domains/ascasa.de/httpdocs/images/categories/'.$category['categories_image'], '/var/lib/psa/dumps/domains/ascasa.de/tmp_images/categories/'.$category['categories_image']);
}

$products_stmt = $dblink->prepare('SELECT * FROM tmp_products');
$products_stmt->execute();
while($product=$products_stmt->fetch()) {
    copy('/var/lib/psa/dumps/domains/ascasa.de/httpdocs/images/product_images/info_images/'.$product['products_image'], '/var/lib/psa/dumps/domains/ascasa.de/tmp_images/product_images/info_images/'.$product['products_image']);
    copy('/var/lib/psa/dumps/domains/ascasa.de/httpdocs/images/product_images/original_images/'.$product['products_image'], '/var/lib/psa/dumps/domains/ascasa.de/tmp_images/product_images/original_images/'.$product['products_image']);
    copy('/var/lib/psa/dumps/domains/ascasa.de/httpdocs/images/product_images/popup_images/'.$product['products_image'], '/var/lib/psa/dumps/domains/ascasa.de/tmp_images/product_images/popup_images/'.$product['products_image']);
    copy('/var/lib/psa/dumps/domains/ascasa.de/httpdocs/images/product_images/thumbnail_images/'.$product['products_image'], '/var/lib/psa/dumps/domains/ascasa.de/tmp_images/product_images/thumbnail_images/'.$product['products_image']);
}

$product_images_stmt = $dblink->prepare('SELECT * FROM tmp_products_images');
$product_images_stmt->execute();
while($image = $product_images_stmt->fetch()) {
    copy('/var/lib/psa/dumps/domains/ascasa.de/httpdocs/images/product_images/info_images/'.$image['image_name'], '/var/lib/psa/dumps/domains/ascasa.de/tmp_images/product_images/info_images/'.$product['image_name']);
    copy('/var/lib/psa/dumps/domains/ascasa.de/httpdocs/images/product_images/original_images/'.$image['image_name'], '/var/lib/psa/dumps/domains/ascasa.de/tmp_images/product_images/original_images/'.$product['image_name']);
    copy('/var/lib/psa/dumps/domains/ascasa.de/httpdocs/images/product_images/popup_images/'.$image['image_name'], '/var/lib/psa/dumps/domains/ascasa.de/tmp_images/product_images/popup_images/'.$product['image_name']);
    copy('/var/lib/psa/dumps/domains/ascasa.de/httpdocs/images/product_images/thumbnail_images/'.$image['image_name'], '/var/lib/psa/dumps/domains/ascasa.de/tmp_images/product_images/thumbnail_images/'.$product['image_name']);
}