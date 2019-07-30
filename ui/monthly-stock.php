<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../vendor/autoload.php';
$report = new Report();
$search_option = $report->search_options();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../include/_head.php') ?>
    <link href="../assets/js/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
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
                    <h3>Monthly Product Stock</h3>
                </div>
                <div class="clearfix"></div>

                <div class="row">
                    <?php session_message() ?>
                    <!-- Page Container -->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Products <small> Stock</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <form class="form-horizontal">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="last-name">Start Date <span class="required">*</span>
                                            </label>
                                            <div class="">
                                                <input type="text" id="start_date" name="start_date" class="form-control col-md-7 col-xs-12">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="last-name">End Date <span class="required">*</span>
                                            </label>
                                            <div class="">
                                                <input type="text" id="end_date" name="end_date" class="form-control col-md-7 col-xs-12">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="last-name">Product (optional)
                                            </label>
                                            <div class="">
                                                <select class="form-control" name="payment_method">
                                                    <option value="">-- Select Product --</option>
                                                    <?php foreach ($search_option['products'] as $product){ ?>
                                                       <option value="<?= $product['id'] ?>"><?= $product['product_name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="last-name">Category (optional)
                                            </label>
                                            <div class="">
                                                <select class="form-control" name="payment_method">
                                                    <option value="">-- Select Category --</option>
                                                    <?php foreach ($search_option['categories'] as $category){ ?>
                                                        <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="last-name">Brand (optional)
                                            </label>
                                            <div class="">
                                                <input type="text" id="contact" name="contact" class="form-control col-md-7 col-xs-12">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="last-name"><i class="fa fa-search-plus"></i></label>
                                            <div class="">
                                                <button class="btn btn-success btn-md"><i class="fa fa-search"></i> Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="table-responsive">
                                    <table id="datatable-responsive" class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Category</th>
                                            <th>Product Name</th>
                                            <th>Brand</th>
                                            <th>Price</th>
                                            <th>Available Qty</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
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
<script src="../assets/js/datatables.net/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="../assets/js/datatables.net-bs/js/dataTables.bootstrap.min.js" type="text/javascript"></script>

<script>
    $('#start_date').datepicker({
        format: 'yyyy-mm-dd',
        startDate: "now()",
        todayHighlight: true,
        autoclose: true
    });
    $('#end_date').datepicker({
        format: 'yyyy-mm-dd',
        startDate: "now()",
        todayHighlight: true,
        autoclose: true
    });
</script>
</body>
</html>
