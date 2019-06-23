<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../vendor/autoload.php';

$attribute = new ProductAttributes();
$category = new Category();
if (isset($_POST['add_attribute'])) {
    $attribute->store($_POST);
}elseif (isset($_POST['edit_attribute'])){
    $attribute->update($_POST);
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
                    <h3>Attributes</h3>
                </div>
                <div class="clearfix"></div>

                <div class="row">
                    <?php session_message() ?>
                    <!-- Page Container -->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <button class="btn btn-success btn-md" data-toggle="modal" data-target=".add_modal"><i class="fa fa-plus-circle"></i> Add</button>
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Attributes <small>List</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="table-responsive">
                                    <table id="question_table" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Category</th>
                                                <th>Enable</th>
                                                <th>Created At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($attribute->allAttributes() as $row){ ?>
                                            <tr>
                                                <td><input type="checkbox" name="ids" id="ids" value="<?= $row['id'] ?>"></td>
                                                <td><?= $row['attribute_name'] ?></td>
                                                <td><?= $row['name'] ?></td>
                                                <td>
                                                    <?php if ($row['enable']==1){ ?>
                                                        <i class="fa fa-check-circle"></i>
                                                    <?php }else{ ?>
                                                        <i class="fa fa-close"></i>
                                                    <?php } ?>
                                                </td>
                                                <td><?= $row['created_at'] ?></td>
                                                <td>
                                                    <button type="button" id="<?= $row['id'] ?>"
                                                            class="btn btn-info btn-xs edit"
                                                            data-attribute='<?= $row['attribute_name'] ?>'
                                                            data-category='<?= $row['category_id'] ?>'
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
                                    <h4 class="modal-title" id="myModalLabel">Add New Attribute</h4>
                                </div>
                                <form class="form-horizontal" action="attributes.php" method="post">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Category <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select id="category" name="category" required="required" class="form-control col-md-7 col-xs-12">
                                                   <option value="">-- Select Category --</option>
                                                   <?php foreach ($category->allCategories() as $row){ ?>
                                                     <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                                   <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Attribute Name <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" id="attribute_name" name="attribute_name" required="required" class="form-control col-md-7 col-xs-12">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                        <button  type="submit" id="submit" name="add_attribute" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
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
    $('.edit').click(function () {
        var id = $(this).attr('id');
        var attribute = $(this).attr('data-attribute');
        var category = $(this).attr('data-category');

        $('.modal-title').html('Update Attribute');
        $('.modal-body').append('<input type="hidden" name="edit_id" value="'+id+'">');
        $('#submit').html('<i class="fa fa-save"></i> Update');
        $('#submit').attr('name','edit_attribute');
        $('#attribute_name').val(attribute);
        $('#category').val(category);
    });
</script>
</body>
</html>
