<?php
require '../vendor/autoload.php';
if (isset($_GET['cat_id'])){
    $attribute = new ProductAttributes();
    $data = $attribute->getAttributesByCategory($_GET['cat_id']);

    echo json_encode($data);
}