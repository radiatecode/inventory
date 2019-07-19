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
                    <h3>Product Stock</h3>
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
                                <div class="table-responsive">
                                    <table id="question_table" class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Category</th>
                                            <th>Product Name</th>
                                            <th>Brand</th>
                                            <th>Enable</th>
                                            <th>Price</th>
                                            <th>Available Qty</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $sl=1; foreach ($product->stock() as $row){
                                            $available = ($row['purchase_quantity']-($row['sale_quantity']+$row['purchase_return_quantity']));
                                            ?>
                                            <tr style="<?= $available<=$row['repurchase_qty'] ? 'background-color: #cd494c; color: white':'' ?>">
                                                <td><?= $sl ?></td>
                                                <td><?= $row['name'] ?></td>
                                                <td>
                                                    <?php if (!empty($row['thumb'])){ ?>
                                                        <img src="../assets/images/<?= $row['thumb'] ?>" class="image"
                                                             style="height: 30px; width: 30px; border-radius: 30px">
                                                    <?php } ?>
                                                    <?= $row['product_name'] ?>
                                                </td>
                                                <td><?= $row['brand_name'] ?></td>
                                                <td>
                                                    <?php if ($row['enable']==1){ ?>
                                                        <i class="fa fa-check-circle"></i>
                                                    <?php }else{ ?>
                                                        <i class="fa fa-close"></i>
                                                    <?php } ?>
                                                </td>
                                                <td><?= $row['mrp'] ?></td>
                                                <td><?= $available ?></td>
                                            </tr>
                                        <?php $sl++; } ?>
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
