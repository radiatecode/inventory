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

if (isset($_GET['ajax']) && $_GET['ajax']=='dsr'){
    if (isset($_GET['id'])){
        $salesReturn = new SalesReturn();
        $deleted = $salesReturn->delete_return($_GET['id']);
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

if (isset($_GET['ajax']) && $_GET['ajax']=='d_product'){
    if (isset($_GET['id'])){
        $product = new Products();
        $deleted = $product->delete_product($_GET['id']);
        if ($deleted){
            echo 'success';
        }else {
            echo 'error';
        }
    }
}
if (isset($_GET['ajax']) && $_GET['ajax']=='d_selected_product'){
    if (isset($_POST['selected_ids'])){
        $selected_ids = $_POST['selected_ids'];
        $product = new Products();
        foreach ($selected_ids as $id) {
            $deleted = $product->delete_product($id);
            if (!$deleted) {
                echo json_encode('error');
            }
        }
        echo json_encode('success');
    }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='d_selected_purchase'){
    if (isset($_POST['selected_ids'])){
        $selected_ids = $_POST['selected_ids'];
        $purchase = new Purchase();
        foreach ($selected_ids as $id) {
            $deleted = $purchase->delete_purchase($id);
            if (!$deleted) {
                echo json_encode('error');
            }
        }
        echo json_encode('success');
    }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='d_selected_purchase_return'){
    if (isset($_POST['selected_ids'])){
        $selected_ids = $_POST['selected_ids'];
        $purchaseReturn = new PurchaseReturn();
        foreach ($selected_ids as $id) {
            $deleted = $purchaseReturn->delete_return($id);
            if (!$deleted) {
                echo json_encode('error');
            }
        }
        echo json_encode('success');
    }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='d_selected_sales_return'){
    if (isset($_POST['selected_ids'])){
        $selected_ids = $_POST['selected_ids'];
        $salesReturn = new SalesReturn();
        foreach ($selected_ids as $id) {
            $deleted = $salesReturn->delete_return($id);
            if (!$deleted) {
                echo json_encode('error');
            }
        }
        echo json_encode('success');
    }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='d_selected_sales'){
    if (isset($_POST['selected_ids'])){
        $selected_ids = $_POST['selected_ids'];
        $order = new Order();
        foreach ($selected_ids as $id) {
            $deleted = $order->delete_order($id);
            if (!$deleted) {
                echo json_encode('error');
            }
        }
        echo json_encode('success');
    }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='enable_disable'){
    if (isset($_POST['selected_ids'])){
        $selected_ids = $_POST['selected_ids'];
        $product = new Products();
        foreach ($selected_ids as $id) {
            $res = $product->enable_disable($id,$_GET['type']);
            if (!$res) {
                echo json_encode('error');
            }
        }
        echo json_encode('success');
    }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='d_brand'){
    $brand = new Brand();
    $deleted = $brand->delete_brand($_GET['id']);
    if (!$deleted) {
        echo 'error';
    }
    echo 'success';
}

if (isset($_GET['ajax']) && $_GET['ajax']=='d_selected_brand'){
    if (isset($_POST['selected_ids'])){
        $selected_ids = $_POST['selected_ids'];
        $brand = new Brand();
        foreach ($selected_ids as $id) {
            $deleted = $brand->delete_brand($id);
            if (!$deleted) {
                echo json_encode('error');
            }
        }
        echo json_encode('success');
    }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='enable_disable_brand'){
    if (isset($_POST['selected_ids'])){
        $selected_ids = $_POST['selected_ids'];
        $brand = new Brand();
        foreach ($selected_ids as $id) {
            $res = $brand->enable_disable($id,$_GET['type']);
            if (!$res) {
                echo json_encode('error');
            }
        }
        echo json_encode('success');
    }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='d_category'){
    $category = new Category();
    $deleted = $category->delete_category($_GET['id']);
    if (!$deleted) {
        echo 'error';
    }
    echo 'success';
}

if (isset($_GET['ajax']) && $_GET['ajax']=='d_selected_category'){
    if (isset($_POST['selected_ids'])){
        $selected_ids = $_POST['selected_ids'];
        $category = new Category();
        foreach ($selected_ids as $id) {
            $deleted = $category->delete_category($id);
            if (!$deleted) {
                echo json_encode('error');
            }
        }
        echo json_encode('success');
    }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='enable_disable_category'){
    if (isset($_POST['selected_ids'])){
        $selected_ids = $_POST['selected_ids'];
        $category = new Category();
        foreach ($selected_ids as $id) {
            $res = $category->enable_disable($id,$_GET['type']);
            if (!$res) {
                echo json_encode('error');
            }
        }
        echo json_encode('success');
    }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='d_supplier'){
    $supplier = new Suppliers();
    $deleted = $supplier->delete_supplier($_GET['id']);
    if (!$deleted) {
        echo 'error';
    }
    echo 'success';
}

if (isset($_GET['ajax']) && $_GET['ajax']=='d_selected_supplier'){
    if (isset($_POST['selected_ids'])){
        $selected_ids = $_POST['selected_ids'];
        $supplier = new Suppliers();
        foreach ($selected_ids as $id) {
            $deleted = $supplier->delete_supplier($id);
            if (!$deleted) {
                echo json_encode('error');
            }
        }
        echo json_encode('success');
    }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='d_attribute'){
    $attribute = new ProductAttributes();
    $deleted = $attribute->delete_attribute($_GET['id']);
    if (!$deleted) {
        echo 'error';
    }
    echo 'success';
}

if (isset($_GET['ajax']) && $_GET['ajax']=='d_selected_attribute'){
    if (isset($_POST['selected_ids'])){
        $selected_ids = $_POST['selected_ids'];
        $attribute = new ProductAttributes();
        foreach ($selected_ids as $id) {
            $deleted = $attribute->delete_attribute($id);
            if (!$deleted) {
                echo json_encode('error');
            }
        }
        echo json_encode('success');
    }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='enable_disable_attribute'){
    if (isset($_POST['selected_ids'])){
        $selected_ids = $_POST['selected_ids'];
        $attribute = new ProductAttributes();
        foreach ($selected_ids as $id) {
            $res = $attribute->enable_disable($id,$_GET['type']);
            if (!$res) {
                echo json_encode('error');
            }
        }
        echo json_encode('success');
    }
}

if (isset($_GET['ajax']) && $_GET['ajax']=='invoice_order'){
    if (isset($_GET['type'])){
        $report = new Report();
        $data = $report->getOrders($_GET['type']);
        echo json_encode($data);
    }
}



