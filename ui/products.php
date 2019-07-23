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
                                <div class="pull-right">
                                    <button type="button" class="btn btn-danger btn-md" id="delete_selected_item"><i class="fa fa-trash-o"></i> Delete</button>
                                    <button type="button" class="btn btn-success btn-md" id="enable_selected_item"><i class="fa fa-trash-o"></i> Enable?</button>
                                    <button type="button" class="btn btn-warning btn-md" id="disable_selected_item"><i class="fa fa-trash-o"></i> Disable?</button>
                                </div>
                                <hr>
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
                                                <td><input type="checkbox" name="ids[]" id="ids" value="<?= $row['id'] ?>"></td>
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
                                                                $row['repurchase_qty'], $row['sale_price'], $row['sale_discount'] ] )?>'
                                                            class="btn btn-success btn-xs purchase">
                                                        <i class="fa fa-cart-arrow-down"></i>
                                                    </button>
                                                    <a href="products-view.php?id=<?= $row['id'] ?>" class="btn btn-info btn-xs edit">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <button type="button" id="<?= $row['id'] ?>" class="btn btn-danger btn-xs delete_product"><i class="fa fa-trash-o"></i></button>
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
        console.log(id);
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
            '<label>Repurchase Quantity</label>'+
            '<input type="number" id="qty" class="form-control" value="'+products[2]+'" placeholder="Repurchase Qty">' +
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
                        'repurchase_qty':qty,
                        'sale_price':sale_price,
                        'sale_discount':sale_discount,
                        'mrp':(sale_price+mrp)
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
    });
    $('.delete_product').click(function () {
        var id = $(this).attr('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!'
        }).then(function(result) {
            if (result.value) {
                var url = "ajax.php?ajax=d_product&id="+id;
                $.ajax({
                    url:url,
                    type:'GET',
                    contentType:false,
                    processData:false,
                    beforeSend:function () {
                        Swal.fire({
                            title: 'Deleting Data.......',
                            showConfirmButton: false,
                            html: '<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>',
                            allowOutsideClick: false
                        });
                    },
                    success:function (response) {
                        Swal.close();
                        console.log(response);
                        if (response==="success"){
                            Swal.fire({
                                title: 'Successfully Deleted',
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            }).then(function(result) {
                                if (result.value) {
                                    window.location.reload();
                                }
                            });
                        }
                    },
                    error:function (error) {
                        Swal.close();
                        console.log(error);
                    }
                })
            }
        });
    });
    $('#enable_selected_item').click(function () {
        var selected_ids = [];
        var type = $(this).attr('data-action-type');
        $("input:checkbox[name='ids[]']:checked").each(function(){
            selected_ids.push($(this).val());
        });
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to enable?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!'
        }).then(function(result) {
            if (result.value) {
                var url = "ajax.php?ajax=d_selected_product";
                $.ajax({
                    url:url,
                    type:'POST',
                    data:{
                        'selected_ids':selected_ids
                    },
                    beforeSend:function () {
                        Swal.fire({
                            title: 'Deleting Data.......',
                            showConfirmButton: false,
                            html: '<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>',
                            allowOutsideClick: false
                        });
                    },
                    success:function (response) {
                        Swal.close();
                        if (JSON.parse(response)==="success"){
                            Swal.fire({
                                title: 'Successfully Deleted',
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok',
                                allowOutsideClick: false
                            }).then(function(result) {
                                if (result.value) {
                                    window.location.reload();
                                }
                            });
                        }
                    },
                    error:function (error) {
                        Swal.close();
                        console.log(error);
                    }
                })
            }
        });
    });
    $('#disable_selected_item').click(function () {
        var selected_ids = [];
        $("input:checkbox[name='ids[]']:checked").each(function(){
            selected_ids.push($(this).val());
        });
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to enable?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!'
        }).then(function(result) {
            if (result.value) {
                var url = "ajax.php?ajax=d_selected_product";
                $.ajax({
                    url:url,
                    type:'POST',
                    data:{
                        'selected_ids':selected_ids
                    },
                    beforeSend:function () {
                        Swal.fire({
                            title: 'Deleting Data.......',
                            showConfirmButton: false,
                            html: '<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>',
                            allowOutsideClick: false
                        });
                    },
                    success:function (response) {
                        Swal.close();
                        if (JSON.parse(response)==="success"){
                            Swal.fire({
                                title: 'Successfully Deleted',
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok',
                                allowOutsideClick: false
                            }).then(function(result) {
                                if (result.value) {
                                    window.location.reload();
                                }
                            });
                        }
                    },
                    error:function (error) {
                        Swal.close();
                        console.log(error);
                    }
                })
            }
        });
    });
</script>
</body>
</html>
