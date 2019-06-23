<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../vendor/autoload.php';
$product = new Products();
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
                    <h3>Products</h3>
                </div>
                <div class="clearfix"></div>

                <div class="row">
                    <?php session_message() ?>
                    <!-- Page Container -->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <button class="btn btn-success btn-md" data-toggle="modal" data-target=".add_modal"><i class="fa fa-plus-circle"></i> Add</button>
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Products <small>List</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="table-responsive">
                                    <table id="question_table" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Category</th>
                                                <th>Product Name</th>
                                                <th>Brand</th>
                                                <th>Supplier</th>
                                                <th>Enable</th>
                                                <th>Price</th>
                                                <th>Qty</th>
                                                <th>Available Qty</th>
                                                <th>Created At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($product->allProducts() as $row){ ?>
                                            <tr>
                                                <td><input type="checkbox" name="ids" id="ids" value="<?= $row['id'] ?>"></td>
                                                <td><?= $row['name'] ?></td>
                                                <td><?= $row['product_name'] ?></td>
                                                <td><?= $row['brand_name'] ?></td>
                                                <td><?= $row['supplier'] ?></td>
                                                <td>
                                                    <?php if ($row['enable']==1){ ?>
                                                      <i class="fa fa-check-circle"></i>
                                                    <?php }else{ ?>
                                                        <i class="fa fa-close"></i>
                                                    <?php } ?>
                                                </td>
                                                <td><?= $row['product_price'] ?></td>
                                                <td></td>
                                                <td></td>
                                                <td><?= $row['created_at'] ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-success btn-xs edit">
                                                        <i class="fa fa-cart-arrow-down"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-info btn-xs edit">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button>
                                                </td>
                                            </tr>
                                        <?php } ?>
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
<script>

</script>
</body>
</html>
