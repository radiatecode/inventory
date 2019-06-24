<?php
require '../vendor/autoload.php';
if (isset($_GET['cat_id'])){
    $attribute = new ProductAttributes();
    $data = $attribute->getAttributesByCategory($_GET['cat_id']);

    echo json_encode($data);
}

if (isset($_GET['ajax']) && $_GET['ajax']=='purchase'){
   if (isset($_GET['id']) && isset($_GET['qty'])){
       $product = new Products();
       $data = $product->purchase($_GET['id'],$_GET['qty']);
       if ($data!="success"){
           echo json_encode($data);
       }else {
           echo json_encode($data);
       }
   }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='dpa'){
    if (isset($_GET['id'])){
        $productAttr = new ProductAttributes();
        $deleted = $productAttr->delete_attributes($_GET['id']);
        if ($deleted){
            echo json_encode('success');
        }else {
            echo json_encode('error');
        }
    }
}