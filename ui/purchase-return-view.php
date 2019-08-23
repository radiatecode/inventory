<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../vendor/autoload.php';
$product = new Products();
$products = $product->enableProducts();
$supplier = new Suppliers();
$suppliers = $supplier->allSuppliers();
$purchaseReturn = new PurchaseReturn();
if (isset($_GET['id'])) {
    if (isset($_POST['submit'])) {
        $purchaseReturn->update($_POST,$_GET['id']);
    }
    $viewReturn = $purchaseReturn->viewPurchaseReturn($_GET['id']);
    $viewReturnItems = $purchaseReturn->viewPurchaseReturnItems($_GET['id']);
    $total = ($viewReturn['sub_total']-($viewReturn['sub_total']*$viewReturn['discount_given'])/100);
    $vat =  ($total*$viewReturn['vat']/100);
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
                    <h3>View Purchase Return</h3>
                </div>
                <div class="clearfix"></div>

                <div class="row">
                    <?php
                    session_message();
                    messages($purchaseReturn->getMessage())
                    ?>
                    <!-- Page Container -->
                    <div class="col-md-12 col-sm-12 col-xs-12" ng-app="app" ng-controller="ItemsController">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>View Purchase <small>Return</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <form action="purchase-return-view.php?id=<?= $_GET['id'] ?>" method="post" class="form-horizontal">
                                    <div class="row">
                                         <div class="col-md-4 col-lg-6 col-xs-12">
                                             <div class="form-group">
                                                 <label class="control-label" >Supplier :</label>
                                                 <label class="control-label" ><?= $viewReturn['name'] ?></label>
                                             </div>
                                             <div class="form-group">
                                                 <label class="control-label" >Contact :</label>
                                                 <label class="control-label" ><?= $viewReturn['contact'] ?></label>
                                             </div>
                                             <div class="form-group">
                                                 <label class="control-label" >Email :
                                                 </label>
                                                 <label class="control-label" ><?= $viewReturn['email'] ?></label>
                                             </div>
                                             <div class="form-group">
                                                 <label class="control-label" >Billing Address :</label>
                                                 <label class="control-label" ><?= $viewReturn['billing_address'] ?></label>
                                             </div>
                                             <div class="form-group">
                                                 <label class="control-label" >Order Date :</label>
                                                 <label class="control-label" ><?= $viewReturn['order_date'] ?></label>
                                             </div>
                                             <div class="form-group">
                                                 <label class="control-label" >Discount Given :</label>
                                                 <label class="control-label" ><?= $viewReturn['discount_given'] ?></label>
                                             </div>
                                        </div>
                                         <div class="col-md-4 col-lg-6 col-xs-12">
                                             <div class="form-group">
                                                 <label class="control-label" for="last-name">Ref. Order No
                                                 </label>
                                                 <div class="">
                                                     <input type="text" id="ref_order" name="ref_order" value="<?= $viewReturn['purchase_order_no'] ?>" class="form-control col-md-7 col-xs-12" readonly>
                                                 </div>
                                             </div>
                                             <div class="form-group">
                                                 <label class="control-label" for="last-name">Return Date <span class="required">*</span>
                                                 </label>
                                                 <div class="">
                                                     <input type="text" id="return_date" name="return_date" value="<?= $viewReturn['return_date'] ?>" class="form-control col-md-7 col-xs-12" required>
                                                 </div>
                                             </div>
                                             <div class="form-group">
                                                 <label class="control-label" for="last-name">Payment Method <span class="required">*</span>
                                                 </label>
                                                 <div class="">
                                                     <select class="form-control" name="payment_method" required>
                                                         <option value="">-- Select Payment Method --</option>
                                                         <option value="bkash" <?= $viewReturn['payment_method']=='bkash'?'selected':'' ?>>Bkash</option>
                                                         <option value="cash" <?= $viewReturn['payment_method']=='cash'?'selected':'' ?>>Cash</option>
                                                         <option value="bank" <?= $viewReturn['payment_method']=='bank'?'selected':'' ?>>Bank</option>
                                                     </select>
                                                 </div>
                                             </div>
                                        </div>
                                        <div>
                                        <div class="col-md-12 col-lg-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                       <tr>
                                                           <th>Product</th>
                                                           <th>Purchased Qty</th>
                                                           <th>Unit Price</th>
                                                           <th>Returned Quantity</th>
                                                           <th>Total</th>
                                                       </tr>
                                                    </thead>
                                                    <tbody>
                                                       <tr ng-repeat="item in items">
                                                           <td>
                                                               <input type="hidden" name="purchase_return_items[]" value="{{item.id}}">
                                                               <select class="form-control select_product" name="product[]" ng-model="item.product_id">
                                                                   <?php foreach ($products as $row){ ?>
                                                                       <option value="<?= $row['id'] ?>"><?= $row['product_name'] ?></option>
                                                                   <?php } ?>
                                                               </select>
                                                           </td>
                                                           <td>
                                                               <input type="text" class="form-control col-md-7 col-xs-12" ng-model="item.purchase_qty" readonly>
                                                           </td>
                                                           <td>
                                                               <input type="text" name="unit_price[]" class="form-control col-md-7 col-xs-12" ng-model="item.unit_price" required>
                                                           </td>
                                                           <td>
                                                               <input type="text" name="quantity[]" class="form-control col-md-7 col-xs-12" ng-model="item.quantity" ng-change="calculate(item);getTotal();" required>
                                                           </td>
                                                           <td>
                                                               <input type="text" name="total[]" class="form-control col-md-7 col-xs-12" ng-model="item.total" required readonly>
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
                                                    <textarea class="form-control" name="note"><?= $viewReturn['note'] ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-xs-12">
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Sub Total <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="number" id="sub_total" name="sub_total" ng-model="sub_total"
                                                           class="form-control col-md-7 col-xs-12" step=".01" min="0" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Discount Given (%)
                                                </label>
                                                <div class="">
                                                    <input type="number" id="discount" name="discount" ng-model="discount" class="form-control col-md-7 col-xs-12" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Total Amount
                                                </label>
                                                <div class="">
                                                    <input type="number" id="total_amount" name="total_amount" ng-model="total_amount" class="form-control col-md-7 col-xs-12" step=".01" min="0" required readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">VAT (%) <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="number" id="vat" name="vat" class="form-control col-md-7 col-xs-12" ng-model="vat" ng-change="getTotal();" min="0">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">VAT <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="number" id="vat_amount" ng-model="vat_amount" name="vat_amount" class="form-control col-md-7 col-xs-12" step=".01" readonly>
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
                                                <label class="control-label" for="last-name">Receipt Amount <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="number" id="receipt_amount" name="receipt_amount"  ng-model="receipt_amount" ng-change="getTotal();" class="form-control col-md-7 col-xs-12" step=".01" min="0" required >
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Adjust Amount <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="number" id="adjust_amount" name="adjust_amount" ng-model="adjust_amount" class="form-control col-md-7 col-xs-12" step=".01" min="0" readonly>
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
        <?php require_once '../include/_footer.php'?>
        <!-- /footer content -->
    </div>
</div>
<?php include('../include/_script.php') ?>
<script src="../assets/js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.5/angular.js"></script>
<script>
    $('#return_date').datepicker({
        format: 'yyyy-mm-dd',
        startDate: "now()",
        todayHighlight: true,
        autoclose: true
    });
    var viewReturnItems = <?php echo json_encode($viewReturnItems) ?>;
    var app = angular.module("app",[]);
    app.controller("ItemsController",function($scope,$http) {

        $scope.order = {};
        $scope.items = viewReturnItems;
        view_return($scope);

        $scope.calculate = function(i){
            var unite_price = parseFloat(i.unit_price);
            var quantity = parseFloat(i.quantity);
            var total_price = unite_price*quantity;
            i.total = total_price;

            var total_bdt = 0;

            for(var i = 0; i < $scope.items.length; i++){
                var product         = $scope.items[i];
                if (product.total) {
                    total_bdt += parseFloat(product.total);
                }
            }
            $scope.sub_total = total_bdt;
            $scope.receipt_amount = 0;
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

            var receipt_amount = parseFloat($scope.receipt_amount);
            var grand_total = parseFloat($scope.grand_total);
            $scope.adjust_amount = grand_total-receipt_amount;
        }
    });

    function view_return($scope) {
        $scope.sub_total = parseFloat('<?= $viewReturn['sub_total'] ?>');
        $scope.discount = parseInt('<?= $viewReturn['discount_given'] ?>');
        $scope.total_amount = parseFloat('<?= $total ?>');
        $scope.vat = parseInt('<?= $viewReturn['vat'] ?>');
        $scope.vat_amount = parseFloat('<?= $vat ?>');
        $scope.grand_total = parseFloat('<?= $grand_total ?>');
        $scope.receipt_amount = parseFloat('<?= $viewReturn['receipt_amount'] ?>');
        $scope.adjust_amount = parseFloat('<?= $viewReturn['adjust_amount'] ?>');
    }
</script>
</body>
</html>
