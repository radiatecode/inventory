<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../vendor/autoload.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../include/_head.php') ?>
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
                    <h3>New Sale Order</h3>
                </div>
                <div class="clearfix"></div>

                <div class="row">
                    <?php
                    session_message();
                    //messages($customer->getMessage())
                    ?>
                    <!-- Page Container -->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>New Sale <small>Order</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <form action="customer-add.php" method="post" class="form-horizontal" enctype="multipart/form-data">
                                    <div class="row">
                                         <div class="col-md-4 col-lg-6 col-xs-12">
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Customer <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                   <select class="form-control" name="customer">
                                                       <option value="">-- Select Customer --</option>
                                                   </select>
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                 <label class="control-label" for="last-name">Contact <span class="required">*</span>
                                                 </label>
                                                 <div class="">
                                                     <input type="text" id="contact" name="contact" class="form-control col-md-7 col-xs-12">
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
                                                    <textarea class="form-control" name="billing_address"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="col-md-4 col-lg-6 col-xs-12">
                                             <div class="form-group">
                                                 <label class="control-label" for="last-name">Order Date <span class="required">*</span>
                                                 </label>
                                                 <div class="">
                                                     <input type="text" id="order_date" name="order_date" class="form-control col-md-7 col-xs-12">
                                                 </div>
                                             </div>
                                             <div class="form-group">
                                                 <label class="control-label" for="last-name">Delivery Date <span class="required">*</span>
                                                 </label>
                                                 <div class="">
                                                     <input type="text" id="delivery_date" name="delivery_date" class="form-control col-md-7 col-xs-12">
                                                 </div>
                                             </div>
                                             <div class="form-group">
                                                 <label class="control-label" for="last-name">Sales Person <span class="required">*</span>
                                                 </label>
                                                 <div class="">
                                                     <select class="form-control" name="sales_person">
                                                         <option value="">-- Select Sales Person --</option>
                                                     </select>
                                                 </div>
                                             </div>
                                        </div>
                                        <div class="col-md-12 col-lg-12 col-xs-12">
                                            <button class="btn btn-info btn-xs"><i class="fa fa-plus-circle"></i></button>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                       <tr>
                                                           <th>Product</th>
                                                           <th>Unit Price</th>
                                                           <th>Quantity</th>
                                                           <th>Total</th>
                                                       </tr>
                                                    </thead>
                                                    <tbody>
                                                       <tr>
                                                           <td>
                                                               <select class="form-control" name="product[]">
                                                                   <option value="">-- Select Product --</option>
                                                               </select>
                                                           </td>
                                                           <td>
                                                               <input type="text" name="unit_price[]" class="form-control col-md-7 col-xs-12">
                                                           </td>
                                                           <td>
                                                               <input type="text" name="quantity[]" class="form-control col-md-7 col-xs-12">
                                                           </td>
                                                           <td>
                                                               <input type="text" name="total[]" class="form-control col-md-7 col-xs-12">
                                                           </td>
                                                       </tr>
                                                    </tbody>
                                                </table>
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
                                                    <input type="text" id="sub_total" name="sub_total" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Discount <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="text" id="discount" name="discount" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Total <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="text" id="total" name="total" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">VAT (%) <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="text" id="vat" name="vat" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">VAT <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="text" id="vat_amount" name="vat_amount" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Grand Total <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="text" id="grand_total" name="grand_total" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Paid Amount <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="text" id="paid" name="paid" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="last-name">Due Amount <span class="required">*</span>
                                                </label>
                                                <div class="">
                                                    <input type="text" id="due" name="due" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-lg-12 col-xs-12">
                                        <div>
                                            <button type="submit" name="submit" id="submit" class="btn btn-success btn-md"><i class="fa fa-save"></i> Save</button>
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
<script>

</script>
</body>
</html>
