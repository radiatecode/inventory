<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../vendor/autoload.php';
$orders = new Order();
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
                    <h3>Order List</h3>
                </div>
                <div class="clearfix"></div>
                <div class="row">
                    <?php session_message() ?>
                    <!-- Page Container -->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <a class="btn btn-success btn-md" href="order-add.php"><i class="fa fa-plus-circle"></i> Add</a>
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Sales Order <small>List</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="table-responsive">
                                    <table id="question_table" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Customer</th>
                                                <th>Order ID</th>
                                                <th>Order Date</th>
                                                <th>Total Products</th>
                                                <th>Grand Total</th>
                                                <th>Paid</th>
                                                <th>Due</th>
                                                <th>Order Status</th>
                                                <th>Created At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($orders->allOrders() as $row){ ?>
                                            <tr>
                                                <td><input type="checkbox" name="ids" id="ids" value="<?= $row['order_id'] ?>"></td>
                                                <td><?= $row['name'] ?></td>
                                                <td>
                                                    #ODI <?= $row['order_id'] ?>
                                                </td>
                                                <td><?= $row['order_date'] ?></td>
                                                <td>
                                                    <?= $row['total_qty'] ?>
                                                </td>
                                                <td>
                                                    <?= $row['sub_total'] ?>
                                                </td>
                                                <td><?= $row['paid_amount'] ?></td>
                                                <td><?= $row['due_amount'] ?></td>
                                                <td><?= $row['order_status'] ?></td>
                                                <td><?= $row['created_at'] ?></td>
                                                <td>
                                                    <a href="order-view.php?id=<?= $row['order_id'] ?>" class="btn btn-info btn-xs edit">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <button type="button" id="<?= $row['order_id'] ?>" class="btn btn-danger btn-xs delete_order"><i class="fa fa-trash-o"></i></button>
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
    $('.delete_order').click(function () {
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
                var url = "ajax.php?ajax=do&id="+id;
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
</script>
</body>
</html>
