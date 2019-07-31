<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../vendor/autoload.php';

$supplier = new Suppliers();
if (isset($_POST['add_supplier'])) {
    $supplier->store($_POST);
}elseif (isset($_POST['edit_supplier'])){
    $supplier->update($_POST);
}
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
                    <h3>Suppliers</h3>
                </div>
                <div class="clearfix"></div>

                <div class="row">
                    <?php session_message() ?>
                    <!-- Page Container -->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <button class="btn btn-success btn-md" data-toggle="modal" data-target=".add_modal"><i class="fa fa-plus-circle"></i> Add</button>
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Suppliers <small>List</small></h2>
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
                                                <th>Name</th>
                                                <th>Address</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Created At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($supplier->allSuppliers() as $row){ ?>
                                            <tr>
                                                <td><input type="checkbox" name="ids[]" id="ids" value="<?= $row['id'] ?>"></td>
                                                <td><?= $row['name'] ?></td>
                                                <td><?= $row['address'] ?></td>
                                                <td><?= $row['email'] ?></td>
                                                <td><?= $row['phone'] ?></td>
                                                <td><?= $row['created_at'] ?></td>
                                                <td>
                                                    <button type="button" id="<?= $row['id'] ?>"
                                                            class="btn btn-info btn-xs edit"
                                                            data-supplier='<?= json_encode($row) ?>'
                                                            data-target=".add_modal" data-toggle="modal">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    <button type="button" id="<?= $row['id'] ?>" class="btn btn-danger btn-xs delete_item"><i class="fa fa-trash-o"></i></button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Add modal -->
                    <div class="modal fade add_modal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                    </button>
                                    <h4 class="modal-title" id="myModalLabel">Add New Supplier</h4>
                                </div>
                                <form class="form-horizontal" action="suppliers.php" method="post">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Name <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" id="name" name="name" required="required" class="form-control col-md-7 col-xs-12">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Address <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" id="address" name="address" required="required" class="form-control col-md-7 col-xs-12">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Email <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" id="email" name="email" required="required" class="form-control col-md-7 col-xs-12">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Phone <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" id="phone" name="phone" required="required" class="form-control col-md-7 col-xs-12">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                        <button  type="submit" id="submit" name="add_supplier" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> <!-- / Add modal -->
                    <!-- / End Page Container -->
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
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#photo-preview').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#photo").change(function() {
        readURL(this);
    });

    $('.edit').click(function () {
        var id = $(this).attr('id');
        var supplier = JSON.parse($(this).attr('data-supplier'));

        $('.modal-title').html('Update Supplier');
        $('.modal-body').append('<input type="hidden" name="edit_id" value="'+id+'">');
        $('#submit').html('<i class="fa fa-save"></i> Update');
        $('#submit').attr('name','edit_supplier');
        $('#name').val(supplier.name);
        $('#address').val(supplier.address);
        $('#email').val(supplier.email);
        $('#phone').val(supplier.phone);
    });
    $('.delete_item').click(function () {
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
                var url = "ajax.php?ajax=d_supplier&id="+id;
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
        if(selected_ids.length>0) {
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
                    var url = "ajax.php?ajax=d_selected_supplier";
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
