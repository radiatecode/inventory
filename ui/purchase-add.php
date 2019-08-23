<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../vendor/autoload.php';
$product = new Products();
$products = $product->enableProducts();
$supplier = new Suppliers();
$suppliers = $supplier->allSuppliers();
$purchase = new Purchase();
if (isset($_POST['submit'])){
    $purchase->store($_POST);
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
                    <h3>New Purchase Order</h3>
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
                                <h2>New Purchase <small>Order</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <form action="purchase-add.php" method="post" class="form-horizontal">
                                    <div class="row">
                                         <div class="col-md-4 col-lg-6 col-xs-12">
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Supplier <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                   <select class="form-control" name="supplier" required>
                                                       <option value="">-- Select Supplier --</option>
                                                       <?php foreach ($suppliers as $row){ ?>
                                                           <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                                       <?php } ?>
                                                   </select>
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                 <label class="control-label" for="last-name">Contact <span class="required">*</span>
                                                 </label>
                                                 <div class="">
                                                     <input type="text" id="contact" name="contact" class="form-control col-md-7 col-xs-12" required>
                                                 </div>
                                             </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Email
                                                </label>
                                                <div class="">
                                                    <input type="email" id="email" name="email" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Billing Address <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <textarea class="form-control" name="billing_address" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="col-md-4 col-lg-6 col-xs-12">
                                             <div class="form-group">
                                                 <label class="control-label" for="last-name">Order Date <span class="required">*</span>
                                                 </label>
                                                 <div class="">
                                                     <input type="text" id="order_date" name="order_date" class="form-control col-md-7 col-xs-12" required>
                                                 </div>
                                             </div>
                                             <div class="form-group">
                                                 <label class="control-label" for="last-name">Payment Date <span class="required">*</span>
                                                 </label>
                                                 <div class="">
                                                     <input type="text" id="payment_date" name="payment_date" class="form-control col-md-7 col-xs-12" required>
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
                                                       <tr ng-repeat="item in items" ng-model="newItemName" >
                                                           <td>
                                                               <select class="form-control select_product" ng-model="item.product"
                                                                       ng-change="selectChange(item);" name="product[]" required>
                                                                   <option value="">-- Select Product --</option>
                                                                   <?php foreach ($products as $row){ ?>
                                                                       <option value="<?= $row['id'] ?>"><?= $row['product_name'] ?></option>
                                                                   <?php } ?>
                                                               </select>
                                                           </td>
                                                           <td>
                                                               <input type="number" name="unit_price[]" class="form-control col-md-7 col-xs-12" ng-model="item.unit_price" ng-change="calculate(item);getTotal();" step=".01" min="0" required>
                                                           </td>
                                                           <td>
                                                               <input type="number" name="quantity[]" class="form-control col-md-7 col-xs-12" ng-model="item.quantity" ng-change="calculate(item);getTotal();" min="0" required>
                                                           </td>
                                                           <td>
                                                               <input type="text" name="total[]" class="form-control col-md-7 col-xs-12" ng-model="item.total" required readonly>
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
                                                <label class="control-label" for="last-name">Discount <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="number" id="discount" name="discount" ng-model="discount" ng-change="getTotal();" class="form-control col-md-7 col-xs-12" min="0">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Total Amount <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="number" id="total_amount" name="total_amount" ng-model="total_amount" class="form-control col-md-7 col-xs-12" step=".01" min="0" required readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">VAT (%) <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="number" id="vat" name="vat" value="0" class="form-control col-md-7 col-xs-12" ng-model="vat" ng-change="getTotal();" min="0" required>
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
                                                <label class="control-label" for="last-name">Paid Amount <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="number" id="paid" name="paid"  ng-model="paid" ng-change="getTotal();" class="form-control col-md-7 col-xs-12" step=".01" min="0" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Due Amount <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="number" id="due" name="due" ng-model="due" class="form-control col-md-7 col-xs-12" step=".01" readonly>
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
    var products = <?php echo $product->jsonProducts(); ?>;
    console.log(products);
    //$(function () {
    //    var drop_down = '';

    //    $.each(products,function (key,value) {
    //        drop_down += '<option value="'+value.id+'">'+value.product_name+'</option>';
    //    });
    //    $('.add_product').click(function () {
    //        $("table tbody").append('<tr><td><select class="form-control select_product" name="product[]">' +
    //            '<option value="">-- Select Product --</option>'+drop_down+'</select></td>' +
    //            '<td><input type="text" name="unit_price[]" class="form-control col-md-7 col-xs-12 set_unit_price"></td>' +
    //            '<td><input type="text" name="quantity[]" class="form-control col-md-7 col-xs-12 set_quantity"></td>' +
    //            '<td><input type="text" name="total[]" class="form-control col-md-7 col-xs-12 set_total"></td>' +
    //            '<td><button type="button" class="btn btn-danger btn-xs remCF"><i class="fa fa-trash-o"></i></button></td></tr>');
    //    });
    //    $("table").on('click','.remCF',function(){
    //        $(this).parent().parent().remove();
    //    });
    //
    //    $('.select_product').change(function () {
    //        var id = $(this).val();
    //        var obj = searchObjects(products,id);
    //        console.log(obj);
    //    });
    //});
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
            i.unit_price = parseFloat(obj.purchase_price);
        };

        $scope.calculate = function(i){
            var unite_price = parseFloat(i.unit_price);
            var quantity = parseFloat(i.quantity);
            var total_price = unite_price*quantity;
            i.total = total_price;

        };

        $scope.getTotal = function(){
            var total_qty = 0;
            var total_bdt = 0;

            for(var i = 0; i < $scope.items.length; i++){
                var product         = $scope.items[i];
                total_qty           += parseFloat(product.quantity);
                total_bdt           += parseFloat(product.total);
            }
            //$scope.tQty=  total_qty;
            //$scope.tBdt =  total_bdt;
            $scope.sub_total = total_bdt;

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
    $("#submit").click(function (event) {
        if( !confirm('Are you sure that you want to submit the form') ){
            event.preventDefault();
        }
    });
</script>
</body>
</html>
