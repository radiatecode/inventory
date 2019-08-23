<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../vendor/autoload.php';
$product = new Products();
$products = $product->enableProducts();
$supplier = new Suppliers();
$suppliers = $supplier->allSuppliers();
$purchaseReturn = new PurchaseReturn();
if (isset($_POST['submit'])){
    $purchaseReturn->store($_POST);
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
                    <h3>Purchase Return</h3>
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
                                <h2>Purchase <small>Return</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <form action="purchase-return-add.php" method="post" class="form-horizontal">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-3 col-xs-12">
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Supplier <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <select class="form-control selectpicker" name="supplier" id="supplier">
                                                        <option value="">-- Select Supplier --</option>
                                                        <?php foreach ($suppliers as $row){ ?>
                                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3 col-xs-12">
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Order No <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <select class="form-control selectpicker" name="order_no" id="order_no" ng-model="order_no" ng-change="selectChange()">
                                                        <option value="">-- Select Order No --</option>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3 col-xs-12">
                                            <div class="form-group">
                                                <label class="control-label" for="last-name"><i class="fa fa-search-plus"></i></label>
                                                <div class="">
                                                    <button class="btn btn-success btn-md"><i class="fa fa-search"></i> Find</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                         <div class="col-md-4 col-lg-6 col-xs-12">
                                             <div class="form-group">
                                                 <label class="control-label" >Contact :</label>
                                                 <label class="control-label" >{{ order.contact }}</label>
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
                                                 <label class="control-label" >Discount Given :</label>
                                                 <label class="control-label" >{{ order.discount }}</label>
                                                 <input type="hidden" ng-model="discount_given" ng-value="order.discount">
                                             </div>
                                        </div>
                                         <div class="col-md-4 col-lg-6 col-xs-12">
                                             <div class="form-group">
                                                 <label class="control-label" for="last-name">Return Date <span class="required">*</span>
                                                 </label>
                                                 <div class="">
                                                     <input type="text" id="return_date" name="return_date" class="form-control col-md-7 col-xs-12" required>
                                                 </div>
                                             </div>
                                             <div class="form-group">
                                                 <label class="control-label" for="last-name">Payment Method <span class="required">*</span>
                                                 </label>
                                                 <div class="">
                                                     <select class="form-control" name="payment_method" required>
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
                                                           <th>Purchased Quantity</th>
                                                           <th>Unit Price</th>
                                                           <th>Return Quantity</th>
                                                           <th>Total</th>
                                                       </tr>
                                                    </thead>
                                                    <tbody>
                                                       <tr ng-repeat="item in items">
                                                           <td>
                                                               <select class="form-control select_product" name="product[]" ng-model="item.product_id">
                                                                   <?php foreach ($products as $row){ ?>
                                                                       <option value="<?= $row['id'] ?>"><?= $row['product_name'] ?></option>
                                                                   <?php } ?>
                                                               </select>
                                                           </td>
                                                           <td>
                                                               <input type="text" class="form-control col-md-7 col-xs-12" ng-model="item.quantity" readonly>
                                                           </td>
                                                           <td>
                                                               <input type="text" name="unit_price[]" class="form-control col-md-7 col-xs-12" ng-model="item.unit_price" required>
                                                           </td>
                                                           <td>
                                                               <input type="number" name="quantity[]" class="form-control col-md-7 col-xs-12" ng-model="item.return_quantity" ng-change="return_validate(item);calculate(item);getTotal();" min="0" required>
                                                           </td>
                                                           <td>
                                                               <input type="number" name="total[]" class="form-control col-md-7 col-xs-12" ng-model="item.return_total" step=".01" min="0" required readonly>
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
                                                    <textarea class="form-control" name="note"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-xs-12">
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Sub Total <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="number" id="sub_total" name="sub_total" ng-model="sub_total"
                                                           class="form-control col-md-7 col-xs-12" step=".01" min="0" required readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Discount Given (%)
                                                </label>
                                                <div class="">
                                                    <input type="text" id="discount" name="discount" ng-model="order.discount" class="form-control col-md-7 col-xs-12" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Total Amount
                                                </label>
                                                <div class="">
                                                    <input type="number" id="total_amount" name="total_amount" ng-model="total_amount" class="form-control col-md-7 col-xs-12"  step=".01" min="0" readonly>
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
                                                    <input type="number" id="grand_total" name="grand_total" ng-model="grand_total" class="form-control col-md-7 col-xs-12" step=".01" min="0" required readonly>
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
    $('#supplier').change(function () {
       var supplier = $(this).val();
       var dropdown = '';
        $.ajax({
            url:"ajax.php?ajax=order&id="+supplier,
            type:'GET',
            dataType:'json',
            success:function (response) {
                $('#order_no').children('option:not(:first)').remove();
                attribute_dropdown = '';
                $.each(response,function (key,value) {
                    dropdown += '<option value="'+value.id+'">'+value.purchase_order_no+'</option>';
                });
                $('#order_no').append(dropdown);
            },
            error:function (error) {
                console.log(error);
            }
        });
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

        $scope.order = {};
        $scope.items = {};

        $scope.selectChange = function () {
            var order_no = $scope.order_no;
            $http({
                method: 'GET',
                url: "ajax.php?ajax=order_info&id="+order_no
            }).then(function successCallback(response) {
                console.log(response.data);
                $scope.order = response.data.order;
                $scope.items = response.data.items;
            }, function errorCallback(error) {
                console.log(error);
            });
        };

        $scope.return_validate = function (i) {
            var return_qty = parseFloat(i.return_quantity);
            var qty = parseFloat(i.quantity);
            if (return_qty <= qty) {
            }else {
                i.return_quantity = '';
            }
        };

        $scope.calculate = function(i){
            var unite_price = parseFloat(i.unit_price);
            var quantity = parseFloat(i.return_quantity);
            var total_price = unite_price*quantity;
            i.return_total = total_price;

            var total_bdt = 0;

            for(var i = 0; i < $scope.items.length; i++){
                var product         = $scope.items[i];
                if (product.return_total) {
                    total_bdt += parseFloat(product.return_total);
                }
            }
            $scope.sub_total = total_bdt;
        };

        $scope.getTotal = function(){
            var sub_total = parseFloat($scope.sub_total);
            var discount = parseFloat($scope.order.discount);

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
    $("#submit").click(function (event) {
        if( !confirm('Are you sure that you want to submit the form') ){
            event.preventDefault();
        }
    });
</script>
</body>
</html>
