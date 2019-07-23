<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../vendor/autoload.php';
$product = new Products();
$products = $product->allProducts();
$supplier = new Suppliers();
$suppliers = $supplier->allSuppliers();
$purchase = new Purchase();
if (isset($_GET['id'])) {
    if (isset($_POST['submit'])) {
        $purchase->update($_POST,$_GET['id']);
    }
    $view_purchase = $purchase->viewPurchase($_GET['id']);
    $purchase_items = $purchase->viewPurchaseItems($_GET['id']);
    $total = ($view_purchase['sub_total']-($view_purchase['sub_total']*$view_purchase['discount'])/100);
    $vat =  ($total*$view_purchase['vat']/100);
    $grand_total =  ($total+$vat);
}else{
    abort(404);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../include/_head.php') ?>
    <link rel="stylesheet" href="../assets/js/bootstrap-datepicker/css/bootstrap-datepicker.css">
</head>

<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <!-- Side Bar -->
        <?php include('../include/_side-nav.php') ?>
        <!-- /Side Bar -->

        <!-- top navigation -->
        <?php include('../include/_top-nav.php')  ?>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
                <div class="title_left">
                    <h3>View Purchase Order</h3>
                </div>
                <div class="clearfix"></div>

                <div class="row">
                    <?php
                    session_message();
                    messages($purchase->getMessage())
                    ?>
                    <!-- Page Container -->
                    <div class="col-md-12 col-sm-12 col-xs-12" ng-app="app" ng-controller="ItemsController">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>View Purchase <small>Order</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <form action="purchase-view.php?id=<?= $_GET['id'] ?>" method="post" class="form-horizontal">
                                    <div class="row">
                                         <div class="col-md-4 col-lg-6 col-xs-12">
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Supplier <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                   <select class="form-control" name="supplier">
                                                       <option value="">-- Select Supplier --</option>
                                                       <?php foreach ($suppliers as $row){ ?>
                                                           <option value="<?= $row['id'] ?>" <?= $view_purchase['supplier_id']==$row['id']?'selected':'' ?>><?= $row['name'] ?></option>
                                                       <?php } ?>
                                                   </select>
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                 <label class="control-label" for="last-name">Contact <span class="required">*</span>
                                                 </label>
                                                 <div class="">
                                                     <input type="text" id="contact" name="contact" value="<?= $view_purchase['contact'] ?>" class="form-control col-md-7 col-xs-12">
                                                 </div>
                                             </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Email
                                                </label>
                                                <div class="">
                                                    <input type="email" id="email" name="email" value="<?= $view_purchase['email'] ?>" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Billing Address <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <textarea class="form-control" name="billing_address"><?= $view_purchase['billing_address'] ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="col-md-4 col-lg-6 col-xs-12">
                                             <div class="form-group">
                                                 <label class="control-label" for="last-name">Order Date <span class="required">*</span>
                                                 </label>
                                                 <div class="">
                                                     <input type="text" id="order_date" name="order_date" value="<?= $view_purchase['order_date'] ?>" class="form-control col-md-7 col-xs-12">
                                                 </div>
                                             </div>
                                             <div class="form-group">
                                                 <label class="control-label" for="last-name">Payment Date <span class="required">*</span>
                                                 </label>
                                                 <div class="">
                                                     <input type="text" id="payment_date" name="payment_date" value="<?= $view_purchase['payment_date'] ?>" class="form-control col-md-7 col-xs-12">
                                                 </div>
                                             </div>
                                             <div class="form-group">
                                                 <label class="control-label" for="last-name">Payment Method <span class="required">*</span>
                                                 </label>
                                                 <div class="">
                                                     <select class="form-control" name="payment_method">
                                                         <option value="">-- Select Payment Method --</option>
                                                         <option value="bkash" <?= $view_purchase['payment_method']=='bkash'?'selected':'' ?>>Bkash</option>
                                                         <option value="cash" <?= $view_purchase['payment_method']=='cash'?'selected':'' ?>>Cash</option>
                                                         <option value="bank" <?= $view_purchase['payment_method']=='bank'?'selected':'' ?>>Bank</option>
                                                     </select>
                                                 </div>
                                             </div>
                                        </div>
                                        <div>
                                        <div class="col-md-12 col-lg-12 col-xs-12">
                                            <button type="button" ng-click="addItem()" class="btn btn-info btn-xs add_product"><i class="fa fa-plus-circle"></i></button>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                       <tr>
                                                           <th>Product</th>
                                                           <th>Unit Price</th>
                                                           <th>Quantity</th>
                                                           <th>Total</th>
                                                           <th></th>
                                                       </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- loop from php json -->
                                                        <tr ng-repeat="item_row in purchase_items">
                                                            <td>
                                                                <input type="hidden" name="purchase_items_id[]" value="{{ item_row.id }}" ng-init="item_row.id">
                                                                <select class="form-control select_product" name="edit_product[]" ng-model="item_row.product_id" required>
                                                                    <option value="">-- Select Product --</option>
                                                                    <?php foreach ($products as $row){ ?>
                                                                        <option value="<?= $row['id'] ?>"><?= $row['product_name'] ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="edit_unit_price[]"   class="form-control col-md-7 col-xs-12" ng-model="item_row.unit_price" ng-change="calculate(item_row);getTotal();" required>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="edit_quantity[]" class="form-control col-md-7 col-xs-12" ng-model="item_row.quantity" ng-change="calculate(item_row);getTotal();" required>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="edit_total[]" class="form-control col-md-7 col-xs-12" ng-model="item_row.total"  readonly required>
                                                            </td>
                                                        </tr>
                                                        <!-- /loop from php json -->
                                                       <tr ng-repeat="item in items" ng-model="newItemName" >
                                                           <td>
                                                               <select class="form-control select_product" ng-model="item.product"
                                                                       ng-change="selectChange(item);" name="product[]">
                                                                   <option value="">-- Select Product --</option>
                                                                   <?php foreach ($products as $row){ ?>
                                                                       <option value="<?= $row['id'] ?>"><?= $row['product_name'] ?></option>
                                                                   <?php } ?>
                                                               </select>
                                                           </td>
                                                           <td>
                                                               <input type="text" name="unit_price[]" class="form-control col-md-7 col-xs-12" ng-model="item.unit_price" ng-change="calculate(item);getTotal();">
                                                           </td>
                                                           <td>
                                                               <input type="text" name="quantity[]" class="form-control col-md-7 col-xs-12" ng-model="item.quantity" ng-change="calculate(item);getTotal();">
                                                           </td>
                                                           <td>
                                                               <input type="text" name="total[]" class="form-control col-md-7 col-xs-12" ng-model="item.total" readonly>
                                                           </td>
                                                           <td>
                                                               <a ng-click="deleteItem($index)" class="btn btn-danger btn-xs" title="Remove This Row">
                                                                   <i class="glyphicon glyphicon-remove-circle"></i></a></td>
                                                           </td>
                                                       </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="col-md-8 col-lg-8 col-xs-12">
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Note
                                                </label>
                                                <div class="">
                                                    <textarea class="form-control" name="note"><?= $view_purchase['note'] ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-xs-12">
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Sub Total <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="text" id="sub_total" name="sub_total" ng-model="sub_total"
                                                           class="form-control col-md-7 col-xs-12" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Discount <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="text" id="discount" name="discount" ng-model="discount" ng-change="getTotal();" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Total Amount <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="text" id="total_amount" name="total_amount" ng-model="total_amount" class="form-control col-md-7 col-xs-12" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">VAT (%) <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="text" id="vat" name="vat" class="form-control col-md-7 col-xs-12" ng-model="vat" ng-change="getTotal();">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">VAT <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="text" id="vat_amount" ng-model="vat_amount" name="vat_amount" class="form-control col-md-7 col-xs-12" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Grand Total <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="text" id="grand_total" name="grand_total" ng-model="grand_total" class="form-control col-md-7 col-xs-12" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Paid Amount <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="text" id="paid" name="paid"  ng-model="paid" ng-change="getTotal();" class="form-control col-md-7 col-xs-12" >
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Due Amount <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="text" id="due" name="due" ng-model="due" class="form-control col-md-7 col-xs-12" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-lg-12 col-xs-12">
                                        <div>
                                            <button type="submit" name="submit" id="submit" class="btn btn-success btn-md pull-right"><i class="fa fa-save"></i> Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
            <div class="pull-right">
                Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>
<?php include('../include/_script.php') ?>
<script src="../assets/js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.5/angular.js"></script>
<script>
    $('#order_date').datepicker({
        format: 'yyyy-mm-dd',
        startDate: "now()",
        todayHighlight: true,
        autoclose: true
    });
    $('#payment_date').datepicker({
        format: 'yyyy-mm-dd',
        startDate: "now()",
        todayHighlight: true,
        autoclose: true
    });
    var products = <?php echo json_encode($products) ?>;
    function searchObjects(data,id) {
       var searchField = "id";
       for (var i=0 ; i < data.length ; i++)
       {
           if (data[i][searchField] === id) {
               return data[i];
           }
       }
    }
    var app = angular.module("app",[]);
    app.controller("ItemsController",function($scope,$http) {
        view_purchase($scope);

        $scope.items = [{newItemName:''}];
        $scope.addItem = function (index) {
            $scope.items.push({newItemName:''});
        };

        $scope.deleteItem = function (index) {
            if(!index){
                alert("\tDelete Error. \n Root Row not deletable.");
                $scope.items.push({newItemName:''});
            }
            $scope.items.splice(index, 1);
            this.getTotal();
        };

        $scope.selectChange = function (i) {
            var product = i.product;
            var obj = searchObjects(products,product);
            i.unit_price = obj.sale_price;
        };

        $scope.calculate = function(i){
            var unite_price = parseFloat(i.unit_price);
            var quantity = parseFloat(i.quantity);
            var total_price = unite_price*quantity;
            i.total = total_price;

            var total = 0;
            var total_items_amount = 0;

            for(var i = 0; i < $scope.items.length; i++){
                var product         = $scope.items[i];
                total           += parseFloat(product.total);
            }
            for(var i = 0; i < $scope.purchase_items.length; i++){
                var purchase_items         = $scope.purchase_items[i];
                var qty = parseFloat(purchase_items.quantity);
                var unit_price = parseFloat(purchase_items.unit_price);
                total_items_amount           += unit_price*qty;
            }
            if (total) {
                $scope.sub_total = total_items_amount+total;
            }else{
                $scope.sub_total = total_items_amount;
            }

        };

        $scope.getTotal = function(){
            var sub_total = parseFloat($scope.sub_total);
            var discount = parseFloat($scope.discount);

            var total_amount = sub_total-((sub_total*discount)/100);
            $scope.total_amount = total_amount;

            var vat = parseFloat($scope.vat);
            var vat_amount = (total_amount*vat)/100;
            $scope.vat_amount = vat_amount;
            $scope.grand_total = total_amount+vat_amount;

            var paid = parseFloat($scope.paid);
            var grand_total = parseFloat($scope.grand_total);
            $scope.due = grand_total-paid;
        }
    });
    function view_purchase($scope) {
        $scope.purchase_items = JSON.parse('<?= json_encode($purchase_items) ?>');
        console.log($scope.purchase_items);
        $scope.sub_total = '<?= $view_purchase['sub_total'] ?>';
        $scope.discount = '<?= $view_purchase['discount'] ?>';
        $scope.total_amount = '<?= $total ?>';
        $scope.vat = '<?= $view_purchase['vat'] ?>';
        $scope.vat_amount = '<?= $vat ?>';
        $scope.grand_total = '<?= $grand_total ?>';
        $scope.paid = '<?= $view_purchase['paid_amount'] ?>';
        $scope.due = '<?= $view_purchase['due_amount'] ?>';
    }
</script>
</body>
</html>
