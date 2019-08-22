<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../vendor/autoload.php';
$purchase = new Purchase();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../include/_head.php') ?>
    <link href="../assets/js/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">

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
                    <h3>Purchased List</h3>
                </div>
                <div class="clearfix"></div>
                <div class="row">
                    <?php session_message() ?>
                    <!-- Page Container -->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <a class="btn btn-success btn-md" href="purchase-add.php"><i class="fa fa-plus-circle"></i> Add</a>
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Purchased Order <small>List</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="pull-right">
                                    <button type="button" class="btn btn-danger btn-md" id="delete_selected_item"><i class="fa fa-trash-o"></i> Delete</button>
                                </div>
                                <hr>
                                <div class="table-responsive">
                                    <table id="datatable-responsive" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Supplier</th>
                                                <th>Order No</th>
                                                <th>Order Date</th>
                                                <th>Total Products</th>
                                                <th>Grand Total</th>
                                                <th>Paid</th>
                                                <th>Due</th>
                                                <th>Created At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($purchase->allPurchased() as $row){
                                            $total_amount = $row['sub_total']-(($row['sub_total']*$row['discount'])/100);
                                            $vat_amount = ($total_amount*$row['vat'])/100;
                                            $grand = $total_amount+$vat_amount;
                                            ?>
                                            <tr>
                                                <td><input type="checkbox" name="ids[]" id="ids" value="<?= $row['purchase_id'] ?>"></td>
                                                <td><?= $row['name'] ?></td>
                                                <td><?= $row['purchase_order_no'] ?></td>
                                                <td><?= $row['order_date'] ?></td>
                                                <td><?= $row['total_qty'] ?></td>
                                                <td><?= $grand ?></td>
                                                <td><?= $row['paid_amount'] ?></td>
                                                <td><?= $row['due_amount'] ?></td>
                                                <td><?= $row['created_at'] ?></td>
                                                <td>
                                                    <a href="purchase-view.php?id=<?= $row['purchase_id'] ?>" class="btn btn-info btn-xs edit">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <button type="button" id="<?= $row['purchase_id'] ?>" class="btn btn-danger btn-xs delete_purchase"><i class="fa fa-trash-o"></i></button>
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
        <?php require_once '../include/_footer.php'?>
        <!-- /footer content -->
    </div>
</div>
<?php include('../include/_script.php') ?>
<script src="../assets/js/datatables.net/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="../assets/js/datatables.net-bs/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script>
    $('.delete_purchase').click(function () {
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
                var url = "ajax.php?ajax=dp&id="+id;
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
    $('#delete_selected_item').click(function () {
        var selected_ids = [];
        $("input:checkbox[name='ids[]']:checked").each(function(){
            selected_ids.push($(this).val());
        });
        if (selected_ids.length>0) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete it!'
            }).then(function (result) {
                if (result.value) {
                    var url = "ajax.php?ajax=d_selected_purchase";
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            'selected_ids': selected_ids
                        },
                        beforeSend: function () {
                            Swal.fire({
                                title: 'Deleting Data.......',
                                showConfirmButton: false,
                                html: '<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>',
                                allowOutsideClick: false
                            });
                        },
                        success: function (response) {
                            Swal.close();
                            console.log(response);
                            if (JSON.parse(response) === "success") {
                                Swal.fire({
                                    title: 'Successfully Deleted',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok',
                                    allowOutsideClick: false
                                }).then(function (result) {
                                    if (result.value) {
                                        window.location.reload();
                                    }
                                });
                            }
                        },
                        error: function (error) {
                            Swal.close();
                            console.log(error);
                        }
                    })
                }
            });
        }else{
            Swal.fire('Warning!','Select Items First','warning');
        }
    });
</script>
</body>
</html>
