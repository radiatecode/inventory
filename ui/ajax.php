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
            echo 'success';
        }else {
            echo 'error';
        }
    }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='dps'){
    if (isset($_GET['id'])){
        $product = new Products();
        $deleted = $product->delete_stock($_GET['id']);
        if ($deleted){
            echo 'success';
        }else {
            echo 'error';
        }
    }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='dc'){
    if (isset($_GET['id'])){
        $customer = new Customers();
        $deleted = $customer->delete_customer($_GET['id']);
        if ($deleted){
            echo 'success';
        }else {
            echo 'error';
        }
    }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='do'){
    if (isset($_GET['id'])){
        $order = new Order();
        $deleted = $order->delete_order($_GET['id']);
        if ($deleted){
            echo 'success';
        }else {
            echo 'error';
        }
    }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='dp'){
    if (isset($_GET['id'])){
        $purchase = new Purchase();
        $deleted = $purchase->delete_purchase($_GET['id']);
        if ($deleted){
            echo 'success';
        }else {
            echo 'error';
        }
    }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='dpr'){
    if (isset($_GET['id'])){
        $purchaseReturn = new PurchaseReturn();
        $deleted = $purchaseReturn->delete_return($_GET['id']);
        if ($deleted){
            echo 'success';
        }else {
            echo 'error';
        }
    }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='order'){
    if (isset($_GET['id'])){
        $purchase = new Purchase();
        $order = $purchase->getPurchaseOrder($_GET['id']);
        if ($order){
            echo json_encode($order);
        }else {
            echo json_encode('error');
        }
    }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='order_info'){
    if (isset($_GET['id'])){
        $purchase = new Purchase();
        $order = $purchase->viewPurchase($_GET['id']);
        $items = $purchase->viewPurchaseItems($_GET['id']);
        if ($order){
            echo json_encode(['order'=>$order,'items'=>$items]);
        }else {
            echo json_encode('error');
        }
    }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='sales_order'){
    if (isset($_GET['id'])){
        $order = new Order();
        $orders = $order->viewOrder($_GET['id']);
        $order_items = $order->viewOrderItems($_GET['id']);
        if ($orders){
            echo json_encode(['order'=>$orders,'items'=>$order_items]);
        }else {
            echo json_encode('error');
        }
    }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='add_product_quantity'){
    if (isset($_GET['id'])){
        $product = new Products();
        $product->update_data($_POST,$_GET['id']);
        echo 'success';
    }
}