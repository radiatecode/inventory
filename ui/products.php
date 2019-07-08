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
                        <a class="btn btn-success btn-md" href="products-add.php"><i class="fa fa-plus-circle"></i> Add</a>
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
                                                <th>Enable</th>
                                                <th>Purchase Price</th>
                                                <th>MRP</th>
                                                <th>Repurchase Qty</th>
                                                <th>Created At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($product->allProducts() as $row){ ?>
                                            <tr>
                                                <td><input type="checkbox" name="ids" id="ids" value="<?= $row['id'] ?>"></td>
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
                                                <td><?= $row['purchase_price'] ?></td>
                                                <td><?= $row['mrp'] ?></td>
                                                <td><?= $row['repurchase_qty'] ?></td>
                                                <td><?= $row['created_at'] ?></td>
                                                <td>
                                                    <button type="button" id="<?= $row['id'] ?>"
                                                            data-products='<?= json_encode([ $row['purchase_price'],$row['purchase_discount'],
                                                                $row['qty'], $row['sale_price'], $row['sale_discount'] ] )?>'
                                                            class="btn btn-success btn-xs purchase">
                                                        <i class="fa fa-cart-arrow-down"></i>
                                                    </button>
                                                    <a href="products-view.php?id=<?= $row['id'] ?>" class="btn btn-info btn-xs edit">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
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
    $('.purchase').click(function () {
        var products = JSON.parse($(this).attr('data-products'));
        var id = $(this).attr('id');
        Swal.fire({
            title: "Add Product Quantity",
            text: "Add Quantity for the product ",
            showCancelButton: true,
            confirmButtonText:"Submit",
            html:'<form class="form-horizontal">' +
            '<label>Purchase Price</label>'+
            '<input type="number" id="purchase_price" value="'+products[0]+'" class="form-control" placeholder="Purchase Price">' +
            '<label>Purchase Discount</label>'+
            '<input type="number" id="purchase_discount" value="'+products[1]+'" class="form-control" placeholder="Purchase Discount">' +
            '<label>Product Quantity</label>'+
            '<input type="number" id="qty" class="form-control" value="'+products[2]+'" placeholder="Purchase Qty">' +
            '<label>Sales Price</label>'+
            '<input type="number" id="sale_price" class="form-control" value="'+products[3]+'" placeholder="Sales Price">' +
            '<label>Sales Discount</label>'+
            '<input type="number" id="sale_discount" class="form-control" value="'+products[4]+'" placeholder="Sales Discount"></form>',
            preConfirm: () => {
                var purchase_price = document.getElementById('purchase_price').value;
                var purchase_discount = document.getElementById('purchase_discount').value;
                var qty = document.getElementById('qty').value;
                var sale_price = parseFloat(document.getElementById('sale_price').value);
                var sale_discount = parseFloat(document.getElementById('sale_discount').value);
                var mrp = (sale_price * sale_discount)/100;
                $.ajax({
                    url:'ajax.php?ajax=add_product_quantity&id='+id,
                    type:'POST',
                    data: {
                        'purchase_price':purchase_price,
                        'purchase_discount':purchase_discount,
                        'qty':qty,
                        'sale_price':sale_price,
                        'sale_discount':sale_discount,
                        'mrp':mrp
                    },
                    success:function (response) {
                        if (response==='success') {
                            window.location.reload();
                        }
                    },
                    error:function (error) {
                        console.log(error);
                    }
                });
            }
        });
    })
</script>
</body>
</html>
