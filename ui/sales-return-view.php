<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../vendor/autoload.php';
$product = new Products();
$products = $product->enableProducts();
$salesReturn = new SalesReturn();
if (isset($_GET['id'])) {
    if (isset($_POST['submit'])) {
        $salesReturn->update($_POST,$_GET['id']);
    }
    $viewReturn = $salesReturn->viewSalesReturn($_GET['id']);
    $viewReturnItems = $salesReturn->viewSalesReturnItems($_GET['id']);
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
                    <h3>Sales Order Return</h3>
                </div>
                <div class="clearfix"></div>

                <div class="row">
                    <?php
                    session_message();
                    messages($salesReturn->getMessage())
                    ?>
                    <!-- Page Container -->
                    <div class="col-md-12 col-sm-12 col-xs-12" ng-app="app" ng-controller="ItemsController">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Sales Order <small>Return</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <form action="sales-return-view.php?id=<?= $_GET['id'] ?>" method="post" class="form-horizontal">
                                    <div class="row">
                                         <div class="col-md-4 col-lg-6 col-xs-12">
                                             <div class="form-group">
                                                 <label class="control-label" >Customer :</label>
                                                 <label class="control-label" ><?= $viewReturn['name'] ?></label>
                                             </div>
                                             <div class="form-group">
                                                 <label class="control-label" >Email :
                                                 </label>
                                                 <label class="control-label" >{{ order.email }}</label>
                                             </div>
                                             <div class="form-group">
                                                 <label class="control-label" >Billing Address :</label>
                                                 <label class="control-label" >{{ order.billing_address }}</label>
                                             </div>
                                             <div class="form-group">
                                                 <label class="control-label" >Order Date :</label>
                                                 <label class="control-label" >{{ order.order_date }}</label>
                                             </div>
                                             <div class="form-group">
                                                 <label class="control-label" >Delivery Date :</label>
                                                 <label class="control-label" >{{ order.delivery_date }}</label>
                                             </div>
                                             <div class="form-group">
                                                 <label class="control-label" >Discount Given :</label>
                                                 <label class="control-label" >{{ order.discount_given }}</label>
                                                 <input type="hidden" ng-model="discount_given" ng-value="order.discount">
                                             </div>
                                        </div>
                                         <div class="col-md-4 col-lg-6 col-xs-12">
                                             <div class="form-group">
                                                 <label class="control-label" for="last-name">Return Date <span class="required">*</span>
                                                 </label>
                                                 <div class="">
                                                     <input type="text" id="return_date" name="return_date" ng-model="order.return_date" class="form-control col-md-7 col-xs-12">
                                                 </div>
                                             </div>
                                             <div class="form-group">
                                                 <label class="control-label" for="last-name">Payment Method <span class="required">*</span>
                                                 </label>
                                                 <div class="">
                                                     <select class="form-control" name="payment_method" ng-model="order.payment_method">
                                                         <option value="">-- Select Payment Method --</option>
                                                         <option value="bkash">Bkash</option>
                                                         <option value="cash">Cash</option>
                                                         <option value="bank">Bank</option>
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
                                                           <th>Sales Qty</th>
                                                           <th>Unit Price</th>
                                                           <th>Return Quantity</th>
                                                           <th>Total</th>
                                                       </tr>
                                                    </thead>
                                                    <tbody>
                                                       <tr ng-repeat="item in items">
                                                           <td>
                                                               <input type="hidden" name="sales_return_items[]" value="{{item.id}}">
                                                               <select class="form-control select_product" name="product[]" ng-model="item.product_id">
                                                                   <?php foreach ($products as $row){ ?>
                                                                       <option value="<?= $row['id'] ?>"><?= $row['product_name'] ?></option>
                                                                   <?php } ?>
                                                               </select>
                                                           </td>
                                                           <td>
                                                               <input type="text" class="form-control col-md-7 col-xs-12" ng-model="item.sales_qty" readonly>
                                                           </td>
                                                           <td>
                                                               <input type="text" name="unit_price[]" class="form-control col-md-7 col-xs-12" ng-model="item.unit_price" >
                                                           </td>
                                                           <td>
                                                               <input type="text" name="quantity[]" class="form-control col-md-7 col-xs-12" ng-model="item.quantity" ng-change="return_validate(item);calculate(item);getTotal();">
                                                           </td>
                                                           <td>
                                                               <input type="text" name="total[]" class="form-control col-md-7 col-xs-12" ng-model="item.total" readonly>
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
                                                    <input type="text" id="sub_total" name="sub_total" ng-model="sub_total"
                                                           class="form-control col-md-7 col-xs-12" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Discount Given (%)
                                                </label>
                                                <div class="">
                                                    <input type="text" id="discount" name="discount" ng-model="discount" class="form-control col-md-7 col-xs-12" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Total Amount
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
                                                <label class="control-label" for="last-name">Cash Return <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="text" id="cash_return" name="cash_return"  ng-model="cash_return" ng-change="getTotal();" class="form-control col-md-7 col-xs-12" >
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Adjust Amount <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="text" id="adjust_amount" name="adjust_amount" ng-model="adjust_amount" class="form-control col-md-7 col-xs-12" readonly>
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
    var order_items = <?php echo json_encode($viewReturnItems) ?>;
    var order_info = <?php echo json_encode($viewReturn) ?>;

    var app = angular.module("app",[]);
    app.controller("ItemsController",function($scope,$http) {

        $scope.order = order_info;
        $scope.items = order_items;
        view_payment($scope);

        $scope.return_validate = function (i) {
            var sales_qty = parseFloat(i.sales_qty);
            var qty = parseFloat(i.quantity);
            if (qty <= sales_qty) {
            }else {
                i.quantity = '';
            }
        };

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

            var cash_return = parseFloat($scope.cash_return);
            var grand_total = parseFloat($scope.grand_total);
            $scope.adjust_amount = grand_total-cash_return;
        }
    });

    function view_payment($scope) {
        $scope.sub_total = '<?= $viewReturn['sub_total'] ?>';
        $scope.discount = '<?= $viewReturn['discount_given'] ?>';
        $scope.total_amount = '<?= $total ?>';
        $scope.vat = '<?= $viewReturn['vat'] ?>';
        $scope.vat_amount = '<?= $vat ?>';
        $scope.grand_total = '<?= $grand_total ?>';
        $scope.cash_return = '<?= $viewReturn['cash_return'] ?>';
        $scope.adjust_amount = '<?= $viewReturn['adjust_amount'] ?>';
    }
    $("#submit").click(function (event) {
        if( !confirm('Are you sure that you want to submit the form') ){
            event.preventDefault();
        }
    });
</script>
</body>
</html>
