<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../vendor/autoload.php';

$brand = new Brand();
if (isset($_POST['add_brand'])) {
    $brand->store($_POST,$_FILES);
}elseif (isset($_POST['edit_brand'])){
    $brand->update($_POST,$_FILES);
}
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
                    <h3>Brand</h3>
                </div>
                <div class="clearfix"></div>

                <div class="row">
                    <?php session_message() ?>
                    <!-- Page Container -->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <button class="btn btn-success btn-md" data-toggle="modal" data-target=".add_modal"><i class="fa fa-plus-circle"></i> Add</button>
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Brand <small>List</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="table-responsive">
                                    <table id="question_table" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Logo</th>
                                                <th>Name</th>
                                                <th>Products</th>
                                                <th>Enable</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($brand->allBrands() as $row){ ?>
                                            <tr>
                                                <td><input type="checkbox" name="ids" id="ids" value="<?= $row['id'] ?>"></td>
                                                <td>
                                                    <img src="../assets/images/<?= $row['logo'] ?>" style="width: 50px;height: 50px;">
                                                </td>
                                                <td>
                                                  <?= $row['brand_name'] ?>
                                                </td>
                                                <td>

                                                </td>
                                                <td>
                                                    <?php if ($row['enable']==1){ ?>
                                                      <i class="fa fa-check-circle"></i>
                                                    <?php }else{ ?>
                                                        <i class="fa fa-close"></i>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <button type="button" id="<?= $row['id'] ?>"
                                                            class="btn btn-info btn-xs edit"
                                                            data-brand="<?= $row['brand_name'] ?>"
                                                            data-logo="<?= $row['logo'] ?>"
                                                            data-target=".add_modal" data-toggle="modal">
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
                    <!-- Add modal -->
                    <div class="modal fade add_modal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                    </button>
                                    <h4 class="modal-title" id="myModalLabel">Add New Brand</h4>
                                </div>
                                <form class="form-horizontal" action="brand.php" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Brand Name <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" id="brand_name" name="brand_name" required="required" class="form-control col-md-7 col-xs-12">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Photo
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="file" id="photo" name="photo" class="form-control col-md-7 col-xs-12">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Photo Preview
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <img id="photo-preview" src="" style="width: 150px;height: 150px">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                        <button  type="submit" id="submit_brand" name="add_brand" class="btn btn-success"><i class="fa fa-save"></i> Save Comment</button>
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
        var brand = $(this).attr('data-brand');
        var logo = $(this).attr('data-logo');
        $('.modal-title').html('Update Brand');
        $('.modal-body').append('<input type="hidden" name="edit_id" value="'+id+'">');
        $('#submit_brand').html('<i class="fa fa-save"></i> Update');
        $('#submit_brand').attr('name','edit_brand');
        $('#photo-preview').attr('src','../assets/images/'+logo);
        $('#brand_name').val(brand);
    })
</script>
</body>
</html>
